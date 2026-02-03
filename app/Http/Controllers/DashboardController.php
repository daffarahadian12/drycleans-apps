<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,owner,karyawan');
    }

    public function index()
    {
        // Today's statistics
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_amount');
        
        // Processing and ready orders - sesuaikan dengan status di migration
        $processingOrders = Transaction::whereIn('status', ['pending', 'process', 'washing', 'drying', 'ironing'])->count();
        $readyOrders = Transaction::where('status', 'ready')->count();
        
        // Monthly statistics
        $monthlyTransactions = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthlyRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        // Status breakdown - sesuaikan dengan status di migration
        $statusStats = [
            'pending' => Transaction::where('status', 'pending')->count(),
            'process' => Transaction::where('status', 'process')->count(),
            'washing' => Transaction::where('status', 'washing')->count(),
            'drying' => Transaction::where('status', 'drying')->count(),
            'ironing' => Transaction::where('status', 'ironing')->count(),
            'ready' => Transaction::where('status', 'ready')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
        ];
        
        $totalTransactions = Transaction::count();
        
        // Recent transactions
        $recentTransactions = Transaction::with(['customer', 'package'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Customer statistics
        $totalCustomers = Customer::count();
        $activeMembers = Customer::where('is_member', true)->count();
        
        return view('dashboard', compact(
            'todayTransactions',
            'todayRevenue',
            'processingOrders',
            'readyOrders',
            'monthlyTransactions',
            'monthlyRevenue',
            'statusStats',
            'totalTransactions',
            'recentTransactions',
            'totalCustomers',
            'activeMembers'
        ));
    }
}
