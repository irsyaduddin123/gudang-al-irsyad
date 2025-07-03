@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Safety Stock</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Beranda</li>
                <li class="breadcrumb-item ">Safety stok</li>
                <li class="breadcrumb-item active">Tambah </li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
<div class="container">
    {{-- <h3>Tambah Barang</h3> --}}

    <form action="{{ route('safetystok.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>satuan</label>
            <input type="text" name="satuan" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Minimal stok</label>
            <input type="string" name="minstok" class="form-control" required>
        </div>

       
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('safetystok.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
