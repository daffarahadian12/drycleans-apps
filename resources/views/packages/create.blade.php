@extends('partials.app')

@section('title', 'Tambah Paket Laundry')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tambah Paket Laundry Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('packages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                        id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg') }}" min="0" required>
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
                                        id="estimated_days" name="estimated_days" value="{{ old('estimated_days') }}" min="1" required>
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
                                {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                        <div class="form-text">Paket yang tidak aktif tidak akan ditampilkan pada form transaksi</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Paket
                        </button>
                    </div>
                </form>
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
@endsection
