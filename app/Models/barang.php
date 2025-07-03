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
}
