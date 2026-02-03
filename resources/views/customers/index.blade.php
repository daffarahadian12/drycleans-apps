@extends('partials.app')

@section('title', 'List Pelanggan')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Daftar Pelanggan</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <i class="feather-plus"></i> Tambah Pelanggan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Status Member:</label>
                            <select class="form-select" id="member-filter">
                                <option value="">Semua Status</option>
                                <option value="1">Member</option>
                                <option value="0">Non-Member</option>
                            </select>

                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Minimal Transaksi:</label>
                            <select class="form-select" id="transaction-filter">
                                <option value="">Semua</option>
                                <option value="1-5">1-5 Transaksi</option>
                                <option value="6-10">6-10 Transaksi</option>
                                <option value="10+">10+ Transaksi</option>
                            </select>

                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-secondary d-block" id="reset-filter">
                                <i class="feather-refresh-cw"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="customers-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Points</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Belanja</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer['name'] }}</td>
                                        <td>{{ $customer['phone'] }}</td>
                                        <td>{{ $customer['address'] }}</td>
                                        <td data-is-member="{{ $customer['is_member'] ? 1 : 0 }}">
                                            @if ($customer['is_member'])
                                                <span class="badge badge-success">Member</span>
                                                <br><small class="text-muted">Sejak: {{ $customer['member_since'] }}</small>
                                            @else
                                                <span class="badge badge-danger">Non-Member</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge badge-info">{{ $customer['points'] }} pts</span>
                                        </td>
                                        <td>{{ $customer['total_transactions'] }}x</td>
                                        <td>Rp {{ number_format($customer['total_spent'], 0, ',', '.') }}</td>
                                        <td>{{ $customer['created_at'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customers.show', $customer['id']) }}"
                                                    class="btn btn-sm btn-info me-1" title="Detail">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer['id']) }}"
                                                    class="btn btn-sm btn-warning me-1" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('customers.toggle-member', $customer['id']) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $customer['is_member'] ? 'btn-danger' : 'btn-success' }}"
                                                        title="{{ $customer['is_member'] ? 'Hapus Member' : 'Jadikan Member' }}">
                                                        <i
                                                            class="feather-{{ $customer['is_member'] ? 'user-minus' : 'user-plus' }}"></i>
                                                    </button>
                                                </form>
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#customers-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [7, 'desc']
                    ]
                });

                // Custom filter function
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const memberFilter = $('#member-filter').val(); // '1', '0', or ''
                    const transactionFilter = $('#transaction-filter').val(); // '1-5', '6-10', '10+', or ''

                    // Ambil node baris
                    const row = table.row(dataIndex).node();

                    const isMember = $(row).find('td[data-is-member]').data('is-member'); // 1 atau 0
                    const totalTransactions = parseInt(data[5].replace('x', '')) || 0;

                    let matchMember = true;
                    let matchTransaction = true;

                    // Filter member
                    if (memberFilter !== '') {
                        matchMember = parseInt(memberFilter) === isMember;
                    }

                    // Filter transaksi
                    if (transactionFilter === '1-5') {
                        matchTransaction = totalTransactions >= 1 && totalTransactions <= 5;
                    } else if (transactionFilter === '6-10') {
                        matchTransaction = totalTransactions >= 6 && totalTransactions <= 10;
                    } else if (transactionFilter === '10+') {
                        matchTransaction = totalTransactions > 10;
                    }

                    return matchMember && matchTransaction;
                });

                // Trigger filter saat filter berubah
                $('#member-filter, #transaction-filter').on('change', function() {
                    table.draw();
                });

                // Reset
                $('#reset-filter').on('click', function() {
                    $('#member-filter').val('');
                    $('#transaction-filter').val('');
                    table.draw();
                });
            });
        </script>
    @endpush


@endsection
