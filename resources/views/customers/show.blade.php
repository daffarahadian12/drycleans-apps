@extends('partials.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2">Detail Pelanggan</h5>
                    <p class="card-text mb-0">Informasi lengkap pelanggan {{ $customer->name }}</p>
                </div>
                <div>
                    <span class="badge bg-{{ $customer->is_member ? 'success' : 'secondary' }} fs-6">
                        {{ $customer->is_member ? 'Member' : 'Non-Member' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3 class="mb-3">{{ $customer->name }}</h3>
                        @if($customer->phone)
                            <p class="text-muted mb-1"><i class="fas fa-phone"></i> {{ $customer->phone }}</p>
                        @endif
                        @if($customer->address)
                            <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $customer->address }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Status:</div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-{{ $customer->is_member ? 'success' : 'secondary' }}">
                                            {{ $customer->is_member ? 'Member' : 'Non-Member' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Points:</div>
                                    <div class="col-6 text-end fw-bold">{{ $customer->points }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Total Transaksi:</div>
                                    <div class="col-6 text-end fw-bold">{{ $customer->total_transactions }} transaksi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Personal</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Nama</td>
                                <td width="5%">:</td>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>:</td>
                                <td>{{ $customer->phone ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $customer->address ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $customer->is_member ? 'success' : 'secondary' }}">
                                        {{ $customer->is_member ? 'Member' : 'Non-Member' }}
                                    </span>
                                </td>
                            </tr>
                            @if($customer->is_member)
                            <tr>
                                <td>Member Sejak</td>
                                <td>:</td>
                                <td>{{ $customer->member_since ? $customer->member_since->translatedFormat('d F Y') : '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Transaksi</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Total Transaksi</td>
                                <td width="5%">:</td>
                                <td>{{ $customer->total_transactions }} transaksi</td>
                            </tr>
                            <tr>
                                <td>Total Pengeluaran</td>
                                <td>:</td>
                                <td>Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Points</td>
                                <td>:</td>
                                <td>{{ $customer->points }} points</td>
                            </tr>
                            <tr>
                                <td>Rate Points</td>
                                <td>:</td>
                                <td>{{ $customer->points_earned_rate }} points per Rp 1.000</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($customer->transactions->count() === 0)
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Transaksi Terbaru</h6>
            </div>
            <div class="card-body">
                @php
                    $recentTransactions = $customer->transactions()->with(['package', 'user'])->latest()->take(5)->get();
                @endphp
                
                @if($recentTransactions->count() > 0)
                    @foreach($recentTransactions as $transaction)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <small class="fw-bold">{{ $transaction->invoice_number }}</small><br>
                                <small class="text-muted">{{ $transaction->package->name }}</small>
                            </div>
                            <div class="text-end">
                                <small class="fw-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</small><br>
                                <span class="badge bg-{{ $transaction->status_color }} badge-sm">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('transactions.index', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua Transaksi
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
@endsection
