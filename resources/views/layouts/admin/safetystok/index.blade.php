@extends('layouts.main')
{{-- @dd($sarangs) --}}

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Safety Stok</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">beranda</a></li>
              <li class="breadcrumb-item active">safety stok</li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('safetystok.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>satuan</th>
                                <th>stok Aman</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($safetystok as $st)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $st->satuan }}</td>
                                <td>{{ $st->minstok }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $st->id }}">
                                        Edit
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $st->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $st->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('safetystok.update', $st->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $st->id }}">Edit Safety Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="satuan" value="{{ $st->satuan }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Stok Aman (Minimal)</label>
                        <input type="number" name="minstok" value="{{ $st->minstok }}" class="form-control" required min="0">
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
                                    <form action="{{ route('safetystok.destroy', $st->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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