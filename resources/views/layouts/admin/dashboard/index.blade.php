@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">beranda</a></li>
              <li class="breadcrumb-item active">dashboard</li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
{{-- Notifikasi Barang ≤ ROP --}}
@if($barangMenipis->count())
    <div class="card border-danger mb-3">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle"></i> Barang Mencapai atau Kurang dari ROP
            <span class="badge bg-light text-dark float-end">{{ $barangMenipis->count() }} Barang</span>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($barangMenipis as $barang)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $barang->nama_barang }}</strong><br>
                            Stok: <b>{{ $barang->stok }}</b>, ROP: <b>{{ $barang->ropEoq->rop ?? '-' }}</b>
                        </div>
                        <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Notifikasi Barang ≤ Safety Stock --}}
@if($barangMinStok->count())
    <div class="card border-warning mb-3">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-exclamation-circle"></i> Barang Di Bawah Safety Stock
            <span class="badge bg-light text-dark float-end">{{ $barangMinStok->count() }} Barang</span>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($barangMinStok as $barang)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $barang->nama_barang }}</strong><br>
                            Stok: <b>{{ $barang->stok }}</b>, Safety Stock: <b>{{ $barang->safetyStok->minstok ?? '-' }}</b>
                        </div>
                        <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Card Dashboard --}}
<div class="container">
    <div class="row">
        <!-- Card 1 -->
        <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h5 class="mb-1 fw-bold text-white">
                        {{ \Illuminate\Support\Str::limit($barangTerbanyak->nama_barang ?? 'Tidak Ada', 25) }}
                    </h5>
                <p>Barang Paling Sering Diminta</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
        <!-- Card 2 -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $permintaanBelumDisetujui}}</h3>
                    <p>Permintaan menunggu </p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $jumlahPermintaan }}</h3>
                    <p>Total Permintaan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barangMenipis->count() }}</h3>
                    <p>Stok Menipis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Grafik Permintaan Barang </h5>
            <form method="GET" id="filterForm" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold">Pilih Bulan:
                        <select name="bulan" id="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Bulan --</option>
                            @foreach($daftarBulan as $bulan)
                                <option value="{{ $bulan }}" {{ $bulan == $bulanFilter ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('F Y', $bulan)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        </label>
                    </div>
                </div>
            </form>
            {{-- <h6>Bulan: {{ $bulanFilter ? \Carbon\Carbon::createFromFormat('F Y', $bulanFilter)->translatedFormat('F') : 'Semua' }}</h6> --}}

            <canvas id="permintaanChart"></canvas>
        </div>
    </div>

    {{-- <div class="card mb-4">
        <div class="card-body">
            <h5>Grafik Permintaan Barang</h5>

            <form method="GET" id="filterForm" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <label class="col-form-label fw-semibold">Pilih Bulan:</label>
                        <select name="bulan" id="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Bulan --</option>
                            @foreach($daftarBulan as $bulan)
                                <option value="{{ $bulan }}" {{ $bulan == $bulanFilter ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('F Y', $bulan)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="col-form-label fw-semibold">Pilih Tahun:</label>
                        <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($daftarTahun as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == request('tahun') ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <canvas id="permintaanChart"></canvas>
        </div>
    </div> --}}

    <!-- Grafik ROP & EOQ -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Grafik ROP & EOQ</h5>

        {{-- Filter dropdown --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-auto">
                <label for="filterBarang" class="col-form-label fw-semibold">Pilih Barang:</label>
            </div>
            <div class="col-auto">
                <select id="filterBarang" class="form-select form-select-sm" onchange="updateChart()">
                    <option value="semua">Semua Barang</option>
                    @foreach ($daftarBarang as $namaBarang)
                        <option value="{{ $namaBarang }}">{{ $namaBarang }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <canvas id="ropEoqChart"></canvas>
    </div>
</div>

    <!-- Tabel Barang -->
    <div class="card">
        <div class="card-body">
            <h5>Daftar Barang</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Safety stok</th>
                        <th>ROP</th>
                        <th>EOQ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangList as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->safetyStok->minstok ?? 'minstok null' }}</td>
                            <td>{{ $barang->ropEoq->rop ?? 'Belum dilakukan perhitungan' }}</td>
                            <td>{{ $barang->ropEoq->eoq ?? 'Belum dilakukan perhitungan' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('permintaanChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($grafikData->pluck('nama')) !!},
            datasets: [{
                label: 'Jumlah Permintaan',
                data: {!! json_encode($grafikData->pluck('jumlah')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
{{-- <script>
    const ropEoqCtx = document.getElementById('ropEoqChart').getContext('2d');
    const ropEoqChart = new Chart(ropEoqCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($ropEoqData->pluck('barang.nama_barang')) !!},
            datasets: [
                {
                    label: 'ROP',
                    data: {!! json_encode($ropEoqData->pluck('rop')) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'EOQ',
                    data: {!! json_encode($ropEoqData->pluck('eoq')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script> --}}
{{-- <script>
    const ropEoqCtx = document.getElementById('ropEoqChart').getContext('2d');
    const ropEoqChart = new Chart(ropEoqCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($groupedData->pluck('bulan')) !!},
            datasets: [
                {
                    label: 'Rata-rata ROP',
                    data: {!! json_encode($groupedData->pluck('avg_rop')) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Rata-rata EOQ',
                    data: {!! json_encode($groupedData->pluck('avg_eoq')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script> --}}

{{-- ===== Grafik ROP & EOQ per Barang per Bulan ===== --}}
<script>
const ctxRopEoq = document.getElementById('ropEoqChart').getContext('2d');
const bulanLabels = {!! json_encode($bulanLabels) !!};
const allData     = @json($ropEoqChartData);   // { namaBarang: [ {bulan, rop, eoq}, … ] }

let ropEoqChart;   // chart instance

function buildDatasets(filter = 'semua') {
    const datasets = [];

    Object.entries(allData).forEach(([namaBarang, dataBarang]) => {
        if (filter !== 'semua' && namaBarang !== filter) return;

        // susun data sesuai urutan label bulan
        const ropData = bulanLabels.map(b => {
            const m = dataBarang.find(d => d.bulan === b);
            return m ? m.rop : null;
        });
        const eoqData = bulanLabels.map(b => {
            const m = dataBarang.find(d => d.bulan === b);
            return m ? m.eoq : null;
        });

        datasets.push({
            label: 'ROP – ' + namaBarang,
            data: ropData,
            borderColor: 'rgba(255, 99, 132, .7)',
            fill: false,
            tension: .3
        });
        datasets.push({
            label: 'EOQ – ' + namaBarang,
            data: eoqData,
            borderColor: 'rgba(54, 162, 235, .7)',
            borderDash: [6, 4],
            fill: false,
            tension: .3
        });
    });

    return datasets;
}

function renderChart(filter = 'semua') {
    if (ropEoqChart) ropEoqChart.destroy();          // hapus chart lama
    ropEoqChart = new Chart(ctxRopEoq, {
        type: 'line',
        data: { labels: bulanLabels, datasets: buildDatasets(filter) },
        options: {
            responsive: true,
            interaction: { mode: 'nearest', intersect: false },
            scales: { y: { beginAtZero: true, precision: 0 } }
        }
    });
}

function updateChart() {
    const selected = document.getElementById('filterBarang').value;
    renderChart(selected);
}

// render awal
renderChart();
</script>

@endsection
