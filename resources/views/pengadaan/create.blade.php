@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4>Buat Pengadaan untuk: {{ $barang->nama_barang }}</h4>

    <form action="{{ route('pengadaan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="barang_id" value="{{ $barang->id }}">

        <div class="mb-3">
            <label for="tanggal_pengadaan" class="form-label">Tanggal Pengadaan</label>
            <input type="date" name="tanggal_pengadaan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Barang</label>
            <input type="number" name="jumlah" class="form-control"
                   value="{{ $barang->ropEoq->eoq ?? 1 }}" min="1" required>
            <small class="form-text text-muted">
                Nilai default EOQ: {{ $barang->ropEoq->eoq ?? '-' }}
            </small>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Pilih Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
