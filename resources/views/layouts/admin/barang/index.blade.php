@extends('layouts.main')
{{-- @dd($barangs) --}}

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Barang</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">beranda</a></li>
                <li class="breadcrumb-item active">dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">+ Tambah Barang</a>

                    <!-- Input filter pencarian -->
                    {{-- <input class="form-control mb-3" id="searchInput" type="text" placeholder="Cari barang..."> --}}

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga beli</th>
                                <th>stok</th>
                                <th>satuan</th>
                                <th>minimal stok</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $b)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->harga_beli }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>{{ $b->satuan }}</td>
                                <td>{{ $b->safetystok->minstok ?? 'minstok null' }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $b->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $b->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $b->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('barang.update', $b->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $b->id }}">Edit Barang</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Barang</label>
                                                            <input type="text" name="nama_barang" value="{{ $b->nama_barang }}" class="form-control">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Harga Beli</label>
                                                            <input type="number" name="harga_beli" value="{{ $b->harga_beli }}" class="form-control">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Stok</label>
                                                            <input type="number" name="stok" value="{{ $b->stok }}" class="form-control">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Satuan </label>
                                                            <select name="safetystok_id" class="form-control" required>
                                                                <option disabled {{ $b->safetystok_id == null ? 'selected' : '' }}>-- Pilih satuan --</option>
                                                                @foreach($satuans as $s)
                                                                    <option value="{{ $s->id }}" {{ $b->safetystok_id == $s->id ? 'selected' : '' }}>
                                                                        {{ $s->satuan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Script Filter Tabel -->
                    {{-- <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const searchInput = document.getElementById("searchInput");
                            const tableRows = document.querySelectorAll("table tbody tr");

                            searchInput.addEventListener("keyup", function () {
                                const filter = searchInput.value.toLowerCase();
                                tableRows.forEach(function (row) {
                                    const text = row.textContent.toLowerCase();
                                    row.style.display = text.includes(filter) ? "" : "none";
                                });
                            });
                        });
                    </script> --}}

                </div>
            </div>
        </div>
    </div>
@endsection
