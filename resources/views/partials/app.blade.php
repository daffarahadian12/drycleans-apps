<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - DryClean</title>
    <link rel="shortcut icon" href="{{ asset('adm/assets/img/favicon.png') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/icons/flags/flags.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/css/style.css') }}">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <style>
        .bg-gold {
            background-color: #FFD700;
        }
        
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: 0.755em;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: 0.25em;
        }
        
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .filter-btn.active {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        
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
            left: -22px;
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

        .timeline-item.current .timeline-marker {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 2px var(--bs-primary), 0 0 0 4px rgba(var(--bs-primary-rgb), 0.3);
            }
            50% {
                box-shadow: 0 0 0 2px var(--bs-primary), 0 0 0 8px rgba(var(--bs-primary-rgb), 0.1);
            }
            100% {
                box-shadow: 0 0 0 2px var(--bs-primary), 0 0 0 4px rgba(var(--bs-primary-rgb), 0.3);
            }
        }

        .timeline-content {
            padding-left: 15px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="main-wrapper">
        @include('partials.header')
        @include('partials.sidebar')
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-sub-header">
                                <h3 class="page-title">@yield('title')</h3>
                                @php
                                    $segments = request()->segments();
                                @endphp
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    @foreach ($segments as $key => $segment)
                                        @php
                                            $url = url(implode('/', array_slice($segments, 0, $key + 1)));
                                            $name = ucfirst(str_replace(['-', '_'], ' ', $segment));
                                        @endphp
                                        @if ($key == count($segments) - 1)
                                            <li class="breadcrumb-item active">{{ $name }}</li>
                                        @else
                                            <li class="breadcrumb-item"><a
                                                    href="{{ $url }}">{{ $name }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="{{ asset('adm/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adm/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/script.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    
    <!-- Yajra DataTables -->
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}
    
    <script>
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize DataTables with default settings
        $(document).ready(function() {
            // Add DataTables row index
            $.fn.dataTable.ext.errMode = 'throw';
            $.fn.dataTable.ext.pager.numbers_length = 5;
            
            // Default DataTable settings
            $.extend($.fn.dataTable.defaults, {
                responsive: true,
                processing: true,
                language: {
                    "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sLoadingRecords": "Sedang memuat...",
                    "sProcessing": "Sedang memproses...",
                    "sSearch": "Cari:",
                    "sSearchPlaceholder": "Cari data...",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sLast": "Terakhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                    "oAria": {
                        "sSortAscending": ": aktifkan untuk mengurutkan kolom naik",
                        "sSortDescending": ": aktifkan untuk mengurutkan kolom turun"
                    }
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
