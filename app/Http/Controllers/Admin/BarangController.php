<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BarangExport;
use App\Http\Controllers\Controller;
use App\Models\barang;
use App\Models\safetystok;
use App\Models\supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $barang = barang::with('safetystok')->get();
        $satuans = Safetystok::select('id', 'satuan')->distinct()->get();
        $suppliers = Supplier::with('barangs')->get()->keyBy('nama_barang');

         return view('layouts.admin.barang.index', compact('barang','satuans','suppliers'));
    }
       public function create()
        {
            $satuans = Safetystok::select('id', 'satuan')->distinct()->get();
            return view('layouts.admin.barang.tambah', compact('satuans'));
        }

        public function store(Request $request)
        {
            $request->validate([
                'nama_barang'   => 'required|string',
                'harga_beli'    => 'required|integer',
                'stok'          => 'required|integer',
                'safetystok_id' => 'required|exists:safetystoks,id',
            ]);

            $safety = Safetystok::findOrFail($request->safetystok_id);

            Barang::create([
                'nama_barang'    => $request->nama_barang,
                'harga_beli'     => $request->harga_beli,
                'stok'           => $request->stok,
                'satuan'         => $safety->satuan,
                'safetystok_id'  => $safety->id,
            ]);

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        }


        public function update(Request $request, $id)
        {
            $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'harga_beli'    => 'required|integer',
            'stok'          => 'required|integer',
            'safetystok_id' => 'required|exists:safetystoks,id',
        ]);

        $barang = Barang::findOrFail($id);
        $safety = Safetystok::findOrFail($request->safetystok_id);

        $barang->update([
            'nama_barang'   => $request->nama_barang,
            'harga_beli'    => $request->harga_beli,
            'stok'          => $request->stok,
            'satuan'        => $safety->satuan,
            'safetystok_id' => $safety->id,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
        }

        public function destroy($id)
        {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
        }

        public function show($id)
        {
            $barang = Barang::with(['safetystok', 'ropEoq'])->findOrFail($id);
            $supplier = Supplier::where('nama_barang', $barang->nama_barang)->first();
            
            return view('layouts.admin.barang.detail', compact('barang', 'supplier'));
        }


        public function export()
        {
            return Excel::download(new BarangExport, 'data-barang.xlsx');
        }

        public function exportPdf()
        {
            $barangs = Barang::all();
            $suppliers = Supplier::all()->keyBy('nama_barang'); 
            $bulan = Carbon::now()->translatedFormat('F Y');

            $pdf = Pdf::loadView('layouts.admin.barang.pdf', compact('barangs', 'suppliers','bulan'))
                    ->setPaper('A4', 'landscape'); // bisa pakai 'portrait' juga

            return $pdf->download('Data-Barang.pdf'); 
        }
}
