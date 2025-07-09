<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RopEoq;
use DB;
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

    // Barang menipis berdasarkan nilai ROP
    $barangMenipis = Barang::with('ropEoq')->get()->filter(function ($barang) {
        return $barang->ropEoq && $barang->stok <= $barang->ropEoq->rop;
    });
    // Barang menipis berdasarkan nilai Safety Stock
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

    //grafik 
    $ropEoqChartData = RopEoq::with('barang')
    ->select('barang_id', 'bulan', 'rop', 'eoq')
    ->get()
    ->groupBy('barang.nama_barang'); // Group by nama barang

    $bulanLabels = RopEoq::select('bulan')
    ->distinct()
    ->orderByRaw("STR_TO_DATE(bulan, '%M %Y') ASC")
    ->pluck('bulan');

    //filter drop down
    $daftarBarang = RopEoq::with('barang')
    ->get()
    ->pluck('barang.nama_barang')
    ->unique()
    ->values();



    return view('layouts.admin.dashboard.index', compact(
        'jumlahPengguna',
        'permintaanBelumDisetujui',
        'jumlahBarang',
        'jumlahPermintaan',
        'grafikData',
        'barangList',
        'barangMinStok',
        'barangMenipis',
        'ropEoqData',
        'ropEoqChartData',
        'bulanLabels',
        'daftarBarang'
    ));
}
}
