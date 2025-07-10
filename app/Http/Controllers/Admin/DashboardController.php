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
   public function index(Request $request)
{
    $barangMenipis = Barang::with('ropEoq')->get()->filter(function ($barang) {
        return $barang->ropEoq && $barang->stok <= $barang->ropEoq->rop;
    });

    $barangMinStok = Barang::with('safetyStok')->get()->filter(function ($barang) {
        return $barang->safetyStok && $barang->stok <= $barang->safetyStok->minstok;
    });

    $jumlahPengguna = Pengguna::count();
    $permintaanBelumDisetujui = Permintaan::where('status', 'menunggu')->count();
    $jumlahBarang = Barang::count();
    $jumlahPermintaan = Permintaan::count();
    $barangTerbanyak = DB::table('barang_permintaan')
    ->join('barangs', 'barang_permintaan.barang_id', '=', 'barangs.id')
    ->select('barangs.nama_barang', DB::raw('SUM(barang_permintaan.jumlah) as total'))
    ->groupBy('barang_permintaan.barang_id', 'barangs.nama_barang')
    ->orderByDesc('total')
    ->first();

    // === Filter grafik jumlah permintaan per barang ===
    $bulanFilter = $request->bulan;
    $tahunFilter = $request->tahun;

    $grafikData = DB::table('barang_permintaan')
        ->join('barangs', 'barang_permintaan.barang_id', '=', 'barangs.id')
        ->select('barangs.nama_barang as nama', DB::raw('SUM(barang_permintaan.jumlah) as jumlah'))
        ->when($bulanFilter, function ($query) use ($bulanFilter) {
            $query->where('barang_permintaan.bulan', $bulanFilter); 
        })
        ->groupBy('barangs.nama_barang')
        ->get();

    //tahun
    // $grafikData = DB::table('barang_permintaan')
    // ->join('barangs', 'barang_permintaan.barang_id', '=', 'barangs.id')
    // ->select('barangs.nama_barang as nama', DB::raw('SUM(barang_permintaan.jumlah) as jumlah'))
    // ->when($bulanFilter, function ($query) use ($bulanFilter) {
    //     $query->where(DB::raw("MONTH(STR_TO_DATE(barang_permintaan.bulan, '%M %Y'))"), '=', Carbon::parse($bulanFilter)->month);
    // })
    // ->when($tahunFilter, function ($query) use ($tahunFilter) {
    //     $query->where(DB::raw("YEAR(STR_TO_DATE(barang_permintaan.bulan, '%M %Y'))"), '=', $tahunFilter);
    // })
    // ->groupBy('barangs.nama_barang')
    // ->get();

    $daftarBulan = DB::table('barang_permintaan')
        ->select('bulan')
        ->distinct()
        ->pluck('bulan')
        ->sortBy(function ($item) {
            return Carbon::createFromFormat('F Y', $item)->month; // urutkan berdasarkan bulan
        })
        ->values();

        // $daftarTahun = DB::table('barang_permintaan')
        // ->select(DB::raw("bulan as tahun, (STR_TO_DATE(bulan, '%M %Y')) as tahun"))
        // ->distinct()
        // ->pluck('tahun')
        // ->sort()
        // ->values();

    $barangList = Barang::with(['safetyStok', 'ropEoq'])->get();
    $ropEoqData = RopEoq::with('barang')->get();

    $ropEoqChartData = RopEoq::with('barang')
        ->select('barang_id', 'bulan', 'rop', 'eoq')
        ->get()
        ->groupBy('barang.nama_barang');

    $bulanLabels = RopEoq::select('bulan')
        ->distinct()
        ->orderByRaw("STR_TO_DATE(bulan, '%M %Y') ASC")
        ->pluck('bulan');

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
        'daftarBarang',
        'daftarBulan',
        'bulanFilter',
        'barangTerbanyak'
        // 'daftarTahun'
    ));
}

}
