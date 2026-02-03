@extends('partials.app')

@section('title', 'Detail Transaksi')

@section('content')
    @php
        // Helper untuk menerjemahkan Status Pengerjaan ke Label dan Warna
        $getStatusData = function ($status) {
            $data = [
                'pending' => ['label' => 'Menunggu', 'color' => 'warning'],
                'process' => ['label' => 'Diproses', 'color' => 'info'],
                'washing' => ['label' => 'Dicuci', 'color' => 'primary'],
                'drying' => ['label' => 'Dikeringkan', 'color' => 'secondary'],
                'ironing' => ['label' => 'Disetrika', 'color' => 'dark'],
                'ready' => ['label' => 'Siap Diambil', 'color' => 'success'],
                'completed' => ['label' => 'Selesai', 'color' => 'success'],
            ];
            return $data[$status] ?? ['label' => ucfirst($status), 'color' => 'secondary'];
        };

        // Helper untuk menerjemahkan Status Pengantaran ke Label dan Warna
        $getDeliveryStatusData = function ($status) {
            $data = [
                'scheduled' => ['label' => 'Barang Diantar/Jemput', 'color' => 'info'],
                'received' => ['label' => 'Barang Diterima', 'color' => 'primary'],
                'packed' => ['label' => 'Selesai Dikemas', 'color' => 'success'],
                'delivered' => ['label' => 'Ambil/Selesai', 'color' => 'dark'],
            ];
            return $data[$status] ?? ['label' => ucfirst($status), 'color' => 'secondary'];
        };

        // Asumsi kolom delivery_status ada di model
        $deliveryStatus = $transaction->delivery_status ?? 'scheduled'; 
        $deliveryData = $getDeliveryStatusData($deliveryStatus);
        $statusData = $getStatusData($transaction->status);
    @endphp

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Detail Transaksi</h5>
                        <p class="card-text mb-0 fw-bold">{{ $transaction->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="mb-0">Pengerjaan: 
                            <span class="badge bg-{{ $statusData['color'] }} fs-6">{{ $statusData['label'] }}</span>
                        </p>
                        <p class="mb-0">Pengantaran: 
                            <span class="badge bg-{{ $deliveryData['color'] }} fs-6">{{ $deliveryData['label'] }}</span>
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">Nama</td>
                                    <td width="5%">:</td>
                                    <td>
                                        {{ $transaction->customer->name }}
                                        @if($transaction->customer->is_member)
                                            <span class="badge bg-success ms-2">Member</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td>:</td>
                                    <td>{{ $transaction->customer->phone ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $transaction->customer->address ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Points</td>
                                    <td>:</td>
                                    <td>{{ $transaction->customer->points }} pts</td>
                                </tr>
                                <tr>
                                    <td>Total Transaksi</td>
                                    <td>:</td>
                                    <td>{{ $transaction->customer->total_transactions }}x</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Paket</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">Paket</td>
                                    <td width="5%">:</td>
                                    <td>{{ $transaction->package->name }}</td>
                                </tr>
                                <tr>
                                    <td>Deskripsi</td>
                                    <td>:</td>
                                    <td>{{ $transaction->package->description ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Harga/kg</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($transaction->price_per_kg, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Estimasi Hari</td>
                                    <td>:</td>
                                    <td>{{ $transaction->package->estimated_days }} hari</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Detail Transaksi</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">Berat</td>
                                    <td width="5%">:</td>
                                    <td>{{ $transaction->weight }} kg</td>
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($transaction->discount_amount > 0)
                                <tr>
                                    <td>Diskon</td>
                                    <td>:</td>
                                    <td class="text-danger">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Waktu</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%">Tanggal Order</td>
                                    <td width="5%">:</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->order_date)->translatedFormat('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Estimasi Selesai</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->estimated_completion)->translatedFormat('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Selesai</td>
                                    <td>:</td>
                                    <td>{{ $transaction->actual_completion ? \Carbon\Carbon::parse($transaction->actual_completion)->translatedFormat('d M Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Dibuat</td>
                                    <td>:</td>
                                    <td>{{ $transaction->created_at->translatedFormat('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->notes)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Catatan</h6>
                            <div class="alert alert-info">
                                {{ $transaction->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('transactions.invoice', $transaction->id) }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-print"></i> Cetak Invoice
                            </a>
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>
                            
                            {{-- Dropdown untuk Update Status --}}
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sync"></i> Update Status
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="updateStatus({{ $transaction->id }}, '{{ $transaction->status }}')">
                                            Update Pengerjaan ({{ $statusData['label'] }})
                                        </a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="updateDeliveryStatus({{ $transaction->id }}, '{{ $deliveryStatus }}')">
                                            Update Pengantaran ({{ $deliveryData['label'] }})
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Timeline Pengerjaan Laundry</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $statuses = [
                                'pending' => 'Menunggu',
                                'process' => 'Diproses',
                                'washing' => 'Dicuci',
                                'drying' => 'Dikeringkan',
                                'ironing' => 'Disetrika',
                                'ready' => 'Siap Diambil',
                                'completed' => 'Selesai'
                            ];
                        @endphp

                        @foreach($statuses as $status => $label)
                            @php
                                $statusIndex = array_search($status, array_keys($statuses));
                                $currentStatusIndex = array_search($transaction->status, array_keys($statuses));
                                $isActive = $statusIndex <= $currentStatusIndex;
                                $isCurrent = $status === $transaction->status;
                                $data = $getStatusData($status);
                            @endphp
                            <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                <div class="timeline-marker bg-{{ $isActive ? $data['color'] : 'light' }}" style="box-shadow: 0 0 0 2px {{ $isActive ? 'var(--bs-'.$data['color'].')' : '#dee2e6' }}">
                                    @if($isCurrent)
                                        <i class="fas fa-circle"></i>
                                    @elseif($isActive)
                                        <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-circle-o"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 {{ $isActive ? 'text-dark' : 'text-muted' }}">{{ $label }}</h6>
                                    @if($isCurrent)
                                        <small class="text-primary">Status saat ini</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Timeline Pengantaran/Pengambilan (BARU) --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Timeline Pengantaran/Pengambilan</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $deliveryStatuses = [
                                'scheduled' => 'Barang Dijemput/Diantar',
                                'received' => 'Barang Diterima Laundry',
                                'packed' => 'Selesai Dikemas',
                                'delivered' => 'Barang Sudah Diambil/Dikirim',
                            ];
                        @endphp

                        @foreach($deliveryStatuses as $status => $label)
                            @php
                                $statusIndex = array_search($status, array_keys($deliveryStatuses));
                                $currentDeliveryStatusIndex = array_search($deliveryStatus, array_keys($deliveryStatuses));
                                $isActive = $statusIndex <= $currentDeliveryStatusIndex;
                                $isCurrent = $status === $deliveryStatus;
                                $data = $getDeliveryStatusData($status);
                            @endphp
                            <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                <div class="timeline-marker bg-{{ $isActive ? $data['color'] : 'light' }}" style="box-shadow: 0 0 0 2px {{ $isActive ? 'var(--bs-'.$data['color'].')' : '#dee2e6' }}">
                                    @if($isCurrent)
                                        <i class="fas fa-circle"></i>
                                    @elseif($isActive)
                                        <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-circle-o"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 {{ $isActive ? 'text-dark' : 'text-muted' }}">{{ $label }}</h6>
                                    @if($isCurrent)
                                        <small class="text-primary">Status saat ini</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
                                <option value="pending">Menunggu</option>
                                <option value="process">Diproses</option>
                                <option value="washing">Dicuci</option>
                                <option value="drying">Dikeringkan</option>
                                <option value="ironing">Disetrika</option>
                                <option value="ready">Siap Diambil</option>
                                <option value="completed">Selesai</option>
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

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -15px; /* Sesuaikan posisi marker */
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-item.active .timeline-marker {
            box-shadow: 0 0 0 2px var(--bs-primary);
        }
        
        /* Tambahkan style untuk warna latar belakang marker jika status aktif */
        /* Perlu diperhatikan bahwa warna bg-class harus didefinisikan di CSS */
        .bg-warning { background-color: #ffc107 !important; }
        .bg-info { background-color: #0dcaf0 !important; }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-secondary { background-color: #6c757d !important; }
        .bg-dark { background-color: #212529 !important; }
        .bg-success { background-color: #198754 !important; }

        .timeline-item.current .timeline-marker {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 2px currentColor, 0 0 0 4px rgba(var(--bs-primary-rgb), 0.3);
            }
            50% {
                box-shadow: 0 0 0 2px currentColor, 0 0 0 8px rgba(var(--bs-primary-rgb), 0.1);
            }
            100% {
                box-shadow: 0 0 0 2px currentColor, 0 0 0 4px rgba(var(--bs-primary-rgb), 0.3);
            }
        }

        .timeline-content {
            padding-left: 5px; /* Sesuaikan padding */
        }
    </style>

    @push('scripts')
    <script>
        // FUNGSI UPDATE STATUS PENGERJAAN
        function updateStatus(transactionId, currentStatus) {
            const form = document.getElementById('statusForm');
            const statusSelect = document.getElementById('status');

            form.action = "{{ url('transactions') }}/" + transactionId + "/status";
            statusSelect.value = currentStatus;
            
            // Set warna marker modal sesuai status saat ini
            const statusData = {
                'pending': 'warning', 'process': 'info', 'washing': 'primary', 'drying': 'secondary',
                'ironing': 'dark', 'ready': 'success', 'completed': 'success'
            };
            const currentStatusColor = statusData[currentStatus] || 'secondary';
            // Hanya berlaku jika Anda memiliki CSS yang dapat menangani dinamis style ini
            // Jika tidak, abaikan perubahan style di modal ini.

            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }
        
        // FUNGSI BARU UPDATE STATUS PENGANTARAN
        function updateDeliveryStatus(transactionId, currentDeliveryStatus) {
            const form = document.getElementById('deliveryStatusForm');
            const statusSelect = document.getElementById('delivery_status');
            
            form.action = "{{ url('transactions') }}/" + transactionId + "/delivery-status";
            statusSelect.value = currentDeliveryStatus;

            const modal = new bootstrap.Modal(document.getElementById('deliveryStatusModal'));
            modal.show();
        }
    </script>

    @if(session('success'))
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
    @endpush
@endsection