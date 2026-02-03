@extends('partials.app')

@section('title', 'Laporan Pelanggan')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Laporan Pelanggan</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('reports.customers', array_merge(request()->except('export'), ['export' => 'csv'])) }}"
                                class="btn btn-success">
                                <i class="feather-download"></i> Export CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ number_format($stats['total_customers']) }}</h4>
                                    <p class="mb-0">Total Pelanggan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ number_format($stats['member_customers']) }}</h4>
                                    <p class="mb-0">Member</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Belanja</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['avg_spent_per_customer'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Rata-rata per Pelanggan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="row mb-4">

                        <div class="col-md-3">
                            <label>Terdaftar Mulai:</label>
                            <input type="date" class="form-control" id="start-date">
                        </div>

                        <div class="col-md-3">
                            <label>Terdaftar Sampai:</label>
                            <input type="date" class="form-control" id="end-date">
                        </div>

                        <div class="col-md-3">
                            <label>Status Member:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="Member">Member</option>
                                <option value="Non-Member">Non-Member</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary" id="reset-filter">
                                <i class="feather-refresh-cw"></i> Reset
                            </button>

                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="customers-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Points</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Belanja</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td data-status="{{ $customer->is_member ? 'Member' : 'Non-Member' }}">
                                            @if ($customer->is_member)
                                                <span class="badge badge-warning text-dark">Member</span>
                                                @if ($customer->member_since)
                                                    <br><small class="text-muted">Sejak:
                                                        {{ $customer->member_since->translatedFormat('d M Y') }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-danger">Non-Member</span>
                                            @endif
                                        </td>

                                        <td><span class="badge badge-info">{{ $customer->points }} pts</span></td>
                                        <td>{{ $customer->transactions_count }}x</td>
                                        <td>Rp
                                            {{ number_format($customer->transactions_sum_total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $customer->created_at->translatedFormat('d M Y') }}</td>
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
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script>
            $(document).ready(function() {
                const table = $('#customers-table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [6, 'desc']
                    ]
                });

                // Filter tanggal
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const start = $('#start-date').val();
                    const end = $('#end-date').val();
                    const dateStr = data[6]; // kolom 'Terdaftar' (index ke-6)

                    if (!dateStr) return false;

                    const createdMoment = moment(dateStr, 'DD MMM YYYY');
                    if (!createdMoment.isValid()) return false;

                    const startMoment = start ? moment(start, 'YYYY-MM-DD') : null;
                    const endMoment = end ? moment(end, 'YYYY-MM-DD') : null;

                    if (startMoment && createdMoment.isBefore(startMoment, 'day')) return false;
                    if (endMoment && createdMoment.isAfter(endMoment, 'day')) return false;

                    return true;
                });

                // Auto-filter saat user ubah status atau tanggal
                $('#status-filter, #start-date, #end-date').on('change', function() {
                    // Jalankan filter DataTables built-in (untuk tanggal)
                    table.draw();

                    // Manual filter berdasarkan data-status
                    const status = $('#status-filter').val();
                    table.rows().every(function() {
                        const row = this.node();
                        const statusText = $(row).find('td[data-status]').data('status');

                        if (!status || statusText === status) {
                            $(row).show();
                        } else {
                            $(row).hide();
                        }
                    });
                });

                // Reset
                $('#reset-filter').on('click', function(e) {
                    e.preventDefault(); // <-- tambahkan ini
                    $('#status-filter').val('');
                    $('#start-date').val('');
                    $('#end-date').val('');
                    table.search('').columns().search('').draw();

                    // Show all rows again (karena filter status manual hide/show)
                    table.rows().every(function() {
                        $(this.node()).show();
                    });
                });

            });
        </script>
    @endpush

@endsection
