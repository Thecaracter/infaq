<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran - {{ $transaksi->siswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 3px;
        }
        .content {
            line-height: 1.6;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .row.highlight {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin: 15px 0;
        }
        .label {
            font-weight: 600;
            color: #374151;
        }
        .value {
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .separator {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }
        .thank-you {
            text-align: center;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            color: #166534;
        }
        .islamic-quote {
            font-style: italic;
            font-size: 11px;
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 8px;
            margin: 15px 0;
            color: #92400e;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .receipt {
                border: none;
                max-width: none;
                box-shadow: none;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="logo">SIMANIIS</div>
            <div class="subtitle">Sistem Manajemen Infaq Siswa</div>
            <div class="subtitle" style="font-weight: bold; color: #000; margin-top: 8px;">BUKTI PEMBAYARAN</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Informasi Transaksi -->
            <div class="row">
                <span class="label">No. Transaksi:</span>
                <span class="value">#{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="row">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $transaksi->tanggal_bayar->format('d/m/Y H:i') }}</span>
            </div>

            <div class="separator"></div>

            <!-- Informasi Siswa -->
            <div class="row">
                <span class="label">Nama Siswa:</span>
                <span class="value">{{ $transaksi->siswa->nama_lengkap }}</span>
            </div>
            
            <div class="row">
                <span class="label">NIS:</span>
                <span class="value">{{ $transaksi->siswa->nis }}</span>
            </div>
            
            <div class="row">
                <span class="label">Kelas:</span>
                <span class="value">{{ $transaksi->siswa->kelas->nama_kelas }}</span>
            </div>

            <div class="separator"></div>

            <!-- Informasi Pembayaran -->
            <div class="row">
                <span class="label">Periode:</span>
                <span class="value">{{ \Carbon\Carbon::parse($transaksi->bulan_bayar)->format('F Y') }}</span>
            </div>
            
            <div class="row highlight">
                <span class="label">Total Pembayaran:</span>
                <span class="value">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</span>
            </div>

            @if($transaksi->keterangan)
            <div class="separator"></div>
            <div class="row">
                <span class="label">Keterangan:</span>
                <span class="value">{{ $transaksi->keterangan }}</span>
            </div>
            @endif

            <div class="separator"></div>

            <!-- Informasi Petugas -->
            <div class="row">
                <span class="label">Diterima oleh:</span>
                <span class="value">{{ $transaksi->user->name }}</span>
            </div>

            <!-- Informasi Orang Tua -->
            @if($transaksi->siswa->orangTua)
            <div class="separator"></div>
            <div class="row">
                <span class="label">Orang Tua/Wali:</span>
                <span class="value">{{ $transaksi->siswa->orangTua->nama_wali }}</span>
            </div>
            <div class="row">
                <span class="label">No. HP:</span>
                <span class="value">{{ $transaksi->siswa->orangTua->no_hp }}</span>
            </div>
            @endif

            <!-- Thank You Message -->
            <div class="thank-you">
                <strong>Jazakallahu Khairan</strong><br>
                Terima kasih atas pembayaran infaq Anda
            </div>

            <!-- Islamic Quote -->
            <div class="islamic-quote">
                "Dan belanjakanlah (harta bendamu) di jalan Allah, dan janganlah kamu menjatuhkan dirimu sendiri ke dalam kebinasaan, dan berbuatbaiklah; karena sesungguhnya Allah menyukai orang-orang yang berbuat baik." <br>
                <strong>(QS. Al-Baqarah: 195)</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin-bottom: 8px;">Bukti pembayaran ini dicetak pada {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-bottom: 5px; font-weight: bold;">SIMANIIS - Sistem Manajemen Infaq Siswa</p>
            <p style="margin: 0; font-size: 10px;">Mohon simpan bukti pembayaran ini sebagai arsip</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
            
            // Close window after printing (optional)
            setTimeout(function() {
                window.close();
            }, 1000);
        };
    </script>
</body>
</html>