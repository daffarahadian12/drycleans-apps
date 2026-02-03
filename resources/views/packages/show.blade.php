@extends('partials.app')

@section('title', 'Detail Paket Laundry')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2">Detail Paket Laundry</h5>
                    <p class="card-text mb-0">Informasi lengkap paket {{ $package->name }}</p>
                </div>
                <div>
                    <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3 class="mb-3">{{ $package->name }}</h3>
                        <p class="text-muted">{{ $package->description ?: 'Tidak ada deskripsi tersedia' }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Harga per Kg:</div>
                                    <div class="col-6 text-end fw-bold">Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Estimasi:</div>
                                    <div class="col-6 text-end fw-bold">{{ $package->estimated_days }} hari</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Total Transaksi:</div>
                                    <div class="col-6 text-end fw-bold">{{ $package->transactions()->count() }} transaksi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Paket</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Nama Paket</td>
                                <td width="5%">:</td>
                                <td>{{ $package->name }}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi</td>
                                <td>:</td>
                                <td>{{ $package->description ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Harga per Kg</td>
                                <td>:</td>
                                <td>Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Estimasi Hari</td>
                                <td>:</td>
                                <td>{{ $package->estimated_days }} hari</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Sistem</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Dibuat pada</td>
                                <td width="5%">:</td>
                                <td>{{ $package->created_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Terakhir diupdate</td>
                                <td>:</td>
                                <td>{{ $package->updated_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Total Transaksi</td>
                                <td>:</td>
                                <td>{{ $package->transactions()->count() }} transaksi</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('packages.toggle-status', $package->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $package->is_active ? 'outline-danger' : 'outline-success' }}">
                                <i class="fas fa-{{ $package->is_active ? 'toggle-off' : 'toggle-on' }}"></i> 
                                {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @if($package->transactions()->count() === 0)
                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini? Tindakan ini tidak dapat dibatalkan.');">
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
                    $recentTransactions = $package->transactions()->with(['customer'])->latest()->take(5)->get();
                @endphp
                
                @if($recentTransactions->count() > 0)
                    @foreach($recentTransactions as $transaction)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <small class="fw-bold">{{ $transaction->invoice_number }}</small><br>
                                <small class="text-muted">{{ $transaction->customer->name }}</small>
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
                        <a href="{{ route('transactions.index', ['package' => $package->id]) }}" class="btn btn-sm btn-outline-primary">
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
