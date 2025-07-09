<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3 style="text-align: center; margin-bottom: 10px;">
    Data Barang di Gudang Bulan {{ $bulan }}
</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Supplier</th>
                <th>Minimal Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->harga_beli }}</td>
                <td>{{ $b->stok }}</td>
                <td>{{ $b->satuan }}</td>
                <td>{{ $suppliers[$b->nama_barang]->nama_supplier ?? '-' }}</td>
                <td>{{ $b->safetystok->minstok ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>