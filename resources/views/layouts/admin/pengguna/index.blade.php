@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
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
                            <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">+ Tambah Pengguna</a>
                            @endunless
                        </div>
                    </div>
                </div>
            <div class="card-body">
                @unless(auth()->user()->role === 'manager')
                
                @endunless
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Username</th>
                            <th>Ruangan</th>
                            <th>Role</th>
                            @unless(auth()->user()->role === 'manager')
                            <th>Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengguna as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->username }}</td>
                                <td>{{ $p->bagian }}</td>
                                <td>{{ $p->role }}</td>
                                @unless(auth()->user()->role === 'manager')
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $p->id }}">
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('pengguna.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit (diletakkan di luar <tr>) -->
                            <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('pengguna.update', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $p->id }}">Edit Pengguna</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Pengguna</label>
                                                    <input type="text" name="nama" value="{{ $p->nama }}" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text" name="username" value="{{ $p->username }}" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password (Kosongkan jika tidak ingin diubah)</label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Bagian Ruangan</label>
                                                    <input type="text" name="bagian" value="{{ $p->bagian }}" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Role</label>
                                                    <select name="role" class="form-control" required>
                                                        <option disabled {{ $p->role == null ? 'selected' : '' }}>-- Pilih Role --</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role }}" {{ $p->role == $role ? 'selected' : '' }}>
                                                                {{ ucfirst($role) }}
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
                            @endunless
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
