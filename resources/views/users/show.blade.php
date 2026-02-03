@extends('partials.app')

@section('title', 'Detail User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2">Detail User</h5>
                    <p class="card-text mb-0">Informasi lengkap user {{ $user->name }}</p>
                </div>
                <div>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    @php
                        $roleColors = [
                            'admin' => 'primary',
                            'owner' => 'info', 
                            'karyawan' => 'warning'
                        ];
                    @endphp
                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }} fs-6 ms-1">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3 class="mb-3">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span class="badge bg-info ms-2">You</span>
                            @endif
                        </h3>
                        <p class="text-muted mb-1"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-muted mb-1"><i class="fas fa-phone"></i> {{ $user->phone }}</p>
                        @endif
                        @if($user->address)
                            <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $user->address }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Role:</div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Bergabung:</div>
                                    <div class="col-6 text-end fw-bold">{{ $user->created_at->translatedFormat('d M Y') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Total Transaksi:</div>
                                    <div class="col-6 text-end fw-bold">{{ $user->transactions()->count() }} transaksi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Personal</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Nama Lengkap</td>
                                <td width="5%">:</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>:</td>
                                <td>{{ $user->phone ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $user->address ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Informasi Sistem</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="40%">Bergabung pada</td>
                                <td width="5%">:</td>
                                <td>{{ $user->created_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Terakhir diupdate</td>
                                <td>:</td>
                                <td>{{ $user->updated_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Total Transaksi</td>
                                <td>:</td>
                                <td>{{ $user->transactions()->count() }} transaksi</td>
                            </tr>
                            @if($user->email_verified_at)
                            <tr>
                                <td>Email Verified</td>
                                <td>:</td>
                                <td>{{ $user->email_verified_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'outline-danger' : 'outline-success' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i> 
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            @if($user->transactions()->count() === 0)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
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
                    $recentTransactions = $user->transactions()->with(['customer', 'package'])->latest()->take(5)->get();
                @endphp
                
                @if($recentTransactions->count() > 0)
                    @foreach($recentTransactions as $transaction)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <small class="fw-bold">{{ $transaction->invoice_number }}</small><br>
                                <small class="text-muted">{{ $transaction->customer->name }}</small><br>
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
                        <a href="{{ route('transactions.index', ['user' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
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
