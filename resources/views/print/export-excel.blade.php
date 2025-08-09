<!DOCTYPE html>
<html>
<head>
    <title>Export Riwayat Pembayaran - {{ $filename }}</title>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .number {
            text-align: right;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SIMANIIS - Sistem Manajemen Infaq Siswa</div>
        <div class="subtitle">Laporan Riwayat Pembayaran</div>
        <div class="subtitle">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal Bayar</th>
                <th width="15%">NIS</th>
                <th width="20%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="12%">Bulan/Periode</th>
                <th width="12%">Nominal</th>
                <th width="14%">Input Oleh</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalNominal = 0; @endphp
            @foreach($transaksi as $item)
            @php $totalNominal += $item->nominal; @endphp
            <tr>
                <td class="center">{{ $no++ }}</td>
                <td>{{ $item->tanggal_bayar->format('d/m/Y H:i') }}</td>
                <td>{{ $item->siswa->nis }}</td>
                <td>{{ $item->siswa->nama_lengkap }}</td>
                <td>{{ $item->siswa->kelas->nama_kelas }}</td>
                <td>{{ \Carbon\Carbon::parse($item->bulan_bayar)->format('F Y') }}</td>
                <td class="number">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td>{{ $item->user->name }}</td>
            </tr>
            @endforeach
            
            @if($transaksi->count() > 0)
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="6" class="center">TOTAL</td>
                <td class="number">{{ number_format($totalNominal, 0, ',', '.') }}</td>
                <td>{{ $transaksi->count() }} transaksi</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($transaksi->count() == 0)
    <div style="text-align: center; margin-top: 50px; color: #666;">
        <p>Tidak ada data riwayat pembayaran untuk kriteria yang dipilih.</p>
    </div>
    @endif

    <div style="margin-top: 30px; font-size: 11px; color: #666;">
        <p><strong>Keterangan:</strong></p>
        <ul>
            <li>Total Transaksi: {{ $transaksi->count() }} pembayaran</li>
            <li>Total Nominal: Rp {{ number_format($totalNominal, 0, ',', '.') }}</li>
            <li>File ini digenerate dari SIMANIIS pada {{ now()->format('d/m/Y H:i:s') }}</li>
        </ul>
    </div>
</body>
</html>