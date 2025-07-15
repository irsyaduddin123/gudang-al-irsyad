<?php


namespace App\Exports;

use App\Models\BarangMasuk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BarangMasukExport implements FromView
{
    public function view(): View
    {
        $data = BarangMasuk::with(['barang', 'pengadaan'])->get();
        return view('barang_masuk.export_excell', compact('data'));
    }
}
