@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Detail Barang: {{ $barang->nama_barang }}</h3>
    <table class="table table-bordered">
        <tr>
            <th>Nama Barang</th>
            <td>{{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <th>Harga Beli</th>
            <td>Rp {{ number_format($barang->harga_beli) }}</td>
        </tr>
        <tr>
            <th>Stok</th>
            <td>{{ $barang->stok }}</td>
        </tr>
        <tr>
            <th>Satuan</th>
            <td>{{ $barang->satuan }}</td>
        </tr>
        <tr>
            <th>Safety Stock (stok aman)</th>
            <td>{{ $barang->safetystok->minstok ?? '-' }}</td>
        </tr>
        <tr>
            <th>Titik Pemesanan Ulang Stok (ROP)</th>
            <td>{{ $barang->ropEoq->rop ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pesan kembali sebanyak (EOQ)</th>
            <td>{{ $barang->ropEoq->eoq ?? '-' }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $supplier->nama_supplier ?? '-' }}</td>
        </tr>
    </table>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('pengadaan.create', $barang->id) }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Buat Pengadaan
    </a>
</div>
@endsection
