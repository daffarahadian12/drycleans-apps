@extends('partials.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Transaksi Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                        @csrf
                        <div class="row">
                            {{-- KOLOM PELANGGAN --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Pelanggan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_id') is-invalid @enderror"
                                        name="customer_id" id="customer_id" required>
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                            
                            {{-- KOLOM PAKET LAUNDRY --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_id" class="form-label">Paket Laundry <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('package_id') is-invalid @enderror" name="package_id"
                                        id="package_id" required>
                                        <option value="">Pilih Paket</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" data-price="{{ $package->price_per_kg }}"
                                                data-days="{{ $package->estimated_days }}"
                                                data-description="{{ $package->description }}"
                                                {{ old('package_id') == $package->id ? 'selected' : '' }}>
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
                            {{-- KOLOM BERAT --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Berat (Kg) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.1" min="0.1"
                                        class="form-control @error('weight') is-invalid @enderror" name="weight"
                                        id="weight" value="{{ old('weight') }}" required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- INPUT BARU: TIPE PENGANTARAN/PENGAMBILAN --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_type" class="form-label">Tipe Pengantaran Awal <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                        name="delivery_type" id="delivery_type" required>
                                        {{-- Sesuai dengan ENUM 'scheduled' dan 'received' di controller --}}
                                        <option value="scheduled" {{ old('delivery_type') == 'scheduled' ? 'selected' : '' }}>
                                            Barang Diantar/Jemput (Scheduled)
                                        </option>
                                        <option value="received" {{ old('delivery_type') == 'received' ? 'selected' : '' }}>
                                            Barang Diterima Langsung (Walk-in)
                                        </option>
                                    </select>
                                    @error('delivery_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            {{-- DISKON INFO --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Diskon Tersedia</label>
                                    <div id="discountInfo" class="form-control-plaintext">
                                        <small class="text-muted">Masukkan berat untuk melihat diskon yang tersedia</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3"
                                placeholder="Catatan tambahan untuk transaksi ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-save"></i> Simpan Transaksi
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
                    <div id="customerInfo">
                        <p class="text-muted mb-0">Pilih pelanggan untuk melihat info</p>
                    </div>
                </div>
            </div>

            <div class="card mb-3" id="packageInfoCard">
                <div class="card-header">
                    <h6 class="card-title mb-0">Info Paket</h6>
                </div>
                <div class="card-body">
                    <div id="packageInfo">
                        <p class="text-muted mb-0">Pilih paket untuk melihat info</p>
                    </div>
                </div>
            </div>

            <div class="card" id="previewCard">
                <div class="card-header">
                    <h6 class="card-title mb-0">Preview Transaksi</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">Berat:</div>
                        <div class="col-6 text-end" id="previewWeight">0 kg</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Harga/kg:</div>
                        <div class="col-6 text-end" id="previewPricePerKg">Rp 0</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Subtotal:</div>
                        <div class="col-6 text-end" id="previewSubtotal">Rp 0</div>
                    </div>
                    <div class="row mb-2" id="discountRow">
                        <div class="col-6">Diskon:</div>
                        <div class="col-6 text-end text-danger" id="previewDiscount">- Rp 0</div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Total:</strong></div>
                        <div class="col-6 text-end"><strong id="previewTotal">Rp 0</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Estimasi Selesai:</div>
                        <div class="col-6 text-end" id="previewEstimation">-</div>
                    </div>
                    <div class="row">
                        <div class="col-6">Invoice:</div>
                        <div class="col-6 text-end" id="previewInvoice">Auto Generate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                infoDiv.innerHTML = '<p class="text-muted mb-0">Pilih pelanggan untuk melihat info</p>';
            }
            
            calculateTotal();
        }

        function updatePackageInfo() {
            const select = document.getElementById('package_id');
            const selectedValue = select.value;
            const infoDiv = document.getElementById('packageInfo');

            if (selectedValue) {
                const pkg = packages.find(p => p.id == selectedValue); // Ubah 'package' menjadi 'pkg' untuk menghindari konflik
                if (pkg) {
                    infoDiv.innerHTML = `
                        <p class="mb-1"><strong>Paket:</strong> ${pkg.name}</p>
                        <p class="mb-1"><strong>Harga:</strong> Rp ${parseInt(pkg.price_per_kg).toLocaleString('id-ID')}/kg</p>
                        <p class="mb-1"><strong>Estimasi:</strong> ${pkg.estimated_days} hari</p>
                        <p class="mb-0"><strong>Deskripsi:</strong> ${pkg.description || '-'}</p>
                    `;
                }
            } else {
                infoDiv.innerHTML = '<p class="text-muted mb-0">Pilih paket untuk melihat info</p>';
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

            // Reset preview to default values
            resetPreviewToDefault();

            // Reset discount info
            discountInfo.innerHTML = '<small class="text-muted">Masukkan berat untuk melihat diskon yang tersedia</small>';
            currentDiscount = null;

            // Check if form is complete for enabling submit button
            if (!selectedPackage || weight <= 0 || !customerSelect.value) {
                submitBtn.disabled = true;
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
                    
                    document.getElementById('previewDiscount').textContent = `- Rp ${discountAmount.toLocaleString('id-ID')}`;
                } else {
                    discountInfo.innerHTML = '<small class="text-muted">Tidak ada diskon tersedia untuk berat ini</small>';
                    document.getElementById('previewDiscount').textContent = '- Rp 0';
                }

                const total = subtotal - discountAmount;
                document.getElementById('previewTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;

            } catch (error) {
                console.error('Error fetching discount:', error);
                discountInfo.innerHTML = '<small class="text-danger">Error memuat informasi diskon</small>';
                document.getElementById('previewDiscount').textContent = '- Rp 0';
                
                // Calculate total without discount
                document.getElementById('previewTotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            }

            isCalculating = false;
        }

        function resetPreviewToDefault() {
            document.getElementById('previewWeight').textContent = '0 kg';
            document.getElementById('previewPricePerKg').textContent = 'Rp 0';
            document.getElementById('previewSubtotal').textContent = 'Rp 0';
            document.getElementById('previewDiscount').textContent = '- Rp 0';
            document.getElementById('previewTotal').textContent = 'Rp 0';
            document.getElementById('previewEstimation').textContent = '-';
        }

        // Form validation
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            const weight = parseFloat(document.getElementById('weight').value) || 0;
            const customerId = document.getElementById('customer_id').value;
            const packageId = document.getElementById('package_id').value;
            const deliveryType = document.getElementById('delivery_type').value;

            if (!customerId || !packageId || weight <= 0 || !deliveryType) {
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize preview with default values
            resetPreviewToDefault();
            
            // Trigger updates if there are old values
            // We ensure that calculateTotal runs if any necessary field has old data
            if (document.getElementById('customer_id').value) {
                updateCustomerInfo();
            } else {
                updateCustomerInfo();
            }
            
            if (document.getElementById('package_id').value) {
                updatePackageInfo();
            } else {
                updatePackageInfo();
            }
            
            if (document.getElementById('weight').value) {
                calculateTotal();
            }
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
@endsection