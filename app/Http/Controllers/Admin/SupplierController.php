<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $suppliers = \App\Models\Supplier::all();
        return view('layouts.admin.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.supplier.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:50',
        ]);

        \App\Models\supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'nama_barang' => $request->nama_barang,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Data Safety Stok berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:50',
        ]);

        $supplier = \App\Models\Supplier::findOrFail($id);
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'nama_barang' => $request->nama_barang,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    
    {
        $data = \App\Models\supplier::findOrFail($id);
        $data->delete();

        return redirect()->route('supplier.index')->with('success', 'Data Safety Stok berhasil dihapus.');

        //
    }
}
