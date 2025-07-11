@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Daftar Barang Keluar</h4>

    {{-- Form Filter Tanggal --}}
    {{-- <form method="GET" class="mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                       value="{{ request('tanggal_awal') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                       value="{{ request('tanggal_akhir') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </div>
    </form> --}}
    <form method="GET" class="mb-4">
        <div class="row align-items-end">
            {{-- Filter Tanggal --}}
            <div class="col-md-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
            </div>

            {{-- Filter Nama Barang --}}
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

            {{-- Filter ID Permintaan --}}
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

            {{-- Tombol Filter --}}
            <div class="col-md-3 mt-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    {{-- Info Rentang Filter --}}
    {{-- @if(request('tanggal_awal') && request('tanggal_akhir'))
        <div class="alert alert-info">
            Menampilkan data barang keluar dari <strong>{{ request('tanggal_awal') }}</strong> 
            hingga <strong>{{ request('tanggal_akhir') }}</strong>.
        </div>
    @endif --}}

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
                    <li>Tanggal: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}</li>
                @endif
                @if(request('barang_id'))
                    <li>Barang: {{ $barangs->find(request('barang_id'))?->nama_barang }}</li>
                @endif
                @if(request('permintaan_id'))
                    <li>Pengguna (Bagian): {{ $bagianPengguna }}</li>
                @endif
            </ul>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="mb-3">
        <a href="{{ route('barang_keluar.export.excel', request()->query()) }}" class="btn btn-success">Export Excel</a>
        <a href="{{ route('barang_keluar.export.pdf', request()->query()) }}" class="btn btn-danger">Export PDF</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
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
                        {{-- <td>#{{ $item->permintaan_id }}</td> --}}
                        <td>{{ $item->permintaan->pengguna->Bagian ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data barang keluar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
