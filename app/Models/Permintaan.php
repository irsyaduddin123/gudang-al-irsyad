<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    //
        protected $guarded = [];
        protected $table = 'permintaan';

    public function barang()
{
    return $this->belongsToMany(Barang::class, 'barang_permintaan')
                ->withPivot('jumlah')
                ->withTimestamps();
}

public function pengguna()
{
    return $this->belongsTo(Pengguna::class);
}
}
