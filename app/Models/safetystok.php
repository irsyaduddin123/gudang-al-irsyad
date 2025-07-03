<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class safetystok extends Model
{
    //
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = ['satuan', 'minstok'];


    public function barangs()
{
    return $this->hasMany(Barang::class);
}


}
