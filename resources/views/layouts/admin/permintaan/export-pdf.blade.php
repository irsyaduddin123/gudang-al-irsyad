<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Permintaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
    </style>
</head>
<body>
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
                        <td>{{ $permintaan->pengguna->bagian ?? '-' }}</td>
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
