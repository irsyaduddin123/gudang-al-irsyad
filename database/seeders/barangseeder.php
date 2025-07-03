<?php

namespace Database\Seeders;

use App\Models\barang;
use App\Models\safetystok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class barangseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $stokLembar = safetystok::where('satuan', 'lembar')->first();
    $stokRim = safetystok::where('satuan', 'rim')->first();
    $stokPcs = safetystok::where('satuan', 'pcs')->first();

    barang::insert([
        [
            'nama_barang' => 'Lembar Perkembangan Pasien',
            'harga_beli' => 5000,
            'stok' => 100,
            'satuan' => 'lembar',
            'safetystok_id' => $stokLembar?->id,
        ],
        [
            'nama_barang' => 'lembar konsultasi',
            'harga_beli' => 1000,
            'stok' => 150,
            'satuan' => 'lembar',
            'safetystok_id' => $stokLembar?->id,
        ],
        [
            'nama_barang' => 'Surat Kontrol',
            'harga_beli' => 37000,
            'stok' => 100,
            'satuan' => 'rim',
            'safetystok_id' => $stokRim?->id,
        ],
        [
            'nama_barang' => 'Kertas HVS',
            'harga_beli' => 44000,
            'stok' => 100,
            'satuan' => 'rim',
            'safetystok_id' => $stokRim?->id,
        ],
        [
            'nama_barang' => 'Bullpoint Faster',
            'harga_beli' => 2000,
            'stok' => 100,
            'satuan' => 'pcs',
            'safetystok_id' => $stokPcs?->id,
        ],
    ]);
}

}
