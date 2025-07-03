@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Barang</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Beranda</li>
                <li class="breadcrumb-item ">Barang</li>
                <li class="breadcrumb-item active">Tambah Barang</li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- <h3>Tambah Barang</h3> --}}

    <form action="{{ route('barang.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Satuan </label>
            <select name="safetystok_id" class="form-control" required>
                <option disabled selected>-- Pilih satuan --</option>
                @foreach($satuans as $s)
                    <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
