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
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <a href="{{ route('rop-eoq.create') }}" class="btn btn-primary">
                            Lakukan Perhitungan
                        </a>
                    </div>
                    <div>
                            <a href="{{ route('rop-eoq.export.excel') }}" class="btn btn-success">
                            <i class="fa fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ route('rop-eoq.export.pdf') }}" class="btn btn-danger">
                            <i class="fa fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
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
                            <th>Tanggal Dihitung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ropEoq as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td class="text-center">{{ $item->barang->stok }}</td>
                                <td class="text-center">{{ $item->lead_time }} hari</td>
                                <td class="text-end">{{ number_format($item->pemakaian_rata, 2) }}</td>
                                <td class="text-end">Rp {{ number_format($item->biaya_pesan, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->biaya_simpan, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->rop, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->eoq, 2) }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td class="text-center text-nowrap">
                                    <div class="d-flex flex-row justify-content-center gap-1">
                                        <form action="{{ route('rop-eoq.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                        <button 
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateRopEoq"
                                            data-id="{{ $item->id }}"
                                            data-barang_id="{{ $item->barang_id }}"
                                            data-nama_barang="{{ $item->barang->nama_barang }}"
                                            data-lead_time="{{ $item->lead_time }}"
                                            data-biaya_simpan="{{ $item->biaya_simpan }}"
                                            data-periode="{{ \Carbon\Carbon::parse($item->bulan)->format('Y-m') }}"
                                        >
                                            Perhitungan Ulang
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Modal Update ROP & EOQ -->
<div class="modal fade" id="modalUpdateRopEoq" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formUpdateRopEoq" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perhitungan Ulang ROP & EOQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" name="barang_id" id="modal_barang_id">

                {{-- Nama barang readonly --}}
                <div class="mb-3">
                    <label>Nama Barang</label>
                    <input type="text" id="modal_nama_barang" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label>Periode Bulan</label>
                    <input type="month" name="periode" id="modal_periode" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Biaya Simpan</label>
                    <input type="number" name="biaya_simpan" id="modal_biaya_simpan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Lead Time (hari)</label>
                    <input type="number" name="lead_time" id="modal_lead_time" class="form-control" required>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </div>
    </form>
  </div>
</div>
<script>
    const modal = document.getElementById('modalUpdateRopEoq');
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const barangId = button.getAttribute('data-barang_id');
        const namaBarang = button.getAttribute('data-nama_barang');
        const leadTime = button.getAttribute('data-lead_time');
        const biayaSimpan = button.getAttribute('data-biaya_simpan');
        const periode = button.getAttribute('data-periode');

        const form = document.getElementById('formUpdateRopEoq');
        form.action = '/rop-eoq/' + id;

        document.getElementById('modal_barang_id').value = barangId;
        document.getElementById('modal_nama_barang').value = namaBarang;
        document.getElementById('modal_lead_time').value = leadTime;
        document.getElementById('modal_biaya_simpan').value = biayaSimpan;
        document.getElementById('modal_periode').value = periode;
    });
</script>

@endsection
