<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan ROP & EOQ</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
            margin-right: 15px;
        }
        .header-content {
            text-align: center;
            flex-grow: 1;
        }
        .header-content h3,
        .header-content p {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        td.nama-barang {
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo2.png') }}" alt="Logo RS">
        <div class="header-content">
            <h3>Rumah Sakit Al-Irsyad</h3>
            <p>Laporan Perhitungan ROP & EOQ</p>
            <p>Tanggal: {{ $tanggal }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Lead Time (hari)</th>
                <th>Pemakaian Rata-rata</th>
                <th>Biaya Pesan</th>
                <th>Biaya Simpan</th>
                <th>ROP</th>
                <th>EOQ</th>
                <th>Periode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ropEoq as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="nama-barang">{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->barang->stok ?? 0 }}</td>
                    <td>{{ $item->lead_time }}</td>
                    <td>{{ number_format($item->pemakaian_rata, 2) }}</td>
                    <td>Rp {{ number_format($item->biaya_pesan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->biaya_simpan, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->rop, 2) }}</td>
                    <td>{{ number_format($item->eoq, 2) }}</td>
                    <td>{{ $item->bulan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
