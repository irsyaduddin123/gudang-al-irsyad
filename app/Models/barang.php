<?php

namespace App\Models;

use Dotenv\Repository\Adapter\GuardedWriter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    //
    protected $guarded = [];
    protected $table = 'barangs';



        public function safetystok()
    {
        return $this->belongsTo(Safetystok::class);
    }
    public function permintaan()
    {
        return $this->belongsToMany(Permintaan::class, 'barang_permintaan')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
        public function ropEoq()
    {
        return $this->hasOne(RopEoq::class);
    }

    public function ropEoqSemua()
{
    return $this->hasMany(RopEoq::class, 'barang_id');
}

    //     public function supplier()
    // {
    //     return $this->belongsTo(Supplier::class, 'nama_barang', 'nama_barang');
    // }

//     public function suppliers()
// {
//     return Supplier::where('nama_barang', $this->nama_barang)->get();
// }
public function suppliers()
{
    return $this->hasMany(Supplier::class, 'nama_barang', 'nama_barang');
}

}
