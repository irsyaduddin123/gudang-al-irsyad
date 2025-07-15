<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { display: flex; align-items: center; margin-bottom: 10px; }
        .header img { height: 60px; margin-right: 15px; }
        .header-title h2, .header-title p { margin: 0; padding: 0; }
        .intro { margin-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        {{-- Gunakan salah satu dari dua baris di bawah ini sesuai konteks --}}
        {{-- Untuk browser: --}}
        {{-- <img src="{{ asset('images/logo2.png') }}" alt="Logo Rumah Sakit"> --}}
        {{-- Untuk PDF: --}}
        <img src="{{ public_path('images/logo2.png') }}" alt="Logo Rumah Sakit">

        <div class="header-title">
            <h2>Rumah Sakit Al-Irsyad</h2>
            <p>Jl. KH Mas Mansyur No.210-214 dan 191</p>
            <p><strong>Bagian Logistik & Inventaris</strong></p>
        </div>
    </div>

    <h3 style="text-align: center; margin-bottom: 5px;">
        Data Barang di Gudang - Bulan {{ $bulan }}
    </h3>

    <div class="intro">
        <p>Berikut ini adalah daftar stok barang yang tersedia di gudang untuk periode bulan <strong>{{ $bulan }}</strong>, termasuk detail harga, supplier, dan stok minimal sebagai acuan pengendalian persediaan.</p>
    </div>

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
                <td>{{ number_format($b->harga_beli, 0, ',', '.') }}</td>
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
