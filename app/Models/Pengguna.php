<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $fillable = ['username', 'nama', 'password', 'role'];

    protected $hidden = ['password'];

    // public function getAuthIdentifierName()
    // {
    //     return 'username'; // agar Laravel tahu login pakai kolom ini
    // }
}
