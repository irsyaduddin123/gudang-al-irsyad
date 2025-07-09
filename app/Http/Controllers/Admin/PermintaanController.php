<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\Permintaan;
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
        ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
        ->orderBy('created_at', 'desc');

    // Jika bukan Manager atau Staff, filter berdasarkan ID user
    if (!in_array($user->role, ['manager', 'staff'])) {
        $query->where('pengguna_id', $user->id); // Ganti 'user_id' sesuai kolom relasi pengguna
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

        // Validasi stok dan min stok
        foreach ($request->barang_id as $index => $barangId) {
            $barang = Barang::with('safetyStok')->findOrFail($barangId);
            $jumlah = $request->jumlah[$index];
            $stokSekarang = $barang->stok;
            $minstok = $barang->safetyStok->minstok ?? 0;

            if (($stokSekarang - $jumlah) <= $minstok) {
                return back()->with('error', "Permintaan untuk '{$barang->nama_barang}' melebihi batas minimum stok. 
                    Sisa setelah permintaan = ".($stokSekarang - $jumlah).", Min Stok = $minstok.");
            }
        }

        // Simpan permintaan
        $permintaan = Permintaan::create([
            'pengguna_id' => $request->pengguna_id,
            'status' => 'menunggu',
            // 'bulan' => Carbon::now()->translatedFormat('F Y'), // simpan dalam angka (01-12)
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

    public function show(string $id)
    {
        $permintaan = Permintaan::with('pengguna', 'barang')->findOrFail($id);
        return view('layouts.admin.permintaan.show', compact('permintaan'));
    }

    public function edit(string $id)
    {
        $permintaan = Permintaan::with('barang')->findOrFail($id);
        $barangs = Barang::all();
        $penggunas = Pengguna::all();
        return view('layouts.admin.permintaan.edit', compact('permintaan', 'barangs', 'penggunas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update([
            'pengguna_id' => $request->pengguna_id,
            'status' => $request->status,
        ]);

        $dataBarang = [];
        foreach ($request->barang_id as $index => $barangId) {
            $dataBarang[$barangId] = ['jumlah' => $request->jumlah[$index]];
        }
        $permintaan->barang()->sync($dataBarang);

        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->barang()->detach();
        $permintaan->delete();

        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dihapus.');
    }

    public function approve($id)
    {
        // $permintaan = Permintaan::findOrFail($id);
        // $permintaan->update(['status' => 'disetujui']);

        // return back()->with('success', 'Permintaan disetujui.');
        $permintaan = Permintaan::with('barang')->findOrFail($id);

        // Kurangi stok barang
        foreach ($permintaan->barang as $barang) {
            $jumlahDiminta = $barang->pivot->jumlah;

            // Cek stok cukup
            if ($barang->stok < $jumlahDiminta) {
                return back()->with('error', "Stok barang {$barang->nama_barang} tidak mencukupi.");
            }

            // Kurangi stok
            $barang->stok -= $jumlahDiminta;
            $barang->save();
        }

        // Update status permintaan
        $permintaan->update(['status' => 'disetujui']);

        return back()->with('success', 'Permintaan disetujui dan stok berhasil dikurangi.');
    }

    public function reject($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update(['status' => 'ditolak']);

        return back()->with('success', 'Permintaan ditolak.');
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
