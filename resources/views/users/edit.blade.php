@extends('partials.app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                            id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                        <div class="form-text">
                            User yang tidak aktif tidak dapat login ke sistem
                            @if($user->id === auth()->id())
                                <br><small class="text-warning">Anda tidak dapat menonaktifkan akun sendiri</small>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Informasi User</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Status</h6>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Role</h6>
                    @php
                        $roleColors = [
                            'admin' => 'primary',
                            'owner' => 'info', 
                            'karyawan' => 'warning'
                        ];
                    @endphp
                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }} fs-6">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Bergabung pada</h6>
                    <p>{{ $user->created_at->translatedFormat('d F Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Terakhir diupdate</h6>
                    <p>{{ $user->updated_at->translatedFormat('d F Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Jumlah Transaksi</h6>
                    <p>{{ $user->transactions()->count() }} transaksi</p>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $user->is_active ? 'outline-danger' : 'outline-success' }} w-100">
                                <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i> 
                                {{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}
                            </button>
                        </form>
                        
                        @if($user->transactions()->count() === 0)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Hapus User
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-danger w-100" disabled title="User tidak dapat dihapus karena memiliki transaksi">
                                <i class="fas fa-trash"></i> Hapus User
                            </button>
                            <small class="text-muted text-center">User tidak dapat dihapus karena memiliki transaksi</small>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle"></i> Ini adalah akun Anda sendiri</small>
                        </div>
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
