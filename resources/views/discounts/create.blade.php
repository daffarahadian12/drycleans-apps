@extends('partials.app')

@section('title', 'Tambah Diskon')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card comman-shadow">
                <div class="card-header">
                    <h5>Tambah Diskon Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('discounts.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Diskon <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Nilai Diskon <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="discount_percentage">%</span>
                                        <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror"
                                            id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" step="0.01"
                                            min="0" required>
                                    </div>
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                            <label for="is_member_only" class="form-label">Khusus Member <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('is_member_only') is-invalid @enderror" id="is_member_only"
                                name="is_member_only" required>
                                <option value="0" {{ old('is_member_only') == '0' ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('is_member_only') == '1' ? 'selected' : '' }}>Ya</option>
                            </select>
                            @error('is_member_only')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_weight" class="form-label">Min. Berat (Kg)</label>
                                    <input type="number" class="form-control @error('min_weight') is-invalid @enderror"
                                        id="min_weight" name="min_weight" value="{{ old('min_weight') }}" step="0.1"
                                        min="0">
                                    @error('min_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('discounts.index') }}" class="btn btn-secondary">
                                <i class="feather-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save"></i> Simpan Diskon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
