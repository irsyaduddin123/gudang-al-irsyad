<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use App\Exports\BarangMasukExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'pengadaan']);

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        if ($request->filled('tanggal_diterima')) {
            $query->whereDate('tanggal_diterima', $request->tanggal_diterima);
        }

        $barangMasuk = $query->latest()->paginate(10)->withQueryString();
        $barangs = \App\Models\Barang::all();

        return view('barang_masuk.index', compact('barangMasuk', 'barangs'));
    }


    public function terima($id)
    {
        $barangMasuk = BarangMasuk::with('barang')->findOrFail($id);

        if ($barangMasuk->tanggal_diterima) {
            return back()->with('success', 'Barang sudah diterima sebelumnya.');
        }

        // Tandai sebagai diterima dan update stok
        $barangMasuk->update(['tanggal_diterima' => now()]);
        $barangMasuk->barang->increment('stok', $barangMasuk->jumlah);

        return back()->with('success', 'Barang diterima dan stok diperbarui.');
    }
    public function approve($id)
    {
        $pengadaan = Pengadaan::with('barang')->findOrFail($id);

        if ($pengadaan->status !== 'menunggu') {
            return back()->with('success', 'Pengadaan sudah diproses.');
        }

        $pengadaan->status = 'disetujui';
        $pengadaan->save();

        BarangMasuk::create([
            'barang_id' => $pengadaan->barang_id,
            'pengadaan_id' => $pengadaan->id,
            'jumlah' => $pengadaan->jumlah,
            'tanggal_diterima' => null,
        ]);

        return back()->with('success', 'Pengadaan disetujui dan barang menunggu penerimaan.');
    }
    public function exportExcel()
{
    return Excel::download(new BarangMasukExport, 'barang_masuk.xlsx');
}

public function exportPDF()
{
    $barangMasuk = BarangMasuk::with('barang', 'pengadaan')->get();

    $pdf = Pdf::loadView('barang_masuk.export_pdf', compact('barangMasuk'));
    return $pdf->download('barang_masuk.pdf');
}
}
