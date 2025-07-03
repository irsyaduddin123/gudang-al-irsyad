<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RopEoq;
use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Pengguna;
use App\Models\Barang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
{
    $barangMinStok = Barang::with('safetyStok')->get()->filter(function ($barang) {
    return $barang->safetyStok && $barang->stok <= $barang->safetyStok->minstok;
});

    $jumlahPengguna = Pengguna::count();
    $permintaanBelumDisetujui = Permintaan::where('status', 'menunggu')->count();
    $jumlahBarang = Barang::count();
    $jumlahPermintaan = Permintaan::count();

    // Grafik: jumlah permintaan per barang di bulan ini
    
    $bulanIni = Carbon::now()->month;
    $permintaanBarang = Barang::with(['permintaan' => function ($query) use ($bulanIni) {
        $query->whereMonth('permintaan.created_at', $bulanIni);
    }])->get();

    $grafikData = $permintaanBarang->map(function ($barang) {
    $total = 0;
    foreach ($barang->permintaan as $permintaan) {
        $total += $permintaan->pivot->jumlah ?? 0;
    }
    return [
        'nama' => $barang->nama_barang,
        'jumlah' => $total
    ];
});

    $barangList = Barang::with(['safetyStok', 'ropEoq'])->get();
    $ropEoqData = RopEoq::with('barang')->get();



    return view('layouts.admin.dashboard.index', compact(
        'jumlahPengguna',
        'permintaanBelumDisetujui',
        'jumlahBarang',
        'jumlahPermintaan',
        'grafikData',
        'barangList',
        'barangMinStok',
        'ropEoqData'
    ));
}
}
