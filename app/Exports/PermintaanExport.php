<?php

namespace App\Exports;

use App\Models\Permintaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermintaanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Permintaan::with('pengguna', 'barang')
            ->get()
            ->map(function ($p) {
                return [
                    'ID' => $p->id,
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
        return ['ID', 'Pengguna', 'Bagian', 'Barang', 'Jumlah', 'Status', 'Tanggal'];
    }
}

