<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
<body style="
    background: url('{{ asset('images/bg.png') }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-color: #f8f9fa;">

<div class="container mt-5 bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Permintaan</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Tambah Permintaan</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover text-center align-middle">
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
                        <form action="{{ route('user.permintaan.destroy', $permintaan->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit{{ $permintaan->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('user.permintaan.update', $permintaan->id) }}">
                        @csrf @method('PUT')
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
                                            <select name="barang_id[]" class="form-control" required>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}" @if($barang->id == $b->id) selected @endif>
                                                        {{ $barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-danger" onclick="hapusBarang(this)">✖</button>
                                        </div>
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah[]" class="form-control" value="{{ $b->pivot->jumlah }}" required>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>
</div>

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
                            <input type="number" name="jumlah[]" class="form-control" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
