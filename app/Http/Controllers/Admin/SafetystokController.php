<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SafetystokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $safetystok = \App\Models\safetystok::all();
        return view('layouts.admin.safetystok.index', compact('safetystok'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.safetystok.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'satuan' => 'required|string|max:50',
            'minstok' => 'required|integer|min:0',
        ]);

        \App\Models\safetystok::create([
            'satuan' => $request->satuan,
            'minstok' => $request->minstok,
        ]);

        return redirect()->route('safetystok.index')->with('success', 'Data Safety Stok berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'satuan' => 'required|string|max:50',
            'minstok' => 'required|integer|min:0',
        ]);

        $data  = \App\Models\safetystok::findOrFail($id);
        $data ->update([
            'satuan' => $request->satuan,
            'minstok' => $request->minstok,
        ]);

        return redirect()->route('safetystok.index')->with('success', 'Data safetystok berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = \App\Models\safetystok::findOrFail($id);
        $data->delete();

        return redirect()->route('safetystok.index')->with('success', 'Data Safety Stok berhasil dihapus.');

    }
}
