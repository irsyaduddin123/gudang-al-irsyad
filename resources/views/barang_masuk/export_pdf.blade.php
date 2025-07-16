<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { display: flex; align-items: center; margin-bottom: 10px; }
        .header img { height: 60px; margin-right: 15px; }
        .header-title h2, .header-title p { margin: 0; padding: 0; }
        .intro { margin-top: 10px; }
        .signature { width: 200px; text-align: center; float: right; margin-top: 50px; }
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
        Laporan Barang Masuk Bulan {{ $bulan ?? '...' }}
    </h3>

    <div class="intro">
        <p>Berikut ini adalah rekapitulasi barang yang telah diterima ke dalam gudang Rumah Sakit Al-Irsyad, termasuk tanggal pengadaan, tanggal diterima, serta nama supplier sebagai referensi logistik:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pengadaan</th>
                <th>Tanggal Diterima</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangMasuk as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ optional($item->pengadaan)->tanggal_pengadaan ? \Carbon\Carbon::parse($item->pengadaan->tanggal_pengadaan)->format('d-m-Y') : '-' }}</td>
                <td>{{ $item->tanggal_diterima ? \Carbon\Carbon::parse($item->tanggal_diterima)->format('d-m-Y') : '-' }}</td>
                <td>{{ optional($item->pengadaan->supplier)->nama_supplier ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; color: #555;">
            Barang masuk tidak ada untuk filter yang dipilih.
        </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- <div class="signature">
        <p>Surabaya, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p><strong>Kepala Logistik</strong></p>
        <br><br><br>
        <p><u>....................................</u></p>
    </div> --}}

</body>
</html>
