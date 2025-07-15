@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Barang Masuk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Barang Masuk</li>
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
                        {{-- Tidak ada tombol tambah karena data ini berasal dari pengadaan --}}
                    </div>
                    <div>
                        <a href="{{ route('barang_masuk.export_excel') }}" class="btn btn-success btn-sm me-2">
                            <i class="fa fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('barang-masuk.export.pdf') }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                {{-- Filter --}}
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="barang_id" class="form-label">Nama Barang</label>
                        <select name="barang_id" class="form-select">
                            <option value="">-- Semua Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_diterima" class="form-label">Tanggal Diterima</label>
                        <input type="date" name="tanggal_diterima" class="form-control" value="{{ request('tanggal_diterima') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search"></i> Filter
                        </button>
                    </div>
                </form>

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pengadaan</th>
                                <th>Tanggal Diterima</th>
                                <th>Lead Time (hari)</th> {{-- Tambahkan ini --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->barang->nama_barang }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ optional($item->pengadaan)->tanggal_pengadaan ?? '-' }}</td>
                                    <td>
                                        @if($item->tanggal_diterima)
                                            <span class="badge bg-success">{{ $item->tanggal_diterima }}</span>
                                        @else
                                            <span class="text-muted">Belum diterima</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tanggal_diterima && optional($item->pengadaan)->tanggal_pengadaan)
                                            {{-- Hitung selisih hari --}}
                                            {{ \Carbon\Carbon::parse($item->pengadaan->tanggal_pengadaan)->diffInDays(\Carbon\Carbon::parse($item->tanggal_diterima)) }} hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$item->tanggal_diterima)
                                            <form action="{{ route('barang-masuk.terima', $item->id) }}" method="POST" onsubmit="return confirm('Yakin barang sudah diterima?')">
                                                @csrf
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fa fa-check"></i> Barang Diterima
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-success"><i class="fa fa-check-circle"></i> Diterima</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">Tidak ada data barang masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $barangMasuk->withQueryString()->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
