{{-- @extends('layouts.main')

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

@if($barangMinStok->count())
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Peringatan! Barang Mencapai Safety Stok</h5>
        <ul class="mb-0">
            @foreach($barangMinStok as $barang)
                <li>
                    <strong>{{ $barang->nama_barang }}</strong> — 
                    Stok sekarang: <b>{{ optional($barang->safetyStok)->jumlah_safety }}</b>
                </li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <div class="row mb-4">
        <!-- Card 1 -->
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Jumlah Pengguna</h5>
                    <h2>{{ $jumlahPengguna }}</h2>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Permintaan menunggu </h5>
                    <h2>{{ $permintaanBelumDisetujui }}</h2>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Jumlah Barang</h5>
                    <h2>{{ $jumlahBarang }}</h2>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5>Total Permintaan</h5>
                    <h2>{{ $jumlahPermintaan }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Grafik Permintaan Barang (Bulan Ini)</h5>
            <canvas id="permintaanChart"></canvas>
        </div>
    </div>

    <!-- Grafik ROP & EOQ -->
<div class="card mb-4">
    <div class="card-body">
        <h5>Grafik ROP & EOQ</h5>
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
<script>
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
</script>
@endsection --}}

@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Dashboard Manager</h1>
    </div>
    <div class="col-sm-6 text-right">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Total Barang -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $jumlahBarang }}</h3>
                    <p>Jumlah Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <!-- Total Permintaan -->
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

        <!-- Barang Stok Menipis -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $barangMenipis }}</h3>
                    <p>Stok Menipis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <!-- Total Perhitungan ROP/EOQ -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $jumlahRopEoq }}</h3>
                    <p>Data ROP & EOQ</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Bisa ditambah grafik jika dibutuhkan --}}
</div>
@endsection
