<h4>Data Barang Masuk</h4>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Tanggal Pengadaan</th>
            <th>Tanggal Diterima</th>
        </tr>
    </thead>
    <tbody>
        @foreach($barangMasuk as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->barang->nama_barang }}</td>
            <td>{{ $item->jumlah }}</td>
            <td>{{ optional($item->pengadaan)->tanggal_pengadaan ?? '-' }}</td>
            <td>{{ $item->tanggal_diterima ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
