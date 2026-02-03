<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Laundry - DryClean</title>
    
    <link rel="shortcut icon" href="{{ asset('adm/assets/img/favicon.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/all.min.css') }}">
    
    <style>
        /* ... (CSS yang sudah ada dipertahankan) ... */
        body {
            background: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #58aeff 0%, #2617fa 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .header-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .header-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .search-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin: -50px auto 50px;
            max-width: 800px;
            padding: 40px;
            position: relative;
            z-index: 10;
        }
        
        .nav-tabs {
            border: none;
            margin-bottom: 30px;
        }
        
        .nav-tabs .nav-link {
            border: 2px solid #e8ecef;
            border-radius: 10px;
            color: #6c757d;
            font-weight: 500;
            margin-right: 10px;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #58aeff 0%, #2617fa 100%);
            border-color: #667eea;
            color: white;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-control {
            border: 2px solid #e8ecef;
            border-radius: 10px;
            font-size: 16px;
            height: 50px;
            padding: 15px 50px 5px 20px;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -8px;
            left: 15px;
            font-size: 12px;
            color: #667eea;
            background: white;
            padding: 0 5px;
        }
        
        .form-label {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 16px;
            color: #6c757d;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #58aeff 0%, #2617fa 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            height: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .result-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 30px;
        }
        
        /* TIMELINE STYLES */
        .status-timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 15px;
        }
        
        .status-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e8ecef;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e8ecef;
            border: 2px solid white;
            z-index: 5;
        }
        
        .timeline-item.active::before {
            background: #28a745; /* Success */
        }
        
        .timeline-item.current::before {
            background: #667eea;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }
        
        .status-badge {
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 15px;
            text-transform: uppercase;
            margin-right: 5px;
        }
        
        /* Badge Colors */
        .status-pending, .status-scheduled { background: #fff3cd; color: #856404; }
        .status-received, .status-process { background: #cce5ff; color: #004085; }
        .status-washing { background: #d4edda; color: #155724; }
        .status-drying { background: #f8d7da; color: #721c24; }
        .status-ironing { background: #e2e3e5; color: #383d41; }
        .status-ready, .status-packed { background: #d1ecf1; color: #0c5460; }
        .status-completed, .status-delivered { background: #d4edda; color: #155724; }
        
        .contact-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            margin-bottom: 5%;
        }
        
        .admin-login {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn-admin {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .alert-danger {
            background-color: #fee;
            color: #c33;
        }
        
        .alert-info {
            background-color: #e7f3ff;
            color: #0c5460;
        }
    </style>
</head>

<body>
    <div class="admin-login">
        <a href="{{ route('login') }}" class="btn-admin">
            <i class="fas fa-user-shield me-2"></i>Admin Login
        </a>
    </div>

    <div class="header-section">
        <div class="container">
            <h1>🧺 DryClean</h1>
            <p>Track your laundry status in real-time</p>
        </div>
    </div>

    <div class="container">
        <div class="search-section">
            <h3 class="text-center mb-4">Track Your Order</h3>
            
            <ul class="nav nav-tabs justify-content-center" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice" type="button" role="tab">
                        <i class="fas fa-receipt me-2"></i>Invoice Number
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="name-tab" data-bs-toggle="tab" data-bs-target="#name" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>Customer Name
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone" type="button" role="tab">
                        <i class="fas fa-phone me-2"></i>Phone Number
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="searchTabContent">
                <div class="tab-pane fade show active" id="invoice" role="tabpanel">
                    <form method="GET" action="{{ url('/') }}">
                        <input type="hidden" name="search_type" value="invoice">
                        <div class="form-group">
                            <input type="text"
                                    class="form-control"
                                    name="search_value"
                                    value="{{ request('search_value') }}"
                                    placeholder=" "
                                    required>
                            <label class="form-label">Invoice Number</label>
                            <span class="search-icon"><i class="fas fa-receipt"></i></span>
                        </div>
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Track Order
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="name" role="tabpanel">
                    <form method="GET" action="{{ url('/') }}">
                        <input type="hidden" name="search_type" value="name">
                        <div class="form-group">
                            <input type="text"
                                    class="form-control"
                                    name="search_value"
                                    value="{{ request('search_value') }}"
                                    placeholder=" "
                                    required>
                            <label class="form-label">Customer Name</label>
                            <span class="search-icon"><i class="fas fa-user"></i></span>
                        </div>
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Track Order
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="phone" role="tabpanel">
                    <form method="GET" action="{{ url('/') }}">
                        <input type="hidden" name="search_type" value="phone">
                        <div class="form-group">
                            <input type="text"
                                    class="form-control"
                                    name="search_value"
                                    value="{{ request('search_value') }}"
                                    placeholder=" "
                                    required>
                            <label class="form-label">Phone Number</label>
                            <span class="search-icon"><i class="fas fa-phone"></i></span>
                        </div>
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Track Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(request()->has('search_value'))
            @if(isset($transactions) && $transactions->count() > 0)
                @foreach($transactions as $transaction)
                @php
                    // Helper functions untuk tampilan (diambil dari controller/blade sebelumnya)
                    $getDeliveryStatusText = function ($status) {
                        switch ($status) {
                            case 'scheduled': return 'Barang Diantar/Jemput';
                            case 'received': return 'Barang Diterima';
                            case 'packed': return 'Selesai Dikemas';
                            case 'delivered': return 'Ambil/Selesai';
                            default: return ucfirst($status);
                        }
                    };

                    $deliveryStatus = $transaction->delivery_status ?? 'scheduled';
                @endphp
                
                <div class="result-section">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Invoice: {{ $transaction->invoice_number }}
                            </h4>
                            <div class="mb-3">
                                <strong>Customer:</strong> {{ $transaction->customer->name }}<br>
                                <strong>Phone:</strong> {{ $transaction->customer->phone }}<br>
                                <strong>Package:</strong> {{ $transaction->package->name }}<br>
                                <strong>Weight:</strong> {{ $transaction->weight }} kg<br>
                                <strong>Order Date:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}<br>
                                <strong>Estimated Completion:</strong> {{ \Carbon\Carbon::parse($transaction->estimated_completion)->format('d M Y') }}<br>
                                <strong>Total:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </div>
                            <div class="mb-3">
                                <h6 class="mt-4 mb-2">Current Statuses:</h6>
                                {{-- Status Pengerjaan --}}
                                <span class="status-badge status-{{ strtolower($transaction->status) }}">
                                    Pengerjaan: {{ ucfirst($transaction->status) }}
                                </span>
                                {{-- Status Pengantaran --}}
                                <span class="status-badge status-{{ strtolower($deliveryStatus) }}">
                                    Pengantaran: {{ $getDeliveryStatusText($deliveryStatus) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Order Progress</h5>
                            
                            <div class="row">
                                {{-- TIMELINE 1: PENGERJAAN LAUNDRY (Status) --}}
                                <div class="col-6">
                                    <h6 class="text-primary mb-2">Progress Pengerjaan</h6>
                                    <div class="status-timeline status-timeline-laundry">
                                        
                                        @php
                                            $laundryStatuses = [
                                                'pending' => 'Order Diterima',
                                                'process' => 'Disortir/Proses Awal',
                                                'washing' => 'Dicuci',
                                                'drying' => 'Dikeringkan',
                                                'ironing' => 'Disetrika',
                                                'ready' => 'Siap Dikemas',
                                                'completed' => 'Selesai Total'
                                            ];
                                            $currentLaundryIndex = array_search($transaction->status, array_keys($laundryStatuses));
                                        @endphp
                                        
                                        @foreach($laundryStatuses as $status => $label)
                                            @php
                                                $index = array_search($status, array_keys($laundryStatuses));
                                                $isActive = $index <= $currentLaundryIndex;
                                                $isCurrent = $status === $transaction->status;
                                            @endphp
                                            <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                                <strong>{{ $label }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                {{-- TIMELINE 2: PENGANTARAN (Delivery_Status) --}}
                                <div class="col-6">
                                    <h6 class="text-primary mb-2">Progress Pengantaran</h6>
                                    <div class="status-timeline status-timeline-delivery">
                                        
                                        @php
                                            $deliveryTimeline = [
                                                'scheduled' => 'Barang Dijadwalkan',
                                                'received' => 'Barang Diterima Laundry',
                                                'packed' => 'Selesai Dikemas',
                                                'delivered' => 'Barang Diambil/Dikirim',
                                            ];
                                            $currentDeliveryIndex = array_search($deliveryStatus, array_keys($deliveryTimeline));
                                        @endphp
                                        
                                        @foreach($deliveryTimeline as $status => $label)
                                            @php
                                                $index = array_search($status, array_keys($deliveryTimeline));
                                                $isActive = $index <= $currentDeliveryIndex;
                                                $isCurrent = $status === $deliveryStatus;
                                            @endphp
                                            <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                                <strong>{{ $label }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div> {{-- End row for timelines --}}
                            
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="result-section text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No transactions found with the provided search criteria.
                    </div>
                    <p class="text-muted">Please check your search input and try again.</p>
                </div>
            @endif
        @endif

        <div class="contact-section">
            <h4 class="mb-3">Need Help?</h4>
            <p class="text-muted mb-4">Contact us if you have any questions about your order</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <i class="fas fa-phone fa-2x text-primary mb-2"></i>
                        <h6>Phone</h6>
                        <p class="text-muted">+62 123 456 7890</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                        <h6>Email</h6>
                        <p class="text-muted">info@dryclean.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-2"></i>
                        <h6>Address</h6>
                        <p class="text-muted">Jl. Laundry No. 123, Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('adm/assets/js/jquery-3.6.0.min.js') }}"></script>
    
    <script src="{{ asset('adm/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        // Auto-switch to the correct tab based on search type
        @if(request('search_type'))
            document.addEventListener('DOMContentLoaded', function() {
                const searchType = '{{ request("search_type") }}';
                const tabButton = document.getElementById(searchType + '-tab');
                if (tabButton) {
                    // Pastikan elemen tab panel diaktifkan (Bootstrap 5)
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            });
        @endif
    </script>
</body>
</html>