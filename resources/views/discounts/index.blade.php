@extends('partials.app')

@section('title', 'Daftar Diskon')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Daftar Diskon</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('discounts.create') }}" class="btn btn-primary">
                                <i class="feather-plus"></i> Tambah Diskon
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Member Only:</label>
                            <select class="form-select" id="member-filter">
                                <option value="">Semua</option>
                                <option value="Ya">Member Only</option>
                                <option value="Tidak">Semua Customer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
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
                        <table class="table table-hover table-striped" id="discounts-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Diskon</th>
                                    <th>Min. Berat (Kg)</th>
                                    <th>Diskon (%)</th>
                                    <th>Member Only</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedDiscounts as $index => $discount)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $discount['name'] }}</td>
                                        <td>{{ $discount['min_weight'] }} kg</td>
                                        <td>{{ $discount['discount_percentage'] }}%</td>
                                        <td>
                                            <span
                                                class="badge {{ $discount['is_member_only'] ? 'badge-info' : 'badge-warning' }}"
                                                data-search="{{ $discount['is_member_only'] ? 'Ya' : 'Tidak' }}">
                                                {{ $discount['is_member_only'] ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $discount['is_active'] ? 'badge-success' : 'badge-danger' }}"
                                                data-search="{{ $discount['is_active'] ? 'Aktif' : 'Nonaktif' }}">
                                                {{ $discount['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('discounts.show', $discount['id']) }}"
                                                    class="btn btn-sm btn-info me-1" title="Detail"><i
                                                        class="feather-eye"></i></a>
                                                <a href="{{ route('discounts.edit', $discount['id']) }}"
                                                    class="btn btn-sm btn-warning me-1" title="Edit"><i
                                                        class="feather-edit"></i></a>
                                                <form action="{{ route('discounts.toggle-status', $discount['id']) }}"
                                                    method="POST" class="d-inline me-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-{{ $discount['is_active'] ? 'outline-danger' : 'outline-success' }}"
                                                        title="{{ $discount['is_active'] ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i
                                                            class="feather-{{ $discount['is_active'] ? 'toggle-left' : 'toggle-right' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('discounts.destroy', $discount['id']) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus diskon ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="feather-trash"></i>
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
            $(document).ready(function() {
                let table = $('#discounts-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [1, 'asc']
                    ]
                });

                // Filter handlers - langsung seperti transactions
                $('#member-filter').on('change', function() {
                    const value = $(this).val();
                    table.column(4).search(value).draw();
                });

                $('#status-filter').on('change', function() {
                    const value = $(this).val();
                    table.column(5).search(value).draw();
                });


                // Reset filter
                $('#reset-filter').on('click', function() {
                    $('#member-filter').val('');
                    $('#status-filter').val('');
                    table.search('').columns().search('').draw();
                });
            });
        </script>
    @endpush
@endsection
