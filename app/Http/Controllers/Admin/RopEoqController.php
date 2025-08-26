<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RopEoqExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\RopEoq;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CarbonCarbon; 
use Maatwebsite\Excel\Facades\Excel;// 

class RopEoqController extends Controller
{
    public function index()
    {
        $ropEoq = RopEoq::with('barang')->get();

        $groupedData = RopEoq::select(
                'bulan',
                DB::raw('AVG(rop) as avg_rop'),
                DB::raw('AVG(eoq) as avg_eoq')
            )
            ->groupBy('bulan')
            ->orderByRaw("STR_TO_DATE(bulan, '%M %Y') ASC") // urut berdasarkan bulan
            ->get();

        return view('layouts.admin.hasilRopEoq.index', compact('ropEoq', 'groupedData'));
    }


    public function create()
    {
        $barangs = Barang::all();

        // Ambil lead time terakhir per barang
        $leadTimes = \App\Models\BarangMasuk::whereNotNull('tanggal_diterima')
            ->whereHas('pengadaan', function ($query) {
                $query->whereNotNull('tanggal_pengadaan');
            })
            ->get()
            ->groupBy('barang_id')
            ->map(function ($items) {
                // Ambil barang_masuk terakhir untuk setiap barang
                return $items->last(function ($item) {
                    return $item->tanggal_diterima && optional($item->pengadaan)->tanggal_pengadaan;
                });
            })
            ->mapWithKeys(function ($item, $barangId) {
                $leadTime = Carbon::parse($item->pengadaan->tanggal_pengadaan)->diffInDays($item->tanggal_diterima);
                return [$barangId => $leadTime];
            });

        return view('layouts.admin.hasilRopEoq.tambah', compact('barangs', 'leadTimes'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'barang_id' => 'required|exists:barangs,id',
    //         'biaya_simpan' => 'required|numeric|min:1',
    //         'lead_time' => 'required|numeric|min:1',

    //     ]);

    //     $barang = Barang::with('safetystok')->findOrFail($request->barang_id);

    //     $lead_time = $request->lead_time;
    //     $biaya_pesan = $barang->harga_beli ?? 5000;
    //     $biaya_simpan = $request->biaya_simpan;

    //     $bulanSekarang = Carbon::now()->translatedFormat('F Y'); // Contoh: "2025-06"


    //     // Hitung total jumlah permintaan dari tabel pivot
    //     $totalPermintaan = DB::table('barang_permintaan')
    //         ->where('barang_id', $barang->id)
    //         ->where('bulan', $bulanSekarang)
    //         ->sum('jumlah');

    //     // Hitung jumlah hari unik berdasarkan tanggal permintaan
    //     $jumlahHari = DB::table('barang_permintaan')
    //         ->where('barang_id', $barang->id)
    //         ->where('bulan', $bulanSekarang)
    //         ->select(DB::raw('DATE(created_at) as tanggal'))
    //         ->distinct()
    //         ->count();

        

    //     // Pemakaian rata-rata per bulan
    //     $pemakaian_rata = $jumlahHari > 0 ? ($totalPermintaan / $jumlahHari) : 1;

    //     //eoq
    //     // pemakian rata-rata kali periode
    //     $pemakaian_rata_per = $pemakaian_rata * 30;
    //     // Hitung biaya pesan per periode
    //     $biaya_per = $biaya_simpan*30;

    //     // Ambil safety stok dari relasi
    //     $safety_stok = $barang->safetystok->minstok ?? 0;

    //     // Hitung ROP dan EOQ
    //     $rop = round($lead_time * $pemakaian_rata) + $safety_stok;
    //     $eoq = round(sqrt(num: (2 * $biaya_pesan * $pemakaian_rata_per) / $biaya_per));

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
    //     RopEoq::create([
    //         'barang_id' => $barang->id,
    //         'lead_time' => $lead_time,
    //         'pemakaian_rata' => $pemakaian_rata,
    //         'biaya_pesan' => $biaya_pesan,
    //         'biaya_simpan' => $biaya_simpan,
    //         'rop' => $rop,
    //         'eoq' => $eoq,
    //         'total' => $totalPermintaan,
    //         'hari' => $jumlahHari,
    //         'safety_stok' => $safety_stok,
    //         'bulan' => $bulanSekarang
    //     ]);

    //     return redirect()->route('rop-eoq.index')->with('success', 'Perhitungan ROP & EOQ berhasil disimpan!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'biaya_simpan' => 'required|numeric|min:1',
            'lead_time' => 'required|numeric|min:1',
            'periode' => 'required|date_format:Y-m',
        ]);

        $barang = Barang::with('safetystok')->findOrFail($request->barang_id);

        $lead_time = $request->lead_time;
        $biaya_pesan = $barang->harga_beli ?? 5000;
        $biaya_simpan = $request->biaya_simpan;

        $periode = $request->periode; // input: "2025-07"
        $start = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();
        $bulanSekarang = $start->translatedFormat('F Y'); // contoh: "Juli 2025"

        // Cek apakah sudah pernah dihitung
        $existing = RopEoq::where('barang_id', $barang->id)
            ->where('bulan', $bulanSekarang)
            ->first();

        if ($existing) {
            return back()->with('error', 'Perhitungan ROP & EOQ untuk barang dan periode ini sudah pernah dilakukan.');
        }

        // Hitung total jumlah permintaan dari pivot
        $totalPermintaan = DB::table('barang_permintaan')
        ->join('permintaan', 'barang_permintaan.permintaan_id', '=', 'permintaan.id')
        ->where('barang_permintaan.barang_id', $barang->id)
        ->where('permintaan.status', 'disetujui')
        ->whereBetween('barang_permintaan.created_at', [$start, $end])
        ->sum('barang_permintaan.jumlah');

            if ($totalPermintaan == 0) {
            return back()->with('error', 'Tidak ada permintaan barang pada periode yang dipilih. Perhitungan tidak dapat dilakukan.');
        }
        // Hitung jumlah hari unik permintaan
        $jumlahHari = DB::table('barang_permintaan')
        ->join('permintaan', 'barang_permintaan.permintaan_id', '=', 'permintaan.id')
        ->where('barang_permintaan.barang_id', $barang->id)
        ->where('permintaan.status', 'disetujui')
        ->whereBetween('barang_permintaan.created_at', [$start, $end])
        ->select(DB::raw('DATE(barang_permintaan.created_at) as tanggal'))
        ->distinct()
        ->count();


        // Pemakaian rata-rata per hari
        $pemakaian_rata = $jumlahHari > 0 ? ($totalPermintaan / $jumlahHari) : 1;

        // Per bulan
        $pemakaian_rata_per = $pemakaian_rata * 30;
        $biaya_per = $biaya_simpan * 30;
        $safety_stok = $barang->safetystok->minstok ?? 0;

        // Perhitungan
        $rop = round($lead_time * $pemakaian_rata) + $safety_stok;
        $eoq = round(sqrt((2 * $biaya_pesan * $pemakaian_rata_per) / $biaya_per));

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
            'bulan' => $bulanSekarang // Simpan dengan format "Juli 2025"
        ]);

        return redirect()->route('rop-eoq.index')->with('success', 'Perhitungan ROP & EOQ berhasil disimpan!');
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'biaya_simpan' => 'required|numeric|min:1',
            'lead_time' => 'required|numeric|min:1',
            'periode' => 'required|date_format:Y-m',
        ]);

        $ropEoq = RopEoq::findOrFail($id);
        $barang = Barang::with('safetystok')->findOrFail($request->barang_id);

        $lead_time = $request->lead_time;
        $biaya_pesan = $barang->harga_beli ?? 5000;
        $biaya_simpan = $request->biaya_simpan;

        $periode = $request->periode; // format "2025-07"
        $start = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();
        $bulanSekarang = $start->translatedFormat('F Y'); // contoh: "Juli 2025"

        // Cek apakah ada data lain dengan barang dan bulan yang sama
        $cekDuplikat = RopEoq::where('barang_id', $barang->id)
            ->where('bulan', $bulanSekarang)
            ->where('id', '!=', $id)
            ->first();

        if ($cekDuplikat) {
            return back()->with('error', 'Data ROP & EOQ untuk barang "' . $barang->nama_barang . '" pada bulan ' . $bulanSekarang . ' sudah ada.');
        }

        // Hitung total permintaan
        $totalPermintaan = DB::table('barang_permintaan')
            ->where('barang_id', $barang->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('jumlah');

        if ($totalPermintaan == 0) {
            return back()->with('error', 'Tidak ada permintaan barang "' . $barang->nama_barang . '" pada periode ' . $bulanSekarang . '. Perhitungan tidak dapat dilakukan.');
        }

        // Hitung jumlah hari unik permintaan
        $jumlahHari = DB::table('barang_permintaan')
            ->where('barang_id', $barang->id)
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as tanggal'))
            ->distinct()
            ->count();

        $pemakaian_rata = $jumlahHari > 0 ? ($totalPermintaan / $jumlahHari) : 1;
        $pemakaian_rata_per = $pemakaian_rata * 30;
        $biaya_per = $biaya_simpan * 30;
        $safety_stok = $barang->safetystok->minstok ?? 0;

        $rop = round($lead_time * $pemakaian_rata) + $safety_stok;
        $eoq = round(sqrt((2 * $biaya_pesan * $pemakaian_rata_per) / $biaya_per));

        // Update ke database
        $ropEoq->update([
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

        return redirect()->route('rop-eoq.index')->with('success', 'Perhitungan ulang ROP & EOQ untuk "' . $barang->nama_barang . '" pada bulan ' . $bulanSekarang . ' berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $data = RopEoq::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data ROP & EOQ berhasil dihapus.');
    }


    public function exportExcel()
    {
        return Excel::download(new RopEoqExport, 'RopEoq.xlsx');
    }
    public function exportPDF()
    {
        $ropEoq = RopEoq::with('barang')->orderBy('created_at', 'desc')->get();
        $tanggal = now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('layouts.admin.hasilRopEoq.pdf', compact('ropEoq', 'tanggal'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_rop_eoq.pdf');
    }


}
