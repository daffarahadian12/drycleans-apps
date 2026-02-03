@extends('partials.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Transaksi Hari Ini</h6>
                            <h3>{{ $todayTransactions }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-shopping-cart" style="font-size: 2rem; color: #667eea;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Pendapatan Hari Ini</h6>
                            <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-money-bill-wave" style="font-size: 2rem; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Order Dalam Proses</h6>
                            <h3>{{ $processingOrders }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-sync-alt" style="font-size: 2rem; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Siap Diambil</h6>
                            <h3>{{ $readyOrders }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-check-circle" style="font-size: 2rem; color: #17a2b8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-12 col-lg-6">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title">Status Transaksi</h5>
                        </div>
                        <div class="col-6">
                            <ul class="chart-list-out">
                                <li><span class="circle-blue"></span>Proses</li>
                                <li><span class="circle-green"></span>Selesai</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Progress Bars -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Pending</span>
                            <span class="text-muted">{{ $statusStats['pending'] ?? 0 }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalTransactions > 0 ? ($statusStats['pending'] / $totalTransactions * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Process</span>
                            <span class="text-muted">{{ $statusStats['process'] ?? 0 }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $totalTransactions > 0 ? ($statusStats['process'] / $totalTransactions * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Washing</span>
                            <span class="text-muted">{{ $statusStats['washing'] ?? 0 }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $totalTransactions > 0 ? ($statusStats['washing'] / $totalTransactions * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Ready</span>
                            <span class="text-muted">{{ $statusStats['ready'] ?? 0 }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalTransactions > 0 ? ($statusStats['ready'] / $totalTransactions * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Completed</span>
                            <span class="text-muted">{{ $statusStats['completed'] ?? 0 }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-dark" style="width: {{ $totalTransactions > 0 ? ($statusStats['completed'] / $totalTransactions * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title">Statistik Bulanan</h5>
                        </div>
                        <div class="col-6">
                            <ul class="chart-list-out">
                                <li><span class="circle-blue"></span>Transaksi</li>
                                <li><span class="circle-green"></span>Revenue</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $monthlyTransactions }}</h4>
                            <p class="text-muted mb-0">Transaksi Bulan Ini</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ number_format($monthlyRevenue / 1000000, 1) }}M</h4>
                            <p class="text-muted mb-0">Revenue Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title">Transaksi Terbaru</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}"
                                                class="text-primary">
                                                {{ $transaction->invoice_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                {{ $transaction->customer->name }}
                                            </div>
                                        </td>
                                        <td>{{ $transaction->package->name }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $statusClass = match ($transaction->status) {
                                                    'pending' => 'badge-warning',
                                                    'process' => 'badge-info',
                                                    'washing' => 'badge-primary',
                                                    'drying' => 'badge-secondary',
                                                    'ironing' => 'badge-dark',
                                                    'ready' => 'badge-success',
                                                    'completed' => 'badge-success',
                                                    default => 'badge-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus me-2"></i>Transaksi Baru
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customers.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus me-2"></i>Pelanggan Baru
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('track') }}" class="btn btn-info btn-block">
                                <i class="fas fa-search me-2"></i>Lacak Status
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('reports.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-chart-bar me-2"></i>Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Pelanggan</h6>
                            <h3>{{ $totalCustomers }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-users" style="font-size: 2rem; color: #6f42c1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Member Aktif</h6>
                            <h3>{{ $activeMembers }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-star" style="font-size: 2rem; color: #fd7e14;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Transaksi Bulan Ini</h6>
                            <h3>{{ $monthlyTransactions }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-calendar-alt" style="font-size: 2rem; color: #20c997;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Pendapatan Bulan Ini</h6>
                            <h3>{{ number_format($monthlyRevenue / 1000000, 1) }}M</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-chart-line" style="font-size: 2rem; color: #e83e8c;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
