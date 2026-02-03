@extends('partials.app')

@section('title', 'Edit Paket Laundry')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Paket Laundry</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('packages.update', $package->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name', $package->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Jelaskan detail layanan yang termasuk dalam paket ini</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_per_kg" class="form-label">Harga per Kg <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price_per_kg') is-invalid @enderror" 
                                        id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg', $package->price_per_kg) }}" min="0" required>
                                </div>
                                @error('price_per_kg')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estimated_days" class="form-label">Estimasi Hari <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('estimated_days') is-invalid @enderror" 
                                        id="estimated_days" name="estimated_days" value="{{ old('estimated_days', $package->estimated_days) }}" min="1" required>
                                    <span class="input-group-text">hari</span>
                                </div>
                                @error('estimated_days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Estimasi waktu pengerjaan</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                        <div class="form-text">Paket yang tidak aktif tidak akan ditampilkan pada form transaksi</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Informasi Paket</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Status</h6>
                    <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Dibuat pada</h6>
                    <p>{{ $package->created_at->translatedFormat('d F Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Terakhir diupdate</h6>
                    <p>{{ $package->updated_at->translatedFormat('d F Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Jumlah Transaksi</h6>
                    <p>{{ $package->transactions()->count() }} transaksi</p>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <form action="{{ route('packages.toggle-status', $package->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $package->is_active ? 'outline-danger' : 'outline-success' }} w-100">
                            <i class="fas fa-{{ $package->is_active ? 'toggle-off' : 'toggle-on' }}"></i> 
                            {{ $package->is_active ? 'Nonaktifkan Paket' : 'Aktifkan Paket' }}
                        </button>
                    </form>
                    
                    @if($package->transactions()->count() === 0)
                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Hapus Paket
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-danger w-100" disabled title="Paket tidak dapat dihapus karena sudah digunakan dalam transaksi">
                            <i class="fas fa-trash"></i> Hapus Paket
                        </button>
                        <small class="text-muted text-center">Paket tidak dapat dihapus karena sudah digunakan dalam transaksi</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: '@foreach($errors->all() as $error)<div class="mb-1">{{ $error }}</div>@endforeach',
        });
    </script>
@endif

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
