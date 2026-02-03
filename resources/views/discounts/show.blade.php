@extends('partials.app')

@section('title', 'Detail Diskon')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card comman-shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5>Detail Diskon</h5>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-warning btn-sm">
                            <i class="feather-edit"></i> Edit
                        </a>
                        <a href="{{ route('discounts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="feather-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama Diskon</strong></td>
                                <td>: {{ $discount->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipe</strong></td>
                                <td>: 
                                    @if($discount->type == 'percentage')
                                        <span class="badge badge-primary">Persentase</span>
                                    @else
                                        <span class="badge badge-info">Nominal</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Nilai</strong></td>
                                <td>: 
                                    @if($discount->type == 'percentage')
                                        {{ $discount->value }}%
                                    @else
                                        Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Min. Berat</strong></td>
                                <td>: {{ $discount->min_weight ? $discount->min_weight . ' kg' : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Min. Pembelian</strong></td>
                                <td>: {{ $discount->min_amount ? 'Rp ' . number_format($discount->min_amount, 0, ',', '.') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    @if($discount->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat</strong></td>
                                <td>: {{ $discount->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diupdate</strong></td>
                                <td>: {{ $discount->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($discount->description)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Deskripsi:</h6>
                        <p class="text-muted">{{ $discount->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
