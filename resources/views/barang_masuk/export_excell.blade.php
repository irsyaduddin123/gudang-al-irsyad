<!DOCTYPE html>
<html>
<head>
    <style>
        .logo {
            width: 100px;
            margin-bottom: 10px;
        }
        .center {
            text-align: center;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="center">
        {{-- <img src="{{ public_path('images/logo2.png') }}" alt="Logo" class="logo"> --}}
        <h2>Laporan Barang Masuk</h2>
        <p>Data barang yang telah diterima oleh gudang.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pengadaan</th>
                <th>Tanggal Diterima</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ optional($item->pengadaan)->tanggal_pengadaan ? \Carbon\Carbon::parse($item->pengadaan->tanggal_pengadaan)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->tanggal_diterima ? \Carbon\Carbon::parse($item->tanggal_diterima)->format('d-m-Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
