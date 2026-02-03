@extends('partials.app')

@section('title', 'List Transaksi')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Daftar Transaksi</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                                <i class="feather-plus"></i> Tambah Transaksi
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Status Pengerjaan:</label>
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
                        {{-- FILTER BARU UNTUK STATUS PENGANTARAN --}}
                        <div class="col-md-3">
                            <label class="form-label">Status Pengantaran:</label>
                            <select class="form-select" id="delivery-status-filter">
                                <option value="">Semua Status Pengantaran</option>
                                <option value="scheduled">Barang Diantar/Jemput</option>
                                <option value="received">Barang Diterima</option>
                                <option value="packed">Selesai Dikemas</option>
                                <option value="delivered">Ambil/Selesai</option>
                            </select>
                        </div>
                        {{-- END FILTER BARU --}}

                        <div class="col-md-3">
                            <label class="form-label">Tanggal Order:</label>
                            <input type="date" class="form-control" id="date-filter" placeholder="Filter Tanggal">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-secondary d-block" id="reset-filter">
                                <i class="feather-refresh-cw"></i> Reset Filter
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
                                    <th>Status Pengerjaan</th> {{-- Kolom 1 --}}
                                    <th>Status Pengantaran</th> {{-- Kolom 2 --}}
                                    <th>Tanggal Order</th>
                                    <th>Estimasi Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction['invoice_number'] }}</td>
                                        <td>
                                            {{ $transaction['customer_name'] }}
                                            @if ($transaction['customer_is_member'])
                                                <span class="badge badge-warning text-dark ms-1">Member</span>
                                            @endif
                                            <br><small class="text-muted">Points:
                                                {{ $transaction['customer_points'] }}</small>
                                        </td>
                                        <td>{{ $transaction['package_name'] }}</td>
                                        <td>{{ $transaction['weight'] }} kg</td>
                                        <td>
                                            <strong class="text-success">Rp
                                                {{ number_format($transaction['total_amount'], 0, ',', '.') }}</strong>
                                            @if ($transaction['discount_amount'] > 0)
                                                <br><small class="text-muted">Subtotal: Rp
                                                    {{ number_format($transaction['subtotal'], 0, ',', '.') }}</small>
                                                <br><small class="text-danger">Diskon: -Rp
                                                    {{ number_format($transaction['discount_amount'], 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        {{-- STATUS PENGERJAAN --}}
                                        <td data-status-raw="{{ $transaction['status'] }}">
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
                                                $color = $statusColors[$transaction['status']] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $color }}">{{ ucfirst($transaction['status']) }}</span>
                                        </td>
                                        {{-- STATUS PENGANTARAN (BARU) --}}
                                        <td data-delivery-status-raw="{{ $transaction['delivery_status_raw'] }}">
                                            @php
                                                $deliveryStatusColors = [
                                                    'scheduled' => 'info',
                                                    'received' => 'primary',
                                                    'packed' => 'success',
                                                    'delivered' => 'dark',
                                                ];
                                                $dColor = $deliveryStatusColors[$transaction['delivery_status_raw']] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $dColor }}">{{ $transaction['delivery_status_text'] }}</span>
                                        </td>
                                        {{-- END STATUS PENGANTARAN --}}
                                        <td data-order-date="{{ $transaction['order_date_raw'] }}">
                                            {{ $transaction['order_date'] }}</td>
                                        <td>{{ $transaction['estimated_completion'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('transactions.show', $transaction['id']) }}"
                                                    class="btn btn-sm btn-info me-1" title="Detail">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('transactions.edit', $transaction['id']) }}"
                                                    class="btn btn-sm btn-warning me-1" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>

                                                {{-- DROPDOWN UNTUK UPDATE STATUS GANDA --}}
                                                <div class="dropdown me-1">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false" title="Update Status">
                                                        <i class="feather-refresh-cw"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="updateStatus({{ $transaction['id'] }}, '{{ $transaction['status'] }}')">
                                                                Update Pengerjaan ({{ ucfirst($transaction['status']) }})
                                                            </a></li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="updateDeliveryStatus({{ $transaction['id'] }}, '{{ $transaction['delivery_status_raw'] }}')">
                                                                Update Pengantaran ({{ $transaction['delivery_status_text'] }})
                                                            </a></li>
                                                    </ul>
                                                </div>
                                                {{-- END DROPDOWN --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pengerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pengerjaan</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="pending">Pending</option>
                                <option value="process">Process</option>
                                <option value="washing">Washing</option>
                                <option value="drying">Drying</option>
                                <option value="ironing">Ironing</option>
                                <option value="ready">Ready (Selesai Dikerjakan)</option>
                                <option value="completed">Completed (Selesai Total)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status Pengerjaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- MODAL BARU UNTUK STATUS PENGANTARAN --}}
    <div class="modal fade" id="deliveryStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pengantaran/Pengambilan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deliveryStatusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="delivery_status" class="form-label">Status Pengantaran</label>
                            <select class="form-select" name="delivery_status" id="delivery_status" required>
                                <option value="scheduled">Barang Diantar/Jemput</option>
                                <option value="received">Barang Diterima</option>
                                <option value="packed">Selesai Dikemas</option>
                                <option value="delivered">Ambil/Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status Pengantaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END MODAL BARU --}}

    @if (session('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

    @push('scripts')
        <script>
            // FUNGSI UPDATE STATUS PENGERJAAN
            function updateStatus(transactionId, currentStatus) {
                const form = document.getElementById('statusForm');
                const statusSelect = document.getElementById('status');

                // Menggunakan route name yang sudah direvisi: transactions.update-status
                form.action = "{{ url('transactions') }}/" + transactionId + "/status";
                statusSelect.value = currentStatus;

                const modal = new bootstrap.Modal(document.getElementById('statusModal'));
                modal.show();
            }
            
            // FUNGSI BARU UPDATE STATUS PENGANTARAN
            function updateDeliveryStatus(transactionId, currentDeliveryStatus) {
                const form = document.getElementById('deliveryStatusForm');
                const statusSelect = document.getElementById('delivery_status');
                
                // Menggunakan route name yang baru: transactions.update-delivery-status
                form.action = "{{ url('transactions') }}/" + transactionId + "/delivery-status";
                statusSelect.value = currentDeliveryStatus;

                const modal = new bootstrap.Modal(document.getElementById('deliveryStatusModal'));
                modal.show();
            }

            $(document).ready(function() {
                let table = $('#transactions-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [7, 'desc']
                    ] // order_date column (index 7 setelah penambahan kolom)
                });

                // Custom global search
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const selectedStatus = $('#status-filter').val();
                    const selectedDeliveryStatus = $('#delivery-status-filter').val(); // Filter baru
                    const selectedDate = $('#date-filter').val();

                    const row = table.row(dataIndex).node();
                    const orderDate = $(row).find('td[data-order-date]').data('order-date');
                    
                    // Ambil nilai raw status dari atribut data
                    const rawStatus = $(row).find('td[data-status-raw]').data('status-raw');
                    const rawDeliveryStatus = $(row).find('td[data-delivery-status-raw]').data('delivery-status-raw');

                    let matchStatus = true;
                    let matchDeliveryStatus = true;
                    let matchDate = true;

                    // Filter Pengerjaan
                    if (selectedStatus !== '') {
                        matchStatus = rawStatus === selectedStatus;
                    }
                    
                    // Filter Pengantaran (BARU)
                    if (selectedDeliveryStatus !== '') {
                        matchDeliveryStatus = rawDeliveryStatus === selectedDeliveryStatus;
                    }

                    // Filter Tanggal
                    if (selectedDate !== '') {
                        matchDate = orderDate === selectedDate;
                    }

                    return matchStatus && matchDeliveryStatus && matchDate;
                });

                // Apply filters
                $('#status-filter, #delivery-status-filter, #date-filter').on('change', function() {
                    table.draw();
                });

                $('#reset-filter').on('click', function() {
                    $('#status-filter').val('');
                    $('#delivery-status-filter').val('');
                    $('#date-filter').val('');
                    table.draw();
                });

                // Status Pengerjaan form submission (LAMA)
                $('#statusForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const url = form.attr('action');

                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: form.serialize(),
                        success: function() {
                            $('#statusModal').modal('hide');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengupdate status pengerjaan.',
                            });
                        }
                    });
                });
                
                // Status Pengantaran form submission (BARU)
                $('#deliveryStatusForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const url = form.attr('action');

                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: form.serialize(),
                        success: function() {
                            $('#deliveryStatusModal').modal('hide');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengupdate status pengantaran.',
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection