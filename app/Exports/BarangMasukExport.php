<?php


namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangMasukExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BarangMasuk::with('barang', 'pengadaan')->get()->map(function ($item) {
            return [
                $item->barang->nama_barang,
                $item->jumlah,
                optional($item->pengadaan)->tanggal_pengadaan ?? '-',
                $item->tanggal_diterima ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Barang', 'Jumlah', 'Tanggal Pengadaan', 'Tanggal Diterima'];
    }
}

