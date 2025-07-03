@extends('layouts.main')

@section('header')
    <h1>Hitung ROP & EOQ - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h1>
@endsection

@section('content')
<div class="container">
    <form action="{{ route('rop-eoq.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <select name="barang_id" class="form-control" required>
                <option disabled selected>-- Pilih Barang --</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Biaya Simpan (per 30 hari)</label>
            <input type="number" name="biaya_simpan" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Lead Time </label>
            <input type="number" name="lead_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Hitung</button>
        <a href="{{ route('rop-eoq.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
