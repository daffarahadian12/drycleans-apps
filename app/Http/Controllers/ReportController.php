<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,owner');
    }

    public function index()
    {
        // Statistics for dashboard
        $stats = [
            'total_transactions' => Transaction::count(),
            'total_customers' => Customer::count(),
            'total_packages' => Package::count(),
            'total_revenue' => Transaction::sum('total_amount'),
            'monthly_revenue' => Transaction::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount'),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'member_customers' => Customer::where('is_member', true)->count(),
        ];

        return view('reports.index', compact('stats'));
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['customer', 'package', 'user']);

        // Apply date filters
        if ($request->filled('start_date')) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->orderBy('order_date', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_amount'),
            'total_discount' => $transactions->sum('discount_amount'),
            'avg_transaction' => $transactions->count() > 0 ? $transactions->avg('total_amount') : 0,
        ];

        // Handle export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportTransactionsCsv($transactions);
        }

        return view('reports.transactions', compact('transactions', 'stats'));
    }

    public function customers(Request $request)
    {
        $query = Customer::withCount('transactions')->withSum('transactions', 'total_amount');

        // Filter status member
        if ($request->filled('status')) {
            if ($request->status === 'Member') {
                $query->where('is_member', true);
            } elseif ($request->status === 'Non-Member') {
                $query->where('is_member', false);
            }
        }

        // Filter tanggal registrasi
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        // Statistik pelanggan
        $stats = [
            'total_customers' => $customers->count(),
            'member_customers' => $customers->where('is_member', true)->count(),
            'total_spent' => $customers->sum('transactions_sum_total_amount'),
            'avg_spent_per_customer' => $customers->count() > 0 ? $customers->avg('transactions_sum_total_amount') : 0,
        ];

        // Export CSV jika diminta
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCustomersCsv($customers);
        }

        return view('reports.customers', compact('customers', 'stats'));
    }

    public function packages(Request $request)
    {
        $query = Package::withCount('transactions')->withSum('transactions', 'total_amount');

        // Apply status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $packages = $query->orderBy('transactions_sum_total_amount', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_packages' => $packages->count(),
            'active_packages' => $packages->where('is_active', true)->count(),
            'total_revenue' => $packages->sum('transactions_sum_total_amount'),
            'most_popular' => $packages->sortByDesc('transactions_count')->first(),
        ];

        // Handle export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportPackagesCsv($packages);
        }

        return view('reports.packages', compact('packages', 'stats'));
    }

    private function exportTransactionsCsv($transactions)
    {
        $filename = 'laporan_transaksi_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xef) . chr(0xbb) . chr(0xbf));

            // Header
            fputcsv($file, ['Invoice', 'Pelanggan', 'Paket', 'Berat (Kg)', 'Subtotal', 'Diskon', 'Total', 'Status', 'Tanggal Order', 'Estimasi Selesai', 'Petugas']);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [$transaction->invoice_number, $transaction->customer->name, $transaction->package->name, $transaction->weight, $transaction->subtotal, $transaction->discount_amount, $transaction->total_amount, ucfirst($transaction->status), $transaction->order_date ? Carbon::parse($transaction->order_date)->format('d/m/Y') : '', $transaction->estimated_completion ? Carbon::parse($transaction->estimated_completion)->format('d/m/Y') : '', $transaction->user->name ?? '']);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportCustomersCsv($customers)
    {
        $filename = 'laporan_pelanggan_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xef) . chr(0xbb) . chr(0xbf));

            // Header
            fputcsv($file, ['Nama', 'Email', 'Telepon', 'Alamat', 'Status Member', 'Points', 'Total Transaksi', 'Total Belanja', 'Terdaftar']);

            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [$customer->name, $customer->email, $customer->phone, $customer->address, $customer->is_member ? 'Member' : 'Non-Member', $customer->points, $customer->transactions_count, $customer->transactions_sum_total_amount ?? 0, $customer->created_at->format('d/m/Y')]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportPackagesCsv($packages)
    {
        $filename = 'laporan_paket_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($packages) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xef) . chr(0xbb) . chr(0xbf));

            // Header
            fputcsv($file, ['Nama Paket', 'Deskripsi', 'Harga per Kg', 'Estimasi Hari', 'Status', 'Total Transaksi', 'Total Revenue']);

            // Data
            foreach ($packages as $package) {
                fputcsv($file, [$package->name, $package->description, $package->price_per_kg, $package->estimated_days, $package->is_active ? 'Aktif' : 'Tidak Aktif', $package->transactions_count, $package->transactions_sum_total_amount ?? 0]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
