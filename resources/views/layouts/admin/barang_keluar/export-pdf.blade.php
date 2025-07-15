<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { display: flex; align-items: center; margin-bottom: 10px; }
        .header img { height: 60px; margin-right: 15px; }
        .header-title h2, .header-title p { margin: 0; padding: 0; }
        .intro { margin-top: 10px; }

        .signature {
            margin-top: 50px;
            width: 100%;
            text-align: right;
        }
        .signature p {
            margin: 0;
        }
        .signature-name {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo2.png') }}" alt="Logo Rumah Sakit">
        <div class="header-title">
            <h2>Rumah Sakit Al-Irsyad</h2>
            <p>Jl. KH Mas Mansyur No.210-214 dan 191</p>
            <p><strong>Bagian Logistik & Inventaris</strong></p>
        </div>
    </div>

    <h3 style="text-align: center; margin-bottom: 5px;">
        Laporan Barang Keluar
    </h3>

    <div class="intro">
        <p>Berikut ini adalah data barang yang telah dikeluarkan dari gudang berdasarkan permintaan bagian terkait:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Keluar</th>
                <th>Bagian Permintaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barang_keluar as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->permintaan->pengguna->bagian ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p><strong>Kepala Logistik</strong></p>
        <div class="signature-name">
            <p><u>....................................</u></p>
        </div>
    </div>

</body>
</html>
