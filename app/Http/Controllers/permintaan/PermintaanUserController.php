<?php

namespace App\Http\Controllers\Permintaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Auth;

class PermintaanUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'permintaan') {
                abort(403, 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    // Tampilkan permintaan milik pengguna ini saja
    public function index()
    {
        $permintaans = Permintaan::with('barang')
            ->where('pengguna_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('layouts.permintaan_user.index', compact('permintaans'));
    }

    // Form tambah permintaan
    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('permintaan_user.create', compact('barangs'));
    }

    // Simpan permintaan baru
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        foreach ($request->barang_id as $i => $barangId) {
            $barang = Barang::with('safetyStok')->findOrFail($barangId);
            $jumlah = $request->jumlah[$i];
            $minstok = $barang->safetyStok->minstok ?? 0;

            if (($barang->stok - $jumlah) <= $minstok) {
                return back()->with('error', "Permintaan '{$barang->nama_barang}' melebihi batas minimum stok.");
            }
        }

        $permintaan = Permintaan::create([
            'pengguna_id' => Auth::id(),
            'status' => 'menunggu',
        ]);

        $bulan = Carbon::now()->translatedFormat('F Y');

        foreach ($request->barang_id as $i => $barangId) {
            $permintaan->barang()->attach($barangId, [
                'jumlah' => $request->jumlah[$i],
                'bulan' => $bulan,
            ]);
        }

        return redirect()->route('permintaan.user.index')->with('success', 'Permintaan berhasil dibuat.');
    }
}
