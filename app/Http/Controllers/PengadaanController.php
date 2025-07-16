<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pengadaan;
use App\Models\supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengadaan::with(['barang', 'supplier']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pengadaans = $query->latest()->get();

        return view('pengadaan.index', compact('pengadaans'));
    }

    public function create(Barang $barang)
    {
        // ambil supplier yang menyediakan barang ini
        $suppliers = supplier::where('nama_barang', $barang->nama_barang)->get();

        
        return view('pengadaan.create', compact('barang', 'suppliers'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pengadaan' => 'required|date',
            'jumlah' => 'required|integer|min:1',
        ]);
        // dd($request->all());

        Pengadaan::create([
            'barang_id' => $request->barang_id,
            'supplier_id' => $request->supplier_id,
            'tanggal_pengadaan' => $request->tanggal_pengadaan,
            'jumlah' => $request->jumlah,
            'status' => 'menunggu',
        ]);

        return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil disimpan.');
    }

        // Tampilkan form ubah status
    public function editStatus($id)
    {
        $pengadaan = Pengadaan::with('barang')->findOrFail($id);

        // Hanya untuk role manager / admin
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            abort(403, 'Akses ditolak');
        }

        return view('pengadaan.edit-status', compact('pengadaan'));
    }

    // Proses update status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
        ]);

        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status = $request->status;
        $pengadaan->save();

        return redirect()->route('pengadaan.index')->with('success', 'Status pengadaan berhasil diperbarui.');
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
            'jumlah' => $pengadaan->jumlah,
            'tanggal_masuk' => now(),
            'keterangan' => 'Disetujui dari pengadaan ID ' . $pengadaan->id,
            'pengadaan_id' => $pengadaan->id, // opsional, kalau ada kolom ini
        ]);

        return back()->with('success', 'Pengadaan disetujui dan dicatat ke Barang Masuk.');
    }

    // public function reject($id)
    // {
    //     $pengadaan = Pengadaan::findOrFail($id);

    //     if ($pengadaan->status !== 'menunggu') {
    //         return back()->with('success', 'Pengadaan sudah diproses.');
    //     }

    //     $pengadaan->status = 'ditolak';
    //     $pengadaan->save();

    //     return back()->with('success', 'Pengadaan berhasil ditolak.');
    // }
    public function reject(Request $request, $id)
{
    $request->validate([
        'keterangan_penolakan' => 'required|string',
    ]);

    $pengadaan = Pengadaan::findOrFail($id);
    $pengadaan->status = 'ditolak';
    $pengadaan->keterangan_penolakan = $request->keterangan_penolakan;
    $pengadaan->save();

    return redirect()->back()->with('success', 'Pengadaan ditolak dengan keterangan.');
}


}

