<?php

namespace Database\Seeders;

use App\Models\supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class supplierseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        supplier::create([
            'nama_supplier' => 'TBMO',
            'nama_barang' => 'Bullpoint',
        ]);
        supplier::create([
            'nama_supplier' => 'Toko Kelontong',
            'nama_barang' => 'nampan',
        ]);
    }
}
