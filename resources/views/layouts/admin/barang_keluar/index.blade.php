@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Barang Keluar</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Barang Keluar</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-end w-100">
                    <div>
                        <a href="{{ route('barang_keluar.export.excel', request()->query()) }}" class="btn btn-success btn-sm me-2">
                            <i class="fa fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('barang_keluar.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- Form Filter --}}
                <form method="GET" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label for="permintaan_id" class="form-label">ID Permintaan</label>
                            <select name="permintaan_id" class="form-select">
                                <option value="">-- Semua Permintaan --</option>
                                @foreach($permintaans as $permintaan)
                                    <option value="{{ $permintaan->id }}" {{ request('permintaan_id') == $permintaan->id ? 'selected' : '' }}>
                                        {{ $permintaan->pengguna->Bagian ?? 'User' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Filter
                            </button>
                            <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary w-100">
                                <i class="fa fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Filter Summary --}}
                @php
                    $bagianPengguna = null;
                    if (request('permintaan_id')) {
                        $permintaanTerpilih = $permintaans->firstWhere('id', request('permintaan_id'));
                        $bagianPengguna = $permintaanTerpilih->pengguna->Bagian ?? 'Tidak Dikenal';
                    }
                @endphp

                @if(request()->hasAny(['tanggal_awal', 'tanggal_akhir', 'barang_id', 'permintaan_id']))
                    <div class="alert alert-info">
                        Menampilkan hasil filter:
                        <ul class="mb-0">
                            @if(request('tanggal_awal') && request('tanggal_akhir'))
                                <li>
                                    Tanggal: {{ \Carbon\Carbon::parse(request('tanggal_awal'))->translatedFormat('d F Y') }}
                                    s/d
                                    {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->translatedFormat('d F Y') }}
                                </li>
                            @endif
                            @if(request('barang_id'))
                                <li>Barang: {{ $barangs->find(request('barang_id'))?->nama_barang }}</li>
                            @endif
                            @if(request('permintaan_id'))
                                <li>Bagian: {{ $bagianPengguna }}</li>
                            @endif
                        </ul>
                    </div>
                @endif

                {{-- Table Data --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Keluar</th>
                                <th>Bagian Permintaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barang_keluar as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') }}</td>
                                    <td>{{ $item->permintaan->pengguna->Bagian ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Tidak ada data barang keluar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Total Count --}}
                @if($barang_keluar->count())
                    <p class="text-muted mt-3">Total data ditampilkan: <strong>{{ $barang_keluar->count() }}</strong></p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
