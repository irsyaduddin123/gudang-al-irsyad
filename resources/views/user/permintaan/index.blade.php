<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background: url('{{ asset('images/bg.png') }}') center / cover fixed no-repeat;
            background-color: #f8f9fa;
        }
    </style>

    <script>
        function tambahBarang(containerId) {
            const container = document.getElementById(containerId);
            const item = container.querySelector('.barang-item');
            const clone = item.cloneNode(true);
            clone.querySelectorAll('input, select').forEach(el => el.value = '');
            container.appendChild(clone);
        }

        function hapusBarang(button) {
            const container = button.closest('.barang-item').parentElement;
            if (container.querySelectorAll('.barang-item').length > 1) {
                button.closest('.barang-item').remove();
            }
        }

        function tampilkanStok(selectEl) {
            const stok = selectEl.selectedOptions[0].getAttribute('data-stok');
            const container = selectEl.closest('.barang-item');
            const stokInput = container.querySelector('.stok-barang');
            stokInput.value = stok ?? '';
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.barang-select').forEach(select => {
                tampilkanStok(select);
            });
        });
    </script>
</head>
<body>

<div class="container mt-5 bg-white p-4 rounded shadow">

    <!-- Header & Tombol -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Permintaan</h2>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                + Tambah Permintaan
            </button>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>

    <!-- Filter -->
    <div class="mb-4">
        <form action="{{ route('user.permintaan.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <select name="bulan" class="form-select form-select-sm">
                <option value="">-- Pilih Bulan --</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="form-select form-select-sm">
                <option value="">-- Pilih Tahun --</option>
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
            <a href="{{ route('user.permintaan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </form>
    </div>

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabel -->
    <table id="tabelData" class="table table-bordered table-hover text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($permintaans as $permintaan)
            <tr>
                <td>
                    <ul class="list-unstyled mb-0">
                        @foreach($permintaan->barang as $b)
                            <li>{{ $b->nama_barang }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul class="list-unstyled mb-0">
                        @foreach($permintaan->barang as $b)
                            <li>{{ $b->pivot->jumlah }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ ucfirst($permintaan->status) }}</td>
                <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                <td>
                    @if($permintaan->status !== 'disetujui')
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $permintaan->id }}">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $permintaan->id }}">Hapus</button>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
@foreach($permintaans as $permintaan)
<div class="modal fade" id="modalEdit{{ $permintaan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.permintaan.update', $permintaan->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="barangContainerEdit{{ $permintaan->id }}">
                        @foreach($permintaan->barang as $b)
                        <div class="barang-item mb-3">
                            <label class="form-label">Barang</label>
                            <div class="input-group mb-2">
                                <select name="barang_id[]" class="form-control barang-select" onchange="tampilkanStok(this)" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}" 
                                            data-stok="{{ $barang->stok }}"
                                            {{ $barang->id == $b->id ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger" onclick="hapusBarang(this)">✖</button>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Stok Saat Ini</label>
                                <input type="text" class="form-control stok-barang" value="{{ $b->stok }}" readonly>
                            </div>

                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control" value="{{ $b->pivot->jumlah }}" required min="1">
                        </div>
                        @endforeach
                    </div>
                    {{-- <button type="button" class="btn btn-outline-success mt-2" onclick="tambahBarang('barangContainerEdit{{ $permintaan->id }}')">+ Tambah Barang</button> --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Hapus -->
@foreach($permintaans as $permintaan)
<div class="modal fade" id="modalHapus{{ $permintaan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('user.permintaan.destroy', $permintaan->id) }}">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus permintaan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.permintaan.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="barangContainerCreate">
                        <div class="barang-item mb-3">
                            <label class="form-label">Barang</label>
                            <div class="input-group mb-2">
                                <select name="barang_id[]" class="form-control barang-select" onchange="tampilkanStok(this)" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger" onclick="hapusBarang(this)">✖</button>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Stok Saat Ini</label>
                                <input type="text" class="form-control stok-barang" value="" readonly>
                            </div>

                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control" required min="1">
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-success mt-2" onclick="tambahBarang('barangContainerCreate')">+ Tambah Barang</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Kirim Permintaan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelData').DataTable({
        order: [[4, 'asc']], // urut kolom tanggal
        language: {
            lengthMenu: "Menampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(disaring dari total _MAX_ data)",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: ">",
                previous: "<"
            }
        }
    });
});
</script>
</body>
</html>
