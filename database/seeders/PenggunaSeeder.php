<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Pengguna::insert([
            
            [
                'nama' => 'Staff Logistik',
                'username' => 'staff',
                'password' => Hash::make('staff'),
                'role' => 'staff',
                'bagian' => 'Staff Logistik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Manager Gudang',
                'username' => 'manager',
                'password' => Hash::make('manager'),
                'role' => 'manager',
                'bagian' => 'manager Logistik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bagian Permintaan',
                'username' => 'permintaan',
                'password' => Hash::make('permintaan'),
                'role' => 'permintaan',
                'bagian' => 'Bagian Permintaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
