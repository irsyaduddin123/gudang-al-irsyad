<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h3 style="margin-bottom: 5px;">Laporan Barang Keluar</h3>
    <p style="margin-bottom: 20px;">Berikut ini adalah data barang keluar yang tercatat pada sistem gudang.</p>
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
                    <td>{{ $item->tanggal_keluar }}</td>
                    <td>{{ $item->permintaan->pengguna->Bagian }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
