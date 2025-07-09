@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Beranda</li>
                <li class="breadcrumb-item">Pengguna</li>
                <li class="breadcrumb-item active">Tambah Pengguna</li>
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

    <form action="{{ route('pengguna.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Bagian Ruangan</label>
            <input type="text" name="bagian" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option disabled selected>-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
