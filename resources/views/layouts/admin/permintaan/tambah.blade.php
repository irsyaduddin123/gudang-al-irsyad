@extends('layouts.main')

@section('header')
    <h1>Tambah Permintaan</h1>
@endsection

@section('content')
<div class="container">

    {{-- Pesan Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Pesan Sukses (optional) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('permintaan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Pengguna</label>

            @php
                $user = auth()->user();
            @endphp
            @if($user->role === 'permintaan')
                <input type="hidden" name="pengguna_id" value="{{ $user->id }}">
                <input type="text" class="form-control" value="{{ $user->nama }} ({{ $user->bagian }})" readonly>
            @else
            <select name="pengguna_id" class="form-control" required>
                <option disabled selected>-- Pilih Pengguna --</option>
                @foreach($penggunas as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->Bagian }})</option>
                @endforeach
            </select>
            @endif
        </div>

        <div id="barang-wrapper">
            <div class="form-group">
                <label>Barang</label>
                <select name="barang_id[]" class="form-control" required onchange="updateInfo(this)">
                    <option disabled selected>-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" 
                            data-stok="{{ $b->stok }}"
                            data-safety="{{ $b->safetystok->minstok }}">
                            {{ $b->nama_barang }} ({{ $b->satuan }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted stok-info">Stok: - | Min Stok: -</small>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah[]" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function updateInfo(selectElement) {
        const stok = selectElement.selectedOptions[0].getAttribute('data-stok');
        const safety = selectElement.selectedOptions[0].getAttribute('data-safety');
        const info = selectElement.closest('.form-group').querySelector('.stok-info');
        info.innerText = `Stok: ${stok} | Min Stok: ${safety}`;
    }
</script>
@endsection
