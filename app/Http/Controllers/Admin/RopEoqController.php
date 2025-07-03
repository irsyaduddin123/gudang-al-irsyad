<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RopEoqExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\RopEoq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Maatwebsite\Excel\Facades\Excel;// 

class RopEoqController extends Controller
{
    public function index()
    {
        $ropEoq = RopEoq::with('barang')->get();
        return view('layouts.admin.hasilRopEoq.index', compact('ropEoq'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('layouts.admin.hasilRopEoq.tambah', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'biaya_simpan' => 'required|numeric|min:1',
            'lead_time' => 'required|numeric|min:1',

        ]);

        $barang = Barang::with('safetystok')->findOrFail($request->barang_id);

        $lead_time = $request->lead_time;
        $biaya_pesan = $barang->harga_beli ?? 5000;
        $biaya_simpan = $request->biaya_simpan;

        $bulanSekarang = Carbon::now()->translatedFormat('F Y'); // Contoh: "2025-06"


        // Hitung total jumlah permintaan dari tabel pivot
        $totalPermintaan = DB::table('barang_permintaan')
            ->where('barang_id', $barang->id)
            ->where('bulan', $bulanSekarang)
            ->sum('jumlah');

        // Hitung jumlah hari unik berdasarkan tanggal permintaan
        $jumlahHari = DB::table('barang_permintaan')
            ->where('barang_id', $barang->id)
            ->where('bulan', $bulanSekarang)
            ->select(DB::raw('DATE(created_at) as tanggal'))
            ->distinct()
            ->count();

        // Hitung biaya pesan per periode
        $biaya_per = $biaya_simpan*30;

        // Pemakaian rata-rata per bulan
        $pemakaian_rata = $jumlahHari > 0 ? ($totalPermintaan / 30) : 1;

        // pemakian rata-rata kali periode
        $pemakaian_rata_per = $pemakaian_rata * 30;

        // Ambil safety stok dari relasi
        $safety_stok = $barang->safetystok->minstok ?? 0;

        // Hitung ROP dan EOQ
        $rop = ($lead_time * $pemakaian_rata) + $safety_stok;
        $eoq = sqrt(num: (2 * $biaya_pesan * $pemakaian_rata_per) / $biaya_per);

    //     dd([
    //         'barang' => $barang->nama_barang,
    //         'bulan_saat_ini' => $bulanSekarang,
    //     'total' => $totalPermintaan,
    //     'hari' => $jumlahHari,
    //     'rata pemakaian' => $pemakaian_rata,
    //     'lead_time' => $lead_time,
    //     'safety_stok' => $safety_stok,
    //     'biaya_pesan' => $biaya_pesan,
    //     'biaya_per' => $biaya_per,
    //     'pemakaian_rata_per' => $pemakaian_rata_per,
    //     'biaya_simpan' => $biaya_per,
    //     'rop' => $rop,
    //     'eoq' => $eoq,
        
    //     'perhitungan'=> sqrt((2 * $biaya_pesan * $pemakaian_rata_per) / $biaya_per)
    // ]);

        // Simpan ke database
        RopEoq::create([
            'barang_id' => $barang->id,
            'lead_time' => $lead_time,
            'pemakaian_rata' => $pemakaian_rata,
            'biaya_pesan' => $biaya_pesan,
            'biaya_simpan' => $biaya_simpan,
            'rop' => $rop,
            'eoq' => $eoq,
            'total' => $totalPermintaan,
            'hari' => $jumlahHari,
            'safety_stok' => $safety_stok,
            'bulan' => $bulanSekarang
        ]);

        return redirect()->route('rop-eoq.index')->with('success', 'Perhitungan ROP & EOQ berhasil disimpan!');
    }
    public function exportExcel()
    {
        return Excel::download(new RopEoqExport, 'RopEoq.xlsx');
    }

}
