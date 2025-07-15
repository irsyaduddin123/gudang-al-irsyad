<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Permintaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        .header { display: flex; align-items: center; }
        .header img { height: 60px; margin-right: 15px; }
        .intro { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo2.png') }}" alt="Logo Rumah Sakit">
        <div>
            <h2>Rumah Sakit Sehat Al-Irsyad</h2>
            <p><strong>Laporan Permintaan Barang Non-Medis</strong></p>
        </div>
    </div>

    <div class="intro">
        <p>Berikut ini adalah rekap data permintaan barang yang diajukan oleh masing-masing bagian di rumah sakit:</p>
    </div>

    <h3>Data Permintaan</h3>
    <table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Bagian</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaans as $permintaan)
                @foreach($permintaan->barang as $barang)
                    <tr>
                        <td>{{ $permintaan->pengguna->nama ?? '-' }}</td>
                        <td>{{ $permintaan->pengguna->Bagian ?? '-' }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->pivot->jumlah }}</td>
                        <td>{{ ucfirst($permintaan->status) }}</td>
                        <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
