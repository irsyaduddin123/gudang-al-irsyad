<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\Permintaan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\PermintaanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PermintaanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Permintaan::with('pengguna', 'barang')
            ->orderByRaw("FIELD(status, 'butuh_validasi_manager', 'menunggu', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc');

        if (!in_array($user->role, ['manager', 'staff'])) {
            $query->where('pengguna_id', $user->id);
        }

        $permintaans = $query->get();

        return view('layouts.admin.permintaan.index', compact('permintaans'));
    }

    public function create()
    {
        $barangs = Barang::with('safetystok')->get();
        $penggunas = Pengguna::all();
        return view('layouts.admin.permintaan.tambah', compact('barangs', 'penggunas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $butuhValidasi = false;

        foreach ($request->barang_id as $index => $barangId) {
            $barang = Barang::with('safetyStok')->findOrFail($barangId);
            $jumlah = $request->jumlah[$index];
            $stokSekarang = $barang->stok;
            $minstok = $barang->safetyStok->minstok ?? 0;

            if (($stokSekarang - $jumlah) < $minstok) {
                $butuhValidasi = true;
            }
        }

        $permintaan = Permintaan::create([
            'pengguna_id' => $request->pengguna_id,
            'status' => $butuhValidasi ? 'butuh_validasi_manager' : 'menunggu',
        ]);

        $bulanSekarang = Carbon::now()->translatedFormat('F Y');

        foreach ($request->barang_id as $index => $barangId) {
            $permintaan->barang()->attach($barangId, [
                'jumlah' => $request->jumlah[$index],
                'bulan' => $bulanSekarang,
            ]);
        }

        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil ditambahkan!');
    }

    public function approve($id)
    {
        $permintaan = Permintaan::with('barang')->findOrFail($id);

        foreach ($permintaan->barang as $barang) {
            $jumlahDiminta = $barang->pivot->jumlah;

            if ($barang->stok < $jumlahDiminta) {
                return back()->with('error', "Stok barang {$barang->nama_barang} tidak mencukupi.");
            }

            $barang->stok -= $jumlahDiminta;
            $barang->save();

            BarangKeluar::create([
                'permintaan_id' => $permintaan->id,
                'barang_id' => $barang->id,
                'jumlah' => $jumlahDiminta,
                'tanggal_keluar' => now()->toDateString(),
            ]);
        }

        $permintaan->update(['status' => 'disetujui']);

        return back()->with('success', 'Permintaan disetujui dan stok dikurangi.');
    }

    public function reject($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update(['status' => 'ditolak']);

        return back()->with('success', 'Permintaan ditolak.');
    }

    public function validasiSetujui($id)
    {
        $user = auth()->user();
        if ($user->role !== 'manager') {
            abort(403, 'Hanya manajer yang boleh menyetujui.');
        }

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update(['status' => 'menunggu']); // bisa langsung disetujui jika ingin

        return back()->with('success', 'Permintaan berhasil divalidasi oleh manajer.');
    }

    public function validasiTolak($id)
    {
        $user = auth()->user();
        if ($user->role !== 'manager') {
            abort(403, 'Hanya manajer yang boleh menolak.');
        }

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update(['status' => 'ditolak']);

        return back()->with('success', 'Permintaan ditolak oleh manajer.');
    }

    public function exportExcel()
    {
        return Excel::download(new PermintaanExport, 'permintaan.xlsx');
    }

    public function exportPdf()
    {
        $permintaans = Permintaan::with('pengguna', 'barang')->get();
        $pdf = PDF::loadView('layouts.admin.permintaan.export-pdf', compact('permintaans'));
        return $pdf->download('permintaan.pdf');
    }
}
