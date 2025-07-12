@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Daftar Permintaan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Permintaan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                {{-- Kiri: Tombol Tambah --}}
                <div>
                    @unless(in_array(auth()->user()->role, ['manager']))
                        <a href="{{ route('permintaan.create') }}" class="btn btn-primary">+ Tambah Permintaan</a>
                    @endunless
                </div>

                {{-- Kanan: Tombol Export --}}
                @unless(auth()->user()->role === 'permintaan')
                <div class=" gap-2">
                    <a href="{{ route('permintaan.export.excel') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('permintaan.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="fa fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                @endunless
            </div>
        </div>


        <div class="card-body">
            <table id="tabel-permintaan" class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pengguna</th>
                        <th>Bagian</th>
                        <th>Barang</th>
                        <th>Jumlah Permintaan</th>
                        <th>Stok Gudang</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaans as $permintaan)
                        <tr>
                            <td>{{ $permintaan->pengguna->nama ?? '-' }}</td>
                            <td>{{ $permintaan->pengguna->bagian ?? '-' }}</td>
                            <td>
                                <ul class="mb-0 pl-3">
                                    @foreach($permintaan->barang as $barang)
                                        <li>{{ $barang->nama_barang }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-0 pl-3">
                                    @foreach($permintaan->barang as $barang)
                                        <li>{{ $barang->pivot->jumlah }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-0 pl-3">
                                    @foreach($permintaan->barang as $barang)
                                        <li>{{ $barang->stok }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($permintaan->status == 'disetujui') badge-success
                                    @elseif($permintaan->status == 'ditolak') badge-danger
                                    @elseif($permintaan->status == 'butuh_validasi_manager') badge-warning
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $permintaan->status)) }}
                                </span>
                            </td>
                            <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                            <td>
                                {{-- Aksi Staff --}}
                                @if(auth()->user()->role === 'staff' && $permintaan->status === 'menunggu')
                                    <form action="{{ route('permintaan.approve', $permintaan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <form action="{{ route('permintaan.reject', $permintaan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>

                                {{-- Validasi Manager --}}
                                @elseif(auth()->user()->role === 'manager' && $permintaan->status === 'butuh_validasi_manager')
                                    <form action="{{ route('permintaan.validasi.setujui', $permintaan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Validasi</button>
                                    </form>
                                    <form action="{{ route('permintaan.validasi.tolak', $permintaan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                @else
                                    <em>Tidak ada aksi</em>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada permintaan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#tabel-permintaan').DataTable({
            paging: true,
            pagingType: "simple_numbers",
            lengthChange: false,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: true,
            pageLength: 10,
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ditemukan data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(disaring dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "«",
                    last: "»",
                    next: "›",
                    previous: "‹"
                }
            }
        });
    });
</script>
@endsection
