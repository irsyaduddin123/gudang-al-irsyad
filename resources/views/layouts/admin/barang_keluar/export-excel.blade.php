<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Tanggal Keluar</th>
            <th>Permintaan ID</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barang_keluar as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->tanggal_keluar }}</td>
                <td>#{{ $item->permintaan_id }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
