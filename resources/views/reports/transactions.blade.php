@extends('partials.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Laporan Transaksi</h5>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ number_format($stats['total_transactions']) }}</h4>
                                    <p class="mb-0">Total Transaksi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['total_discount'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Diskon</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</h4>
                                    <p class="mb-0">Rata-rata Transaksi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai:</label>
                            <input type="date" class="form-control" id="start-date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="end-date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="process">Process</option>
                                <option value="washing">Washing</option>
                                <option value="drying">Drying</option>
                                <option value="ironing">Ironing</option>
                                <option value="ready">Ready</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary">
                                <i class="feather-refresh-cw"></i> Reset
                            </button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Berat (Kg)</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal Order</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{!! $transaction->invoice_number !!}</td>
                                        <td>
                                            {!! $transaction->customer->name !!}
                                            @if ($transaction->customer->is_member)
                                                <span class="badge badge-warning text-dark ms-1">Member</span>
                                            @endif
                                        </td>
                                        <td>{!! $transaction->package->name !!}</td>
                                        <td>{!! $transaction->weight !!} kg</td>
                                        <td>
                                            <strong class="text-success">Rp {!! number_format($transaction->total_amount, 0, ',', '.') !!}</strong>
                                            @if ($transaction->discount_amount > 0)
                                                <br><small class="text-danger">Diskon: -Rp {!! number_format($transaction->discount_amount, 0, ',', '.') !!}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'process' => 'info',
                                                    'washing' => 'primary',
                                                    'drying' => 'secondary',
                                                    'ironing' => 'dark',
                                                    'ready' => 'success',
                                                    'completed' => 'success',
                                                ];
                                                $color = $statusColors[$transaction->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-{{ $color }}">{!! ucfirst($transaction->status) !!}</span>
                                        </td>
                                        <td>{!! $transaction->order_date ? Carbon\Carbon::parse($transaction->order_date)->translatedFormat('d M Y') : '-' !!}</td>
                                        <td>{!! $transaction->user->name ?? '-' !!}</td>
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
                const table = $('#transactions-table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [6, 'desc']
                    ]
                });

                // Tambah filter khusus tanggal
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const start = $('#start-date').val();
                    const end = $('#end-date').val();
                    const orderDate = data[6]; // Kolom tanggal order

                    if (!orderDate) return false;

                    const orderMoment = moment(orderDate, 'DD MMM YYYY');
                    if (!orderMoment.isValid()) return false;

                    const startDate = start ? moment(start, 'YYYY-MM-DD') : null;
                    const endDate = end ? moment(end, 'YYYY-MM-DD') : null;

                    if (startDate && orderMoment.isBefore(startDate, 'day')) return false;
                    if (endDate && orderMoment.isAfter(endDate, 'day')) return false;

                    return true;
                });

                // Trigger filter tanggal
                $('#start-date, #end-date').on('change', function() {
                    table.draw();
                });

                // Trigger filter status
                $('#status-filter').on('change', function() {
                    const status = $(this).val();
                    table.column(5).search(status).draw();
                });

                // Reset semua filter
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
