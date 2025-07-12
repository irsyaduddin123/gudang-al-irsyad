<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    //
    protected $table = 'barang_masuks';

    protected $fillable = [
        'barang_id', 'pengadaan_id', 'jumlah', 'tanggal_diterima',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class);
    }
}
