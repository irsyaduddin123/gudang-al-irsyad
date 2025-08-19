<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermintaanUserController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();

        $permintaans = Permintaan::with('barang', 'pengguna')
            ->where('pengguna_id', Auth::id());

        // Filter bulan
        if ($request->filled('bulan')) {
            $permintaans->whereMonth('created_at', $request->bulan);
        }

        // filter tahun 
        if ($request->filled('tahun')) {
            $permintaans->whereYear('created_at', $request->tahun);
        }

        $permintaans = $permintaans->latest()->get();


        return view('user.permintaan.index', compact('permintaans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah'      => 'required|array',
            'jumlah.*'    => 'required|numeric|min:1',
        ]);

        $bulanSekarang = Carbon::now()->translatedFormat('F Y');

        foreach ($request->barang_id as $i => $barangId) {
            $barang = Barang::with('safetyStok')->findOrFail($barangId);
            $stokSekarang = $barang->stok;
            $minStok = $barang->safetyStok->minstok ?? 0;
            $jumlah = $request->jumlah[$i];

            $butuhValidasi = ($stokSekarang - $jumlah) < $minStok;

            // Buat permintaan baru untuk setiap barang
            $permintaan = Permintaan::create([
                'pengguna_id' => Auth::id(),
                'status'      => $butuhValidasi ? 'butuh_validasi_manager' : 'menunggu',
            ]);

            $permintaan->barang()->attach($barangId, [
                'jumlah' => $jumlah,
                'bulan'  => $bulanSekarang,
            ]);
        }

        return redirect()->route('user.permintaan.index')->with('success', 'Permintaan berhasil ditambahkan.');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id'   => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah'      => 'required|array',
            'jumlah.*'    => 'required|numeric|min:1',
        ]);

        $permintaan = Permintaan::where('id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        $butuhValidasi = false;

        foreach ($request->barang_id as $i => $barangId) {
            $barang = Barang::with('safetyStok')->findOrFail($barangId);
            $stokSekarang = $barang->stok;
            $minStok = $barang->safetyStok->minstok ?? 0;
            $jumlah = $request->jumlah[$i];

            if (($stokSekarang - $jumlah) < $minStok) {
                $butuhValidasi = true;
            }
        }

        $bulanSekarang = Carbon::now()->translatedFormat('F Y');

        $syncData = [];
        foreach ($request->barang_id as $i => $barangId) {
            $syncData[$barangId] = [
                'jumlah' => $request->jumlah[$i],
                'bulan'  => $bulanSekarang,
            ];
        }

        $permintaan->barang()->sync($syncData);
        $permintaan->status = $butuhValidasi ? 'butuh_validasi_manager' : 'menunggu';
        $permintaan->save();

        return redirect()->route('user.permintaan.index')->with('success', 'Permintaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $permintaan = Permintaan::where('id', $id)
            ->where('pengguna_id', Auth::id())
            ->firstOrFail();

        $permintaan->barang()->detach();
        $permintaan->delete();

        return redirect()->route('user.permintaan.index')->with('success', 'Permintaan berhasil dihapus');
    }
}
