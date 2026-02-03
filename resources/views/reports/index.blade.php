@extends('partials.app')

@section('title', 'Dashboard Laporan')

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Transaksi</h6>
                            <h3>{{ number_format($stats['total_transactions']) }}</h3>
                        </div>
                        <div class="db-icon text-black">
                            <i class="fas fa-receipt"></i>
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
                            <h6>Total Pelanggan</h6>
                            <h3>{{ number_format($stats['total_customers']) }}</h3>
                        </div>
                        <div class="db-icon text-black">
                            <i class="fas fa-users"></i>
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
                            <h6>Revenue Total</h6>
                            <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="db-icon text-black">
                            <i class="fas fa-money-bill-wave"></i>
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
                            <h6>Revenue Bulan Ini</h6>
                            <h3>Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="db-icon text-black">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <h5>Menu Laporan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="fas fa-receipt fa-3x text-primary mb-3"></i>
                                    <h5>Laporan Transaksi</h5>
                                    <p class="text-muted">Lihat detail semua transaksi dan export data</p>
                                    <a href="{{ route('reports.transactions') }}" class="btn btn-primary">
                                        <i class="feather-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                                    <h5>Laporan Pelanggan</h5>
                                    <p class="text-muted">Analisis data pelanggan dan member</p>
                                    <a href="{{ route('reports.customers') }}" class="btn btn-success">
                                        <i class="feather-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="fas fa-box fa-3x text-warning mb-3"></i>
                                    <h5>Laporan Paket</h5>
                                    <p class="text-muted">Performa paket laundry dan popularitas</p>
                                    <a href="{{ route('reports.packages') }}" class="btn btn-warning">
                                        <i class="feather-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card comman-shadow">
                <div class="card-header">
                    <h5>Status Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $stats['pending_transactions'] }}</h4>
                                <p class="text-muted">Pending</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success">{{ $stats['completed_transactions'] }}</h4>
                                <p class="text-muted">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card comman-shadow">
                <div class="card-header">
                    <h5>Status Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $stats['member_customers'] }}</h4>
                                <p class="text-muted">Member</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-info">{{ $stats['total_customers'] - $stats['member_customers'] }}</h4>
                                <p class="text-muted">Non-Member</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
