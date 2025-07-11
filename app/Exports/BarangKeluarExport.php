<?php

namespace App\Exports;

use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BarangKeluarExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('layouts.admin.barang_keluar.export-excel', [
            'barang_keluar' => $this->data
        ]);
    }
}
