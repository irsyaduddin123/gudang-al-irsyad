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
<div class="container-fluid">

    {{-- Notifikasi --}}
    <div class="row">
        @if($barangMenipis->count())
        <div class="col-md-6 mb-3">
            <div class="card border-danger h-100">
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
        </div>
        @endif

        @if($barangMinStok->count())
        <div class="col-md-6 mb-3">
            <div class="card border-warning h-100">
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
        </div>
        @endif
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h5 class="fw-bold text-white">
                        {{ \Illuminate\Support\Str::limit($barangTerbanyak->nama_barang ?? 'Tidak Ada', 25) }}
                    </h5>
                    <p>Barang Paling Sering Diminta</p>
                </div>
                <div class="icon"><i class="fas fa-star"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $permintaanBelumDisetujui }}</h3>
                    <p>Permintaan Menunggu</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $jumlahPermintaan }}</h3>
                    <p>Total Permintaan</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barangMenipis->count() }}</h3>
                    <p>Stok Menipis</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Grafik Permintaan Barang</h5>
                    <form method="GET" id="filterForm" class="mb-3">
                        <label class="fw-semibold">Pilih Bulan:</label>
                        <select name="bulan" id="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Bulan --</option>
                            @foreach($daftarBulan as $bulan)
                                <option value="{{ $bulan }}" {{ $bulan == $bulanFilter ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('F Y', $bulan)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <canvas id="permintaanChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Grafik ROP & EOQ</h5>
                    <div class="row g-2 align-items-center mb-3">
                        <label class="col-auto fw-semibold">Pilih Barang:</label>
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

{{-- Tabel ROP & EOQ Per Bulan --}}@php
    // Kumpulkan semua bulan unik dari semua barang
    $semuaBulan = collect();
    foreach ($barangList as $barang) {
        foreach ($barang->ropEoqSemua as $rekap) {
            $semuaBulan->push($rekap->bulan);
        }
    }
    $semuaBulan = $semuaBulan->unique()->sortBy(function ($bulan) {
    return \Carbon\Carbon::createFromFormat('F Y', $bulan)->timestamp;
}); // sort dan hapus duplikat
@endphp

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">📅 Tabel ROP & EOQ Horizontal per Bulan</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th rowspan="2"  class="text-center align-middle">Nama Barang</th>
                        @foreach ($semuaBulan as $bulan)
                            <th colspan="2">{{ $bulan }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($semuaBulan as $bulan)
                            <th>ROP</th>
                            <th>EOQ</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangList as $barang)
                        <tr>
                            <td>{{ $barang->nama_barang }}</td>
                            @foreach ($semuaBulan as $bulan)
                                @php
                                    $data = $barang->ropEoqSemua->firstWhere('bulan', $bulan);
                                @endphp
                                <td>{{ $data->rop ?? '❌' }}</td>
                                <td>{{ $data->eoq ?? '❌' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>
@endsection

@section('scripts')
<style>
    .small-box {
        position: relative;
        padding: 20px;
        border-radius: 10px;
        color: #fff;
    }
    .small-box .icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 32px;
        opacity: 0.2;
    }
    #permintaanChart,
    #ropEoqChart {
        width: 100% !important;
        height: 300px !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('permintaanChart').getContext('2d');
    new Chart(ctx, {
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
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });

    const ctxRopEoq = document.getElementById('ropEoqChart').getContext('2d');
    const bulanLabels = {!! json_encode($bulanLabels) !!};
    const allData = @json($ropEoqChartData);
    let ropEoqChart;

    function buildDatasets(filter = 'semua') {
        const datasets = [];
        Object.entries(allData).forEach(([namaBarang, dataBarang]) => {
            if (filter !== 'semua' && namaBarang !== filter) return;
            const ropData = bulanLabels.map(b => dataBarang.find(d => d.bulan === b)?.rop || null);
            const eoqData = bulanLabels.map(b => dataBarang.find(d => d.bulan === b)?.eoq || null);
            datasets.push({
                label: 'ROP – ' + namaBarang,
                data: ropData,
                borderColor: 'rgba(255, 99, 132, 0.7)',
                fill: false,
                tension: 0.3
            });
            datasets.push({
                label: 'EOQ – ' + namaBarang,
                data: eoqData,
                borderColor: 'rgba(54, 162, 235, 0.7)',
                borderDash: [6, 4],
                fill: false,
                tension: 0.3
            });
        });
        return datasets;
    }

    function renderChart(filter = 'semua') {
        if (ropEoqChart) ropEoqChart.destroy();
        ropEoqChart = new Chart(ctxRopEoq, {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: buildDatasets(filter)
            },
            options: {
                responsive: true,
                interaction: { mode: 'nearest', intersect: false },
                scales: { y: { beginAtZero: true, precision: 0 } }
            }
        });
    }

    function updateChart() {
        renderChart(document.getElementById('filterBarang').value);
    }

    renderChart();
</script>
@endsection
