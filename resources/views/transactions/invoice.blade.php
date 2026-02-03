<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            padding: 10px;
        }

        .thermal-receipt {
            width: 80mm;
            max-width: 300px;
            margin: 0 auto;
            background: white;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }

        .store-info {
            font-size: 10px;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0 5px 0;
            text-align: center;
        }

        .transaction-info {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .info-label {
            flex: 1;
        }

        .info-value {
            flex: 1;
            text-align: right;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .double-separator {
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        .item-section {
            margin-bottom: 10px;
        }

        .item-header {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            font-size: 11px;
        }

        .item-row {
            margin-bottom: 3px;
            font-size: 11px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 1px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .item-qty-price {
            display: flex;
            justify-content: space-between;
            margin-top: 1px;
        }

        .total-section {
            margin-top: 10px;
            font-size: 11px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #000;
            border-bottom: 2px solid #000;
            padding: 3px 0;
            margin: 5px 0;
        }

        .payment-info {
            margin-top: 10px;
            font-size: 11px;
        }

        .customer-info {
            margin: 10px 0;
            font-size: 11px;
        }

        .customer-name {
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
        }

        .member-badge {
            background: #000;
            color: #fff;
            padding: 1px 4px;
            font-size: 9px;
            border-radius: 2px;
            margin-left: 5px;
        }

        .status-info {
            text-align: center;
            margin: 10px 0;
            font-size: 11px;
        }

        .status-current {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer-line {
            margin-bottom: 2px;
        }

        .barcode-section {
            text-align: center;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 10px;
        }

        .barcode-text {
            letter-spacing: 2px;
            font-weight: bold;
        }

        .notes-section {
            margin: 10px 0;
            font-size: 10px;
            border: 1px dashed #000;
            padding: 5px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .print-button {
                display: none;
            }
            
            .thermal-receipt {
                width: 80mm;
                max-width: none;
                margin: 0;
                padding: 0;
            }
            
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 400px) {
            .thermal-receipt {
                width: 100%;
                max-width: 100%;
                padding: 10px;
            }
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .font-small { font-size: 10px; }
        .font-large { font-size: 14px; }
    </style>
</head>
<body>
    @php
        // Helper function untuk menerjemahkan delivery_status
        $getDeliveryStatusText = function ($status) {
            switch ($status) {
                case 'scheduled': return 'Barang Diantar/Jemput';
                case 'received': return 'Barang Diterima';
                case 'packed': return 'Selesai Dikemas';
                case 'delivered': return 'Ambil/Selesai';
                default: return ucfirst($status);
            }
        };

        // Helper function untuk pesan status pengerjaan
        $getStatusMessage = function ($status) {
            $messages = [
                'pending' => 'Menunggu diterima/diproses',
                'process' => 'Sedang diproses',
                'washing' => 'Sedang dicuci',
                'drying' => 'Sedang dikeringkan',
                'ironing' => 'Sedang disetrika',
                'ready' => 'Pengerjaan selesai',
                'completed' => 'Pengerjaan sudah final'
            ];
            return $messages[$status] ?? ucfirst($status);
        };
    @endphp

    <button class="print-button" onclick="window.print()">
        🖨️ CETAK
    </button>

    <div class="thermal-receipt">
        <div class="header">
            <div class="store-name">DryClean</div>
            <div class="store-info">Jl. Yang Lurus No. 123</div>
            <div class="store-info">Pekanbaru 28285</div>
            <div class="store-info">Telp: (021) 1234-5678</div>
        </div>

        <div class="receipt-title">INVOICE LAUNDRY</div>

        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">No Invoice</span>
                <span class="info-value font-bold">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Order</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($transaction->order_date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kasir</span>
                <span class="info-value">{{ $transaction->user->name }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <div class="customer-info">
            <div class="customer-name">
                {{ strtoupper($transaction->customer->name) }}
                @if($transaction->customer->is_member)
                    <span class="member-badge">MEMBER</span>
                @endif
            </div>
            @if($transaction->customer->phone)
            <div class="text-center font-small">{{ $transaction->customer->phone }}</div>
            @endif
            @if($transaction->customer->is_member)
            <div class="text-center font-small">Points: {{ $transaction->customer->points }} pts</div>
            @endif
        </div>

        <div class="separator"></div>

        <div class="item-section">
            <div class="item-header">DETAIL LAYANAN</div>
            
            <div class="item-row">
                <div class="item-name">{{ strtoupper($transaction->package->name) }}</div>
                <div class="item-details">
                    <span>{{ $transaction->weight }} kg</span>
                    <span>x Rp. {{ number_format($transaction->price_per_kg, 0, ',', '.') }}</span>
                </div>
                <div class="item-qty-price">
                    <span></span>
                    <span class="font-bold">Rp. {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            
            @if($transaction->package->description)
            <div class="font-small" style="margin-top: 2px; font-style: italic;">
                {{ $transaction->package->description }}
            </div>
            @endif
        </div>

        <div class="separator"></div>

        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp. {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            
            @if($transaction->discount_amount > 0)
            <div class="total-row">
                <span>Diskon</span>
                <span>- Rp. {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="total-row grand-total">
                <span>TOTAL BAYAR</span>
                <span>Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">Estimasi Selesai</span>
                <span class="info-value font-bold">{{ \Carbon\Carbon::parse($transaction->estimated_completion)->format('d/m/Y') }}</span>
            </div>
            @if($transaction->actual_completion)
            <div class="info-row">
                <span class="info-label">Tgl. Diambil</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($transaction->actual_completion)->format('d/m/Y H:i') }}</span>
            </div>
            @endif
        </div>

        <div class="separator"></div>

        <div class="status-info">
            <div class="font-small font-bold">STATUS PENGERJAAN:</div>
            <div class="status-current">{{ strtoupper($transaction->status) }}</div>
            <div class="font-small">{{ $getStatusMessage($transaction->status) }}</div>
            
            <div class="separator" style="margin: 5px 20px;"></div>
            
            <div class="font-small font-bold">STATUS PENGANTARAN/PENGAMBILAN:</div>
            <div class="status-current">{{ strtoupper($getDeliveryStatusText($transaction->delivery_status)) }}</div>
        </div>

        @if($transaction->notes)
        <div class="notes-section">
            <div class="notes-title">CATATAN:</div>
            <div>{{ $transaction->notes }}</div>
        </div>
        @endif

        <div class="barcode-section">
            <div class="barcode-text">{{ $transaction->invoice_number }}</div>
            <div style="font-family: 'Courier New'; font-size: 8px; margin-top: 3px;">
                ||||| |||| | |||| ||||| |||| |||||
            </div>
        </div>

        <div class="double-separator"></div>

        <div class="footer">
            <div class="footer-line font-bold">TERIMA KASIH</div>
            <div class="footer-line">Barang yang sudah diambil</div>
            <div class="footer-line">tidak dapat dikembalikan</div>
            <div class="footer-line">---</div>
            <div class="footer-line">Simpan struk ini sebagai</div>
            <div class="footer-line">bukti pengambilan barang</div>
            <div class="footer-line">---</div>
            <div class="footer-line font-small">{{ now()->format('d/m/Y H:i:s') }}</div>
            <div class="footer-line font-small">www.dryclean.com</div>
        </div>

        <div style="height: 20px;"></div>
    </div>

    <script>
        // Auto print when opened in new tab
        window.addEventListener('load', function() {
            // Check if opened from print button (atau logic lain yang sesuai)
            // Hati-hati dengan window.print() di load event, bisa menyebabkan loop di beberapa browser
            
            // Menggunakan timeout agar elemen termuat sempurna
            setTimeout(() => {
                // Hapus tombol cetak sebelum mencetak
                const printButton = document.querySelector('.print-button');
                if (printButton) {
                    printButton.style.display = 'none';
                }
                window.print();
                
                // Tambahkan kembali jika user membatalkan
                if (printButton) {
                    setTimeout(() => {
                        printButton.style.display = 'block';
                    }, 100); 
                }
            }, 500);
        });

        // Print function
        function printInvoice() {
            window.print();
        }

        // Keyboard shortcut for print
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });

        // Prevent context menu on receipt (optional)
        document.querySelector('.thermal-receipt').addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>