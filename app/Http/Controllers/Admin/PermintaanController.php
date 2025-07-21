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
    // Method untuk mengambil data terfilter
    protected function getFilteredPermintaans(Request $request)
    {
        $user = auth()->user();
        $query = Permintaan::with('pengguna', 'barang')
            ->orderByRaw("FIELD(status, 'butuh_validasi_manager', 'menunggu', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc');

        // Filter tanggal permintaan: pastikan format tanggal sesuai (YYYY-MM-DD)
        if ($request->filled('tanggal_filter')) {
            $tanggal = Carbon::parse($request->tanggal_filter)->format('Y-m-d');
            $query->whereDate('created_at', $tanggal);
        }

        // Filter status, khususnya jika ingin menampilkan "disetujui"
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter bagian berdasarkan data pada pengguna
        if ($request->filled('bagian')) {
            $query->whereHas('pengguna', function ($q) use ($request) {
                $q->where('bagian', $request->bagian);
            });
        }

        //filter barang berdasarkan id
        if ($request->filled('barang_id')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('barangs.id', $request->barang_id);
            });
        }

        // Jika bukan manager atau staff, tampilkan hanya permintaan milik user sendiri
        if (!in_array($user->role, ['manager', 'staff'])) {
            $query->where('pengguna_id', $user->id);
        }

        return $query->get();
    }

    public function index(Request $request)
    {
        $permintaans = $this->getFilteredPermintaans($request);

        // Ambil data bagian untuk dropdown filter di view (hanya ambil nilai unik)
        $bagianList = Pengguna::select('bagian')->distinct()->get();
        $barangs = Barang::all();

        return view('layouts.admin.permintaan.index', compact('permintaans', 'bagianList','barangs'));
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
            'barang_id'   => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah'      => 'required|array',
            'jumlah.*'    => 'required|integer|min:1',
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
            'status'      => $butuhValidasi ? 'butuh_validasi_manager' : 'menunggu',
        ]);

        $bulanSekarang = Carbon::now()->translatedFormat('F Y');

        foreach ($request->barang_id as $index => $barangId) {
            $permintaan->barang()->attach($barangId, [
                'jumlah' => $request->jumlah[$index],
                'bulan'  => $bulanSekarang,
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
                'barang_id'     => $barang->id,
                'jumlah'        => $jumlahDiminta,
                'tanggal_keluar'=> now()->toDateString(),
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
        $permintaan->update(['status' => 'menunggu']);

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

    public function exportExcel(Request $request)
    {
        // Mengambil data permintaan sesuai filter
        $permintaans = $this->getFilteredPermintaans($request);
        // Asumsikan kelas PermintaanExport menerima parameter data (sesuaikan dengan implementasi Anda)
        return Excel::download(new PermintaanExport($permintaans), 'permintaan.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $permintaans = $this->getFilteredPermintaans($request);
        $pdf = PDF::loadView('layouts.admin.permintaan.export-pdf', compact('permintaans'));
        return $pdf->download('permintaan.pdf');
    }

    public function tolakOlehManager(Request $request, $id)
    {
        $request->validate([
            'alasan_ditolak' => 'required|string|max:255',
        ]);

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->status = 'ditolak';
        $permintaan->alasan_ditolak = $request->alasan_ditolak;
        $permintaan->save();

        return redirect()->route('permintaan.index')->with('success', 'Permintaan ditolak dengan alasan.');
    }
}
