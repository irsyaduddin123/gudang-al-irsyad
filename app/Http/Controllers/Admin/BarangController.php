<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\barang;
use App\Models\safetystok;
use DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = barang::with('safetystok')->get();
        $satuans = Safetystok::select('id', 'satuan')->distinct()->get();

         return view('layouts.admin.barang.index', compact('barang','satuans'));
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
}
