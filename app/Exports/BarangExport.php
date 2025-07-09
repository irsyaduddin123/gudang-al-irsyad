<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Barang::all()->map(function ($b) {
            return [
                'Nama Barang'   => $b->nama_barang,
                'Harga Beli'    => $b->harga_beli,
                'Stok'          => $b->stok,
                'Satuan'        => $b->satuan,
                'Supplier'      => optional(\App\Models\Supplier::where('nama_barang', $b->nama_barang)->first())->nama_supplier,
                'Minimal Stok'  => optional($b->safetystok)->minstok,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Harga Beli',
            'Stok',
            'Satuan',
            'Supplier',
            'Minimal Stok'
        ];
    }
}
