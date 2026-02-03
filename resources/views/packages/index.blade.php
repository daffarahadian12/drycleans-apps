@extends('partials.app')

@section('title', 'Daftar Paket Laundry')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Daftar Paket Laundry</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('packages.create') }}" class="btn btn-primary">
                                <i class="feather-plus"></i> Tambah Paket
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga:</label>
                            <select class="form-select" id="price-filter">
                                <option value="">Semua Harga</option>
                                <option value="< Rp 10.000">
                                    < Rp 10.000</option>
                                <option value="Rp 10.000 - 20.000">Rp 10.000 - 20.000</option>
                                <option value="Rp 20.000 - 50.000">Rp 20.000 - 50.000</option>
                                <option value="> Rp 50.000">> Rp 50.000</option>
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
                        <table class="table table-hover table-striped" id="packages-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Paket</th>
                                    <th>Deskripsi</th>
                                    <th>Harga/Kg</th>
                                    <th>Estimasi</th>
                                    <th>Status</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedPackages as $index => $package)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $package['name'] }}</td>
                                        <td>{{ $package['description'] }}</td>
                                        <td data-price="{{ $package['price_per_kg'] }}">{{ $package['price_formatted'] }}
                                        <td>{{ $package['estimated_days'] }}</td>
                                        <td data-status="{{ $package['is_active'] ? 'Aktif' : 'Nonaktif' }}">
                                            @if ($package['is_active'])
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Nonaktif</span>
                                            @endif
                                        </td>

                                        </td>

                                        <td>{{ $package['transactions_count'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('packages.show', $package['id']) }}"
                                                    class="btn btn-sm btn-info me-1" title="Detail"><i
                                                        class="feather-eye"></i></a>
                                                <a href="{{ route('packages.edit', $package['id']) }}"
                                                    class="btn btn-sm btn-warning me-1" title="Edit"><i
                                                        class="feather-edit"></i></a>
                                                <form action="{{ route('packages.toggle-status', $package['id']) }}"
                                                    method="POST" class="d-inline me-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-{{ $package['is_active'] ? 'outline-danger' : 'outline-success' }}"
                                                        title="{{ $package['is_active'] ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i
                                                            class="feather-{{ $package['is_active'] ? 'toggle-left' : 'toggle-right' }}"></i>
                                                    </button>
                                                </form>
                                                @if ($package['transactions_count'] == 0)
                                                    <form action="{{ route('packages.destroy', $package['id']) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="feather-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
    $(document).ready(function () {
        const table = $('#packages-table').DataTable({
            responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [6, 'desc']
                    ] // order_date column
        });

        function filterTable() {
            const status = $('#status-filter').val();
            const price = $('#price-filter').val();

            table.rows().every(function () {
                const row = $(this.node());

                const rowStatus = row.find('td[data-status]').data('status'); // "Aktif" / "Nonaktif"
                const rowPrice = parseFloat(row.find('td[data-price]').data('price')); // Angka

                let match = true;

                // Filter status
                if (status && rowStatus !== status) {
                    match = false;
                }

                // Filter price
                if (price) {
                    if (price === '< Rp 10.000' && !(rowPrice < 10000)) match = false;
                    else if (price === 'Rp 10.000 - 20.000' && !(rowPrice >= 10000 && rowPrice <= 20000)) match = false;
                    else if (price === 'Rp 20.000 - 50.000' && !(rowPrice > 20000 && rowPrice <= 50000)) match = false;
                    else if (price === '> Rp 50.000' && !(rowPrice > 50000)) match = false;
                }

                if (match) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        $('#status-filter, #price-filter').on('change', filterTable);

        $('#reset-filter').on('click', function () {
            $('#status-filter').val('');
            $('#price-filter').val('');
            table.rows().every(function () {
                $(this.node()).show();
            });
        });
    });
</script>
@endpush

@endsection
