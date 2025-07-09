<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    //
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = ['nama_supplier', 'nama_barang'];

    public function barangs()
{
    return $this->hasMany(Barang::class, 'nama_barang', 'nama_barang');
}

}
