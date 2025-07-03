@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Data ROP & EOQ</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">ROP & EOQ</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="d-flex justify-content-between w-100">
                        <div>
                            <a href="{{ route('rop-eoq.create') }}" class="btn btn-primary"> Lakukan Perhitungan</a>
                        </div>
                        <div>
                            {{-- <a href="{{ route('rop-eoq.export.excel') }}" class="btn btn-info btn-sm">Export Excel</a> --}}
                            <a href="{{ route('rop-eoq.export.excel') }}" class="btn btn-info btn-sm">Export Excel</a>

                        </div>
                    </div>
            </div>
            <div class="card-body">              
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Stok Saat Ini</th>
                            <th>Lead Time (hari)</th>
                            <th>Pemakaian Rata-rata</th>
                            <th>Biaya Pesan</th>
                            <th>Biaya Simpan</th>
                            <th>ROP</th>
                            <th>EOQ</th>
                            {{-- <th>bulanSekarang</th> --}}
                            <th>Tanggal Dihitung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ropEoq as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->barang->stok ?? '0' }}</td>
                                <td>{{ $item->lead_time }} hari</td>
                                <td>{{ number_format($item->pemakaian_rata, 2) }}</td>
                                <td>Rp {{ number_format($item->biaya_pesan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->biaya_simpan, 0, ',', '.') }}</td>
                                <td><strong>{{ number_format($item->rop, 2) }}</strong></td>
                                <td><strong>{{ number_format($item->eoq, 2) }}</strong></td>
                                {{-- <td><strong>{{ ($item->bulan) }}</strong></td> --}}
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada data ROP & EOQ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
