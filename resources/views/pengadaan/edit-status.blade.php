@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4>Ubah Status Pengadaan: {{ $pengadaan->barang->nama_barang }}</h4>

    <form action="{{ route('pengadaan.update-status', $pengadaan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="status" class="form-label">Status Pengadaan</label>
            <select name="status" class="form-control" required>
                <option value="menunggu" {{ $pengadaan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ $pengadaan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ $pengadaan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
