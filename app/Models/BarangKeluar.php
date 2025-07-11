<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    // use HasFactory;
     protected $table = 'barang_keluar';

    protected $fillable = [
        'permintaan_id',
        'barang_id',
        'jumlah',
        'tanggal_keluar',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }
}
