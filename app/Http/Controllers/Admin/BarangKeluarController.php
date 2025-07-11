<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BarangKeluarExport;
use App\Http\Controllers\Controller;
use App\Models\barang;
use App\Models\BarangKeluar;
use App\Models\Permintaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BarangKeluarController extends Controller
{
 public function index(Request $request)
{
    $query = BarangKeluar::with('barang', 'permintaan')->orderBy('tanggal_keluar', 'desc');

    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('tanggal_keluar', [$request->tanggal_awal, $request->tanggal_akhir]);
    }

    if ($request->filled('barang_id')) {
        $query->where('barang_id', $request->barang_id);
    }

    // Filter berdasarkan semua permintaan dari 1 pengguna
    if ($request->filled('permintaan_id')) {
        $selectedPermintaan = Permintaan::find($request->permintaan_id);

        if ($selectedPermintaan) {
            $penggunaId = $selectedPermintaan->pengguna_id;
            $permintaanIds = Permintaan::where('pengguna_id', $penggunaId)->pluck('id');

            $query->whereIn('permintaan_id', $permintaanIds);
        }
    }

    $barang_keluar = $query->get();
    $barangs = barang::all();

    // Ambil hanya 1 permintaan per pengguna (untuk dropdown)
    $permintaans = Permintaan::with('pengguna')
        ->selectRaw('MIN(id) as id, pengguna_id')
        ->groupBy('pengguna_id')
        ->get();

    return view('layouts.admin.barang_keluar.index', compact('barang_keluar','barangs','permintaans'));
}

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredBarangKeluar($request);
        return Excel::download(new BarangKeluarExport($data), 'barang_keluar.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredBarangKeluar($request);
        $pdf = Pdf::loadView('layouts.admin.barang_keluar.export-pdf', ['barang_keluar' => $data]);
        return $pdf->download('barang_keluar.pdf');
    }

private function getFilteredBarangKeluar(Request $request)
{
    $query = BarangKeluar::with('barang', 'permintaan');

    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('tanggal_keluar', [$request->tanggal_awal, $request->tanggal_akhir]);
    }

    if ($request->filled('barang_id')) {
        $query->where('barang_id', $request->barang_id);
    }

    if ($request->filled('permintaan_id')) {
        $query->where('permintaan_id', $request->permintaan_id);
    }

    return $query->orderBy('tanggal_keluar', 'desc')->get();
}

}