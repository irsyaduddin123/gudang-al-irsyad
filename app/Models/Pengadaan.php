<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $guarded = [];
    // atau:
    protected $fillable = ['barang_id', 'supplier_id', 'tanggal_pengadaan', 'jumlah', 'status'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
