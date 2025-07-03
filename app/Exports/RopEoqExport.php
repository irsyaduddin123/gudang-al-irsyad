<?php

namespace App\Exports;

use App\Models\RopEoq;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RopEoqExport implements FromCollection,WithHeadings
{
    /**
    */
    public function collection()
    {
        return RopEoq::with('barang')->get()->map(function($item){
            return[
                'Nama Barang' => $item->barang->nama_barang ?? '-',
                'Total Permintaan' => $item->total,
                'Bulan Perhitungan' => $item->bulan,
                'Rata-rata Pemakaian' => number_format($item->pemakaian_rata, 2),
                'Lead Time' => $item->lead_time,
                'Safety Stok' => $item->safety_stok,
                'Biaya Pesan' => $item->biaya_pesan,
                'Biaya Simpan' => $item->biaya_simpan,
                'ROP' => $item->rop,
                'EOQ' => $item->eoq,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Nama Barang',
            'Total Permintaan',
            'Bulan Perhitungan',
            'Rata-rata Pemakaian',
            'Lead Time',
            'Safety Stok',
            'Biaya Pesan',
            'Biaya Simpan',
            'ROP',
            'EOQ',
        ];
    }
}
