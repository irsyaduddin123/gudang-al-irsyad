<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\RopEoq;

class managerdashboardController extends Controller
{
    public function index()
    {
        $jumlahBarang = Barang::count();
        $jumlahPermintaan = Permintaan::count();
        $barangMenipis = Barang::with('safetystok')
            ->get()
            ->filter(function ($barang) {
                $minstok = $barang->safetystok->minstok ?? 0;
                return $barang->stok <= $minstok;
            })->count();

        $jumlahRopEoq = RopEoq::count();

        return view('layouts.manager.dashboard.index', compact(
            'jumlahBarang',
            'jumlahPermintaan',
            'barangMenipis',
            'jumlahRopEoq'
        ));
    }
}
