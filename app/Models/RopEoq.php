<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RopEoq extends Model
{
    //
    protected $fillable = [
        'barang_id',
        'lead_time',
        'pemakaian_rata',
        'biaya_pesan',
        'biaya_simpan',
        'rop',
        'eoq',
        'total',
        'bulan',
        'safety_stok',
        ];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
