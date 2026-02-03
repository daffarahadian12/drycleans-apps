@extends('partials.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Transaksi - {{ $transaction->invoice_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" id="transactionForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- STATUS PADA SAAT EDIT (Display Only) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Pengerjaan</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </p>
                                {{-- Input hidden status, tidak diubah di sini --}}
                                <input type="hidden" name="status" value="{{ $transaction->status }}">
                            </div>
                            <div class="col-md-6">
                                <label for="delivery_status_edit" class="form-label">Status Pengantaran/Pengambilan</label>
                                <select class="form-select @error('delivery_status') is-invalid @enderror"
                                    name="delivery_status" id="delivery_status_edit">
                                    {{-- Menggunakan nilai dari DB: $transaction->delivery_status --}}
                                    <option value="scheduled" {{ old('delivery_status', $transaction->delivery_status) == 'scheduled' ? 'selected' : '' }}>
                                        Barang Diantar/Jemput
                                    </option>
                                    <option value="received" {{ old('delivery_status', $transaction->delivery_status) == 'received' ? 'selected' : '' }}>
                                        Barang Diterima
                                    </option>
                                    <option value="packed" {{ old('delivery_status', $transaction->delivery_status) == 'packed' ? 'selected' : '' }}>
                                        Selesai Dikemas
                                    </option>
                                    <option value="delivered" {{ old('delivery_status', $transaction->delivery_status) == 'delivered' ? 'selected' : '' }}>
                                        Ambil/Selesai
                                    </option>
                                </select>
                                @error('delivery_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Pelanggan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror"
                                        name="customer_id" id="customer_id" required>
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ (old('customer_id', $transaction->customer_id) == $customer->id) ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                                @if ($customer->is_member)
                                                    (Member - {{ $customer->points }} pts)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_id" class="form-label">Paket Laundry <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('package_id') is-invalid @enderror" name="package_id"
                                        id="package_id" required>
                                        <option value="">Pilih Paket</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" 
                                                data-price="{{ $package->price_per_kg }}"
                                                data-days="{{ $package->estimated_days }}"
                                                data-description="{{ $package->description }}"
                                                {{ (old('package_id', $transaction->package_id) == $package->id) ? 'selected' : '' }}>
                                                {{ $package->name }} - Rp
                                                {{ number_format($package->price_per_kg, 0, ',', '.') }}/kg
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Berat (Kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.1" min="0.1"
                                        class="form-control @error('weight') is-invalid @enderror" name="weight"
                                        id="weight" value="{{ old('weight', $transaction->weight) }}" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Diskon Tersedia</label>
                                    <div id="discountInfo" class="form-control-plaintext">
                                        <small class="text-muted">Loading...</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3"
                                placeholder="Catatan tambahan untuk transaksi ini...">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Update Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3" id="customerInfoCard">
                <div class="card-header">
                    <h6 class="card-title mb-0">Info Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div id="customerInfo"></div>
                </div>
            </div>

            <div class="card mb-3" id="packageInfoCard">
                <div class="card-header">
                    <h6 class="card-title mb-0">Info Paket</h6>
                </div>
                <div class="card-body">
                    <div id="packageInfo"></div>
                </div>
            </div>

            <div class="card" id="previewCard">
                <div class="card-header">
                    <h6 class="card-title mb-0">Preview Transaksi</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">Invoice:</div>
                        <div class="col-6 text-end"><strong>{{ $transaction->invoice_number }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Berat:</div>
                        <div class="col-6 text-end" id="previewWeight">{{ $transaction->weight }} kg</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Harga/kg:</div>
                        <div class="col-6 text-end" id="previewPricePerKg">Rp {{ number_format($transaction->price_per_kg, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Subtotal:</div>
                        <div class="col-6 text-end" id="previewSubtotal">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-2" id="discountRow" style="{{ $transaction->discount_amount > 0 ? '' : 'display: none;' }}">
                        <div class="col-6">Diskon:</div>
                        <div class="col-6 text-end text-danger" id="previewDiscount">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Total:</strong></div>
                        <div class="col-6 text-end"><strong id="previewTotal">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Estimasi Selesai:</div>
                        <div class="col-6 text-end" id="previewEstimation">
                            {{-- Asumsi Anda memiliki accessor 'formatted_estimated_completion' di model Transaction --}}
                            {{ \Carbon\Carbon::parse($transaction->estimated_completion)->translatedFormat('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let packages = @json($packages);
        let customers = @json($customers);
        let currentDiscount = null;
        let isCalculating = false;

        // Event listeners
        document.getElementById('customer_id').addEventListener('change', updateCustomerInfo);
        document.getElementById('package_id').addEventListener('change', updatePackageInfo);
        document.getElementById('weight').addEventListener('input', debounce(calculateTotal, 500));
        document.getElementById('weight').addEventListener('change', calculateTotal);
        
        // Listener untuk delivery_status agar preview tetap update (optional, tapi baik untuk info)
        document.getElementById('delivery_status_edit').addEventListener('change', calculateTotal);


        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function updateCustomerInfo() {
            const select = document.getElementById('customer_id');
            const selectedValue = select.value;
            const infoDiv = document.getElementById('customerInfo');

            if (selectedValue) {
                const customer = customers.find(c => c.id == selectedValue);
                if (customer) {
                    infoDiv.innerHTML = `
                        <p class="mb-1"><strong>Nama:</strong> ${customer.name}</p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span class="badge bg-${customer.is_member ? 'success' : 'secondary'}">
                                ${customer.is_member ? 'Member' : 'Non-Member'}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Points:</strong> ${customer.points || 0}</p>
                        <p class="mb-1"><strong>Telepon:</strong> ${customer.phone || '-'}</p>
                        <p class="mb-0"><strong>Alamat:</strong> ${customer.address || '-'}</p>
                    `;
                }
            } else {
                infoDiv.innerHTML = '<p class="text-muted">Pilih pelanggan untuk melihat info</p>';
            }
            
            calculateTotal();
        }

        function updatePackageInfo() {
            const select = document.getElementById('package_id');
            const selectedValue = select.value;
            const infoDiv = document.getElementById('packageInfo');

            if (selectedValue) {
                const pkg = packages.find(p => p.id == selectedValue); // Mengubah 'package' menjadi 'pkg'
                if (pkg) {
                    infoDiv.innerHTML = `
                        <p class="mb-1"><strong>Paket:</strong> ${pkg.name}</p>
                        <p class="mb-1"><strong>Harga:</strong> Rp ${parseInt(pkg.price_per_kg).toLocaleString('id-ID')}/kg</p>
                        <p class="mb-1"><strong>Estimasi:</strong> ${pkg.estimated_days} hari</p>
                        <p class="mb-0"><strong>Deskripsi:</strong> ${pkg.description || '-'}</p>
                    `;
                }
            } else {
                infoDiv.innerHTML = '<p class="text-muted">Pilih paket untuk melihat info</p>';
            }
            
            calculateTotal();
        }

        async function calculateTotal() {
            if (isCalculating) return;
            isCalculating = true;

            const weight = parseFloat(document.getElementById('weight').value) || 0;
            const packageSelect = document.getElementById('package_id');
            const customerSelect = document.getElementById('customer_id');
            const selectedPackage = packages.find(p => p.id == packageSelect.value);
            
            const discountInfo = document.getElementById('discountInfo');
            const submitBtn = document.getElementById('submitBtn');
            const discountRow = document.getElementById('discountRow');

            if (!selectedPackage || weight <= 0 || !customerSelect.value) {
                // Jangan reset preview sepenuhnya di halaman edit, cukup update total/diskon
                discountInfo.innerHTML = '<small class="text-muted">Lengkapi data untuk kalkulasi</small>';
                submitBtn.disabled = false; // Tetap aktif agar bisa update catatan dll
                isCalculating = false;
                return;
            }

            submitBtn.disabled = false;

            const pricePerKg = parseFloat(selectedPackage.price_per_kg);
            const estimatedDays = parseInt(selectedPackage.estimated_days);
            const subtotal = weight * pricePerKg;

            // Update basic preview
            document.getElementById('previewWeight').textContent = `${weight} kg`;
            document.getElementById('previewPricePerKg').textContent = `Rp ${pricePerKg.toLocaleString('id-ID')}`;
            document.getElementById('previewSubtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

            // Calculate estimated completion
            const estimatedDate = new Date();
            estimatedDate.setDate(estimatedDate.getDate() + estimatedDays);
            document.getElementById('previewEstimation').textContent = estimatedDate.toLocaleDateString('id-ID');

            try {
                // Fetch discount information
                const response = await fetch(`/api/discount-preview?weight=${weight}&customer_id=${customerSelect.value}`);
                const data = await response.json();

                let discountAmount = 0;

                if (data.discount) {
                    currentDiscount = data.discount;
                    discountAmount = subtotal * (data.discount.discount_percentage / 100);
                    
                    discountInfo.innerHTML = `
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <strong>${data.discount.name}</strong><br>
                                Diskon ${data.discount.discount_percentage}% untuk berat ≥ ${data.discount.min_weight}kg
                                ${data.discount.is_member_only ? ' (Khusus Member)' : ''}
                            </small>
                        </div>
                    `;
                    
                    document.getElementById('previewDiscount').textContent = `- Rp ${Math.round(discountAmount).toLocaleString('id-ID')}`;
                    discountRow.style.display = 'flex';
                } else {
                    discountInfo.innerHTML = '<small class="text-muted">Tidak ada diskon tersedia untuk berat ini</small>';
                    document.getElementById('previewDiscount').textContent = '- Rp 0';
                    discountRow.style.display = 'none';
                }

                const total = subtotal - discountAmount;
                document.getElementById('previewTotal').textContent = `Rp ${Math.round(total).toLocaleString('id-ID')}`;

            } catch (error) {
                console.error('Error fetching discount:', error);
                discountInfo.innerHTML = '<small class="text-danger">Error memuat informasi diskon</small>';
                document.getElementById('previewTotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
                document.getElementById('previewDiscount').textContent = '- Rp 0';
                discountRow.style.display = 'none';
            }

            isCalculating = false;
        }

        // Form validation
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            const weight = parseFloat(document.getElementById('weight').value) || 0;
            const customerId = document.getElementById('customer_id').value;
            const packageId = document.getElementById('package_id').value;

            if (!customerId || !packageId || weight <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Form Tidak Lengkap',
                    text: 'Mohon lengkapi semua field yang wajib diisi',
                });
                return false;
            }

            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupdate...';
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Karena data sudah ada saat load, kita hanya perlu memicu update info
            updateCustomerInfo();
            updatePackageInfo();
            calculateTotal();
        });
    </script>

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach',
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    @endpush
@endsection