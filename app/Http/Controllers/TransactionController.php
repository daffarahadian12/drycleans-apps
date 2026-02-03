<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Discount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Fungsi Pembantu untuk menerjemahkan delivery_status ke Bahasa Indonesia.
     * @param string $status
     * @return string
     */
    private function getDeliveryStatusText($status)
    {
        switch ($status) {
            case 'scheduled':
                return 'Barang Diantar/Jemput (Scheduled)';
            case 'received':
                return 'Barang Diterima';
            case 'packed':
                return 'Selesai Dikemas/Siap Ambil';
            case 'delivered':
                return 'Ambil/Selesai';
            default:
                return ucfirst($status);
        }
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'package', 'user']);

        // Filter status pengerjaan (status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter status pengantaran/dropship (delivery_status)
        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter tanggal (opsional)
        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        $transactions = $query->orderBy('order_date', 'desc')->get();

        // Format data untuk DataTables dan tampilan
        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'customer_name' => $transaction->customer->name,
                'customer_is_member' => $transaction->customer->is_member,
                'customer_points' => $transaction->customer->points,
                'package_name' => $transaction->package->name,
                'weight' => $transaction->weight,
                'total_amount' => $transaction->total_amount,
                'subtotal' => $transaction->subtotal,
                'discount_amount' => $transaction->discount_amount,
                
                // Status Pengerjaan Laundry
                'status' => $transaction->status,

                // Status Pengantaran/Pengambilan (Baru)
                'delivery_status_text' => $this->getDeliveryStatusText($transaction->delivery_status ?? 'scheduled'),
                'delivery_status_raw' => $transaction->delivery_status, 

                // Tambahan: format filter dan tampilan berbeda
                'order_date_raw' => $transaction->order_date ? Carbon::parse($transaction->order_date)->format('Y-m-d') : null,
                'order_date' => $transaction->order_date ? Carbon::parse($transaction->order_date)->translatedFormat('d M Y') : '-',

                'estimated_completion' => $transaction->estimated_completion ? Carbon::parse($transaction->estimated_completion)->translatedFormat('d M Y') : '-',
            ];
        });

        return view('transactions.index', compact('formattedTransactions'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::active()->orderBy('name')->get();
        $discounts = Discount::active()->orderBy('min_weight')->get();

        return view('transactions.create', compact('customers', 'packages', 'discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'weight' => 'required|numeric|min:0.1',
            'notes' => 'nullable|string',
            // Tambahkan validasi untuk status pengantaran awal dari form (jika ada)
            'delivery_type' => 'nullable|in:scheduled,received', 
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($request->customer_id);
            $package = Package::findOrFail($request->package_id);

            // Tentukan status awal
            $initialDeliveryStatus = $request->delivery_type ?? 'scheduled'; 

            $transaction = new Transaction([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'user_id' => auth()->id(),
                'weight' => $request->weight,
                'price_per_kg' => $package->price_per_kg,
                'order_date' => Carbon::now(),
                'estimated_completion' => Carbon::now()->addDays($package->estimated_days),
                'notes' => $request->notes,
                'status' => 'pending', // Status pengerjaan awal
                'delivery_status' => $initialDeliveryStatus, // Status pengantaran awal
            ]);

            // Calculate totals with discounts
            $transaction->calculateTotal();
            $transaction->save();

            // Update customer statistics
            $customer->updateTransactionStats($transaction->total_amount);

            // Add points to customer
            $pointsEarned = $customer->addPoints($transaction->total_amount);

            // Check if customer should become member (example: after 5 transactions or 500k spent)
            if (!$customer->is_member && ($customer->total_transactions >= 5 || $customer->total_spent >= 500000)) {
                $customer->becomeMember();
            }

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', "Transaksi berhasil dibuat! Invoice: {$transaction->invoice_number}. Points earned: {$pointsEarned}");
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'package', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::active()->orderBy('name')->get();
        $discounts = Discount::active()->orderBy('min_weight')->get();

        return view('transactions.edit', compact('transaction', 'customers', 'packages', 'discounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'weight' => 'required|numeric|min:0.1',
            'notes' => 'nullable|string',
            'delivery_status' => 'nullable|in:scheduled,received,packed,delivered', // Validasi delivery_status
        ]);

        DB::beginTransaction();

        try {
            $oldAmount = $transaction->total_amount;
            $customer = Customer::findOrFail($request->customer_id);
            $package = Package::findOrFail($request->package_id);

            $data = [
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'weight' => $request->weight,
                'price_per_kg' => $package->price_per_kg,
                'notes' => $request->notes,
            ];
            
            // Perbarui delivery_status jika ada di request
            if ($request->filled('delivery_status')) {
                $data['delivery_status'] = $request->delivery_status;
            }

            $transaction->update($data);

            // Recalculate totals
            $transaction->calculateTotal();
            $transaction->save();

            // Update customer statistics (adjust for the difference)
            $amountDifference = $transaction->total_amount - $oldAmount;
            if ($amountDifference != 0) {
                $customer->increment('total_spent', $amountDifference);

                // Adjust points
                if ($amountDifference > 0) {
                    $customer->addPoints($amountDifference);
                }
            }
            
            // Sinkronisasi status jika delivery_status diupdate via method ini
            if ($request->filled('delivery_status') && $request->delivery_status === 'delivered') {
                $transaction->update([
                    'status' => 'completed',
                    'actual_completion' => Carbon::now(),
                ]);
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui Status Pengantaran/Pengambilan (delivery_status) dan menyinkronkan status pengerjaan.
     * Status: scheduled, received, packed, delivered
     */
    public function updateDeliveryStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'delivery_status' => 'required|in:scheduled,received,packed,delivered',
        ]);

        $transaction->update([
            'delivery_status' => $request->delivery_status,
        ]);

        // LOGIKA SINKRONISASI PENTING: Jika status pengantaran/pengambilan adalah 'delivered' (Ambil), 
        // maka status pengerjaan dianggap 'completed' dan actual_completion diisi.
        if ($request->delivery_status === 'delivered') {
            $transaction->update([
                'status' => 'completed',
                'actual_completion' => Carbon::now(),
            ]);
        }


        return redirect()->route('transactions.index')->with('success', 'Status pengantaran berhasil diupdate!');
    }

    /**
     * Memperbarui Status Pengerjaan Laundry (status) dan menyinkronkan delivery_status.
     * Status: pending, process, washing, drying, ironing, ready, completed
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,process,washing,drying,ironing,ready,completed',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        // LOGIKA SINKRONISASI PENTING: Jika status Pengerjaan selesai 'ready', 
        // status pengantaran harus menjadi 'packed' (Selesai Dikemas/Siap Ambil).
        if ($request->status === 'ready') {
             // Hanya ubah jika belum diselesaikan atau dikirimkan
             if ($transaction->delivery_status !== 'delivered') { 
                 $transaction->update(['delivery_status' => 'packed']);
             }
        }
        
        // Opsional: Jika status diubah dari completed ke status lain, actual_completion harus di-null-kan.
        if ($request->status !== 'completed' && $transaction->actual_completion !== null) {
             $transaction->update(['actual_completion' => null]);
        }


        return redirect()->route('transactions.index')->with('success', 'Status transaksi berhasil diupdate!');
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Adjust customer statistics
            $customer = $transaction->customer;
            $customer->decrement('total_transactions');
            $customer->decrement('total_spent', $transaction->total_amount);

            // Remove points (approximate)
            $pointsToRemove = floor($transaction->total_amount / 1000) * $customer->points_earned_rate;
            $customer->decrement('points', min($pointsToRemove, $customer->points));

            $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function printInvoice(Transaction $transaction)
    {
        $transaction->load(['customer', 'package', 'user']);
        return view('transactions.invoice', compact('transaction'));
    }

    public function getDiscountPreview(Request $request)
    {
        $weight = $request->weight;
        $customerId = $request->customer_id;

        if (!$weight || !$customerId) {
            return response()->json([
                'discount' => null,
                'customer' => null,
            ]);
        }

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json([
                'discount' => null,
                'customer' => null,
            ]);
        }

        $discount = Discount::getBestDiscount($weight, $customer->is_member);

        return response()->json([
            'discount' => $discount,
            'customer' => [
                'is_member' => $customer->is_member,
                'points' => $customer->points,
                'total_transactions' => $customer->total_transactions,
                'total_spent' => $customer->total_spent,
            ],
        ]);
    }
}