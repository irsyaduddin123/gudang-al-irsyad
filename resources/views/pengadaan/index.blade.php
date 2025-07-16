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
                    @php
                        $adaYangDitolak = $pengadaans->contains('status', 'ditolak');
                    @endphp
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                @if($adaYangDitolak)
                                    <th>Keterangan Penolakan</th>
                                @endif
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

                                @if($adaYangDitolak)
                                    <td>
                                        @if($pengadaan->status == 'ditolak')
                                            {{ $pengadaan->keterangan_penolakan ?? '-' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endif

                                <td>
                                    @if(in_array(auth()->user()->role, ['manager', 'admin']))
                                        @if($pengadaan->status === 'menunggu')
                                            <form action="{{ route('pengadaan.approve', $pengadaan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pengadaan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-check"></i> Setujui
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $pengadaan->id }}">
                                                <i class="fa fa-times"></i> Tolak
                                            </button>

                                            <!-- Modal Tolak -->
                                            <div class="modal fade" id="modalTolak{{ $pengadaan->id }}" tabindex="-1" aria-labelledby="modalTolakLabel{{ $pengadaan->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('pengadaan.reject', $pengadaan->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalTolakLabel{{ $pengadaan->id }}">Tolak Pengadaan</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="keterangan_penolakan{{ $pengadaan->id }}" class="form-label">Keterangan Penolakan</label>
                                                                    <textarea name="keterangan_penolakan" id="keterangan_penolakan{{ $pengadaan->id }}" class="form-control" required rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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
                                <td colspan="{{ $adaYangDitolak ? 8 : 7 }}" class="text-muted">Tidak ada data pengadaan.</td>
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

{{-- <form action="{{ route('pengadaan.reject', $pengadaan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pengadaan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times"></i> Tolak
                                                </button>
                                            </form> --}}