@extends('layouts.main')
{{-- @dd($sarangs) --}}

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">beranda</a></li>
              <li class="breadcrumb-item active">supplier</li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            @unless(in_array(auth()->user()->role, ['manager']))
                            <a href="{{ route('supplier.create') }}" class="btn btn-primary mb-3">+ Tambah supplier</a>
                            @endunless
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama supplier</th>
                                <th>nama Barang</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nama_supplier }}</td>
                                <td>{{ $s->nama_barang }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $s->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $s->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('supplier.update', $s->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $s->id }}">Edit Supplier</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nama Supplier</label>
                                                            <input type="text" name="nama_supplier" value="{{ $s->nama_supplier }}" class="form-control" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Nama Barang</label>
                                                            <input type="text" name="nama_barang" value="{{ $s->nama_barang }}" class="form-control" required>
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
                                    <form action="{{ route('supplier.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
@endsection