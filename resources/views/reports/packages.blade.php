@extends('partials.app')

@section('title', 'Laporan Paket')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Laporan Paket Laundry</h5>
                        </div>
                        <div class="col-6 text-end">
                            <form method="GET" class="d-inline">
                                @foreach(request()->except('export') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <button type="submit" name="export" value="csv" class="btn btn-success">
                                    <i class="feather-download"></i> Export CSV
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ number_format($stats['total_packages']) }}</h4>
                                    <p class="mb-0">Total Paket</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ number_format($stats['active_packages']) }}</h4>
                                    <p class="mb-0">Paket Aktif</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $stats['most_popular']->name ?? '-' }}</h4>
                                    <p class="mb-0">Paling Populer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary">
                                <i class="feather-refresh-cw"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="packages-table">
                            <thead>
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Deskripsi</th>
                                    <th>Harga per Kg</th>
                                    <th>Estimasi Hari</th>
                                    <th>Status</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                <tr>
                                    <td>{{ $package->name }}</td>
                                    <td>{{ $package->description }}</td>
                                    <td>Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</td>
                                    <td>{{ $package->estimated_days }} hari</td>
                                    <td>
                                        @if($package->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $package->transactions_count }}x</td>
                                    <td>Rp {{ number_format($package->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    $(document).ready(function () {
        const table = $('#packages-table').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[6, 'desc']]
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const status = $('#status-filter').val();
            const statusText = data[4];

            if (!status) return true;
            if (status === '1' && statusText.includes('Aktif')) return true;
            if (status === '0' && statusText.includes('Tidak Aktif')) return true;

            return false;
        });

        $('#status-filter').on('change', function () {
            table.draw();
        });

        $('#reset-filter').on('click', function () {
            $('#status-filter').val('');
            table.search('').columns().search('').draw();
        });
    });
</script>
@endpush
@endsection