@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Suplier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Beranda</li>
                <li class="breadcrumb-item ">Supplier</li>
                <li class="breadcrumb-item active">Tambah </li>
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

    <form action="{{ route('supplier.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="string" name="nama_barang" class="form-control" required>
        </div>

       
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
