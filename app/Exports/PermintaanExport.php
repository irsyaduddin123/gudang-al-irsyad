<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermintaanExport implements FromCollection, WithHeadings
{
    protected $permintaans; // ✅ Tambahkan ini!

    public function __construct($permintaans)
    {
        $this->permintaans = $permintaans;
    }

    public function collection()
    {
        return $this->permintaans->map(function ($p) {
            return [
                'Pengguna' => $p->pengguna->nama ?? '-',
                'Bagian' => $p->pengguna->Bagian ?? '-',
                'Barang' => $p->barang->pluck('nama_barang')->implode(', '),
                'Jumlah' => $p->barang->pluck('pivot.jumlah')->implode(', '),
                'Status' => $p->status,
                'Tanggal' => $p->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Pengguna', 'Bagian', 'Barang', 'Jumlah', 'Status', 'Tanggal'];
    }
}
