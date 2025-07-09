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

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            @unless(in_array(auth()->user()->role, ['manager']))

                            <a href="{{ route('permintaan.create') }}" class="btn btn-primary">+ Tambah Permintaan</a>
                            @endunless
                        </div>
                        @unless(auth()->user()->role === 'permintaan')
                        <div>
                            <a href="{{ route('permintaan.export.excel') }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Export Excel</a>
                            <a href="{{ route('permintaan.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank"><i class="fa fa-file-pdf"></i> Export PDF</a>
                        </div>
                        @endunless
                    </div>
                </div>


                <div class="card-body">
                    <table id="tabel-permintaan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                {{-- <th>No</th> --}}
                                <th>Pengguna</th>
                                <th>Bagian</th>
                                <th>Barang</th>
                                <th>Jumlah Permintaan</th>
                                <th>Stok di gudang</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                @unless(in_array(auth()->user()->role, ['manager', 'permintaan']))

                                <th>Aksi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permintaans as $permintaan)
                                <tr>
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    <td>{{ $permintaan->pengguna->nama ?? '-' }}</td>
                                    <td>{{ $permintaan->pengguna->bagian ?? '-' }}</td>
                                    <td>
                                        <ul class="mb-0 pl-3">
                                            @foreach($permintaan->barang as $barang)
                                                <li>{{ $barang->nama_barang }} </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $barang->pivot->jumlah }}</td>
                                    <td>
                                         @foreach($permintaan->barang as $barang)
                                                {{ $barang->stok }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($permintaan->status == 'disetujui') badge-success
                                            @elseif($permintaan->status == 'ditolak') badge-danger
                                            @else badge-secondary
                                            @endif">
                                            {{ ucfirst($permintaan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $permintaan->created_at->format('d-m-Y') }}
                                    </td>
                                    @unless(in_array(auth()->user()->role, ['manager', 'permintaan']))

                                    <td>
                                        @if($permintaan->status == 'menunggu')
                                            <form action="{{ route('permintaan.approve', $permintaan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                            </form>
                                            <form action="{{ route('permintaan.reject', $permintaan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                            </form>
                                        @else
                                            <em></em>
                                        @endif
                                    </td>
                                    @endunless
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada permintaan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
</div> <!-- /.container -->
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


