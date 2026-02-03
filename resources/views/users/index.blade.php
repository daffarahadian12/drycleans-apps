@extends('partials.app')

@section('title', 'Daftar User')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5>Daftar User</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="feather-plus"></i> Tambah User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Role:</label>
                            <select class="form-select" id="role-filter">
                                <option value="">Semua Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Owner">Owner</option>
                                <option value="Karyawan">Karyawan</option>
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
                        <table class="table table-hover table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedUsers as $user)
                                    <tr>
                                        <td>
                                            {{ $user['name'] }}
                                            @if ($user['is_current_user'])
                                                <span class="badge badge-info ms-1">You</span>
                                            @endif
                                        </td>
                                        <td>{{ $user['email'] }}</td>
                                        <td data-role="{{ ucfirst($user['role']) }}">
                                            @php
                                                $roleColors = [
                                                    'admin' => 'primary',
                                                    'owner' => 'info',
                                                    'karyawan' => 'warning',
                                                ];
                                                $color = $roleColors[$user['role']] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge badge-{{ $color }}">{{ ucfirst($user['role']) }}</span>
                                        </td>
                                        <td>{{ $user['phone'] }}</td>
                                        <td data-status="{{ $user['is_active'] ? 'Aktif' : 'Nonaktif' }}">
                                            @if ($user['is_active'])
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $user['transactions_count'] }}</td>
                                        <td>{{ $user['created_at'] }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user['id']) }}"
                                                    class="btn btn-sm btn-info me-1" title="Detail">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user['id']) }}"
                                                    class="btn btn-sm btn-warning me-1" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                @if (!$user['is_current_user'])
                                                    <form action="{{ route('users.toggle-status', $user['id']) }}"
                                                        method="POST" class="d-inline me-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $user['is_active'] ? 'outline-danger' : 'outline-success' }}"
                                                            title="{{ $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i
                                                                class="feather-{{ $user['is_active'] ? 'toggle-left' : 'toggle-right' }}"></i>
                                                        </button>
                                                    </form>
                                                    @if ($user['transactions_count'] == 0)
                                                        <form action="{{ route('users.destroy', $user['id']) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Hapus">
                                                                <i class="feather-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
        const table = $('#users-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });

        function filterTable() {
            const selectedRole = $('#role-filter').val();
            const selectedStatus = $('#status-filter').val();

            table.rows().every(function () {
                const row = $(this.node());
                const role = row.find('td[data-role]').data('role');
                const status = row.find('td[data-status]').data('status');

                let visible = true;

                if (selectedRole && role !== selectedRole) {
                    visible = false;
                }

                if (selectedStatus && status !== selectedStatus) {
                    visible = false;
                }

                if (visible) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        $('#role-filter, #status-filter').on('change', filterTable);

        $('#reset-filter').on('click', function () {
            $('#role-filter').val('');
            $('#status-filter').val('');
            table.rows().every(function () {
                $(this.node()).show();
            });
        });
    });
</script>
@endpush

@endsection
