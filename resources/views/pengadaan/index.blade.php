@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Pengadaan Barang</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Pengadaan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            {{-- <div class="card-header">
                <h5 class="mb-0">Daftar Pengadaan Barang</h5>
            </div> --}}
            <div class="card-body">
                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Filter Status --}}
                <form method="GET" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="filter_status" class="form-label">Filter Status</label>
                        <select name="status" id="filter_status" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </form>

                {{-- Tabel Pengadaan --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengadaans as $pengadaan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengadaan->barang->nama_barang }}</td>
                                <td>{{ $pengadaan->supplier->nama_supplier ?? '-' }}</td>
                                <td>{{ $pengadaan->tanggal_pengadaan }}</td>
                                <td>{{ $pengadaan->jumlah }}</td>
                                <td>
                                    <span class="badge 
                                        @if($pengadaan->status == 'menunggu') bg-warning 
                                        @elseif($pengadaan->status == 'disetujui') bg-success 
                                        @else bg-danger @endif">
                                        {{ ucfirst($pengadaan->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if(in_array(auth()->user()->role, ['manager', 'admin']))
                                        @if($pengadaan->status === 'menunggu')
                                            <form action="{{ route('pengadaan.approve', $pengadaan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pengadaan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <form action="{{ route('pengadaan.reject', $pengadaan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pengadaan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">{{ ucfirst($pengadaan->status) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-muted">Tidak ada data pengadaan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination jika diperlukan --}}
                {{-- <div class="d-flex justify-content-end mt-3">
                    {{ $pengadaans->links('pagination::bootstrap-5') }}
                </div> --}}

            </div>
        </div>
    </div>
</div>
@endsection
