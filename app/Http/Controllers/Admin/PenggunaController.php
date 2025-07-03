<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Hash;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pengguna = Pengguna::all();
        $roles = ['staff', 'permintaan', 'manager'];
        return view('layouts.admin.pengguna.index', compact('pengguna','roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = ['staff', 'permintaan', 'manager'];
        return view('layouts.admin.pengguna.tambah', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'Bagian' => 'required|string|max:100',
            'role' => 'required|in:staff,permintaan,manager',
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'Bagian' => $request->Bagian,
            'role' => $request->role,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    
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
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:100|',
            'Bagian' => 'required|string|max:100',
            'role' => 'required|in:staff,permintaan,manager',
        ]);

        $pengguna = Pengguna::findOrFail($id);
        $pengguna->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'Bagian' => $request->Bagian,
            'role' => $request->role,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'pengguna berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
