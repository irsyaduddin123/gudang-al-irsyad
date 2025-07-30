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
    <div class="col-lg-3 col-6 mb-4">
        <div class="small-box bg-info h-100 d-flex flex-column justify-content-between">
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
    <div class="col-lg-3 col-6 mb-4">
        <div class="small-box bg-warning h-100 d-flex flex-column justify-content-between">
            <div class="inner">
                <h3>{{ $permintaanBelumDisetujui }}</h3>
                <p>Permintaan Menunggu</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col-lg-3 col-6 mb-4">
        <div class="small-box bg-success h-100 d-flex flex-column justify-content-between">
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
    <div class="col-lg-3 col-6 mb-4">
        <div class="small-box bg-danger h-100 d-flex flex-column justify-content-between">
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


   <div class="row">
    <!-- Grafik Permintaan Barang -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5>Grafik Permintaan Barang</h5>
                <form method="GET" id="filterForm" class="mb-3">
                    <div class="row">
                        <div class="col-md-12">
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
                    </div>
                </form>
                <canvas id="permintaanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik ROP & EOQ -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="mb-3">Grafik ROP & EOQ</h5>
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-auto">
                        <label for="filterBarang" class="col-form-label fw-semibold">Pilih Barang:</label>
                    </div>
                    <div class="col">
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
    </div>
</div>

    <!-- Tabel Barang -->
    <div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">📋 Daftar Barang</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Safety Stok</th>
                        <th>ROP</th>
                        <th>EOQ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangList as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->safetyStok->minstok ?? '-' }}</td>
                            <td>
                                {{ $barang->ropEoq->rop ?? '❌ Belum dihitung' }}
                            </td>
                            <td>
                                {{ $barang->ropEoq->eoq ?? '❌ Belum dihitung' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data barang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>
@endsection

@section('scripts')

<style>
    .table td, .table th {
    vertical-align: middle;
    font-size: 14px;
}

.table thead th {
    background-color: #343a40;
    color: #fff;
}

    #permintaanChart,
    #ropEoqChart {
        width: 100% !important;
        height: 300px !important;
    }

    @media (max-width: 768px) {
        #permintaanChart,
        #ropEoqChart {
            height: 250px !important;
        }
    }
    datasets.push({
    label: 'ROP – ' + namaBarang,
    data: ropData,
    borderColor: 'rgba(255, 99, 132, 1)',
    backgroundColor: 'rgba(255, 99, 132, 0.1)',
    fill: false,
    tension: 0.4
});
datasets.push({
    label: 'EOQ – ' + namaBarang,
    data: eoqData,
    borderColor: 'rgba(54, 162, 235, 1)',
    backgroundColor: 'rgba(54, 162, 235, 0.1)',
    borderDash: [6, 3],
    fill: false,
    tension: 0.4
});

</style>


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
