<?php

namespace Database\Seeders;

use App\Models\safetystok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class safetystokseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        safetystok::create([
            'satuan' => 'pcs',
            'minstok' => '20',
        ]);
        safetystok::create([
            'satuan' => 'box',
            'minstok' => '15',
        ]);
        safetystok::create([
            'satuan' => 'lembar',
            'minstok' => '30',
        ]);
        safetystok::create([
            'satuan' => 'rim',
            'minstok' => '15',
        ]);
        safetystok::create([
            'satuan' => 'pack',
            'minstok' => '15',
        ]);
    }
}
