<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        Jabatan::insert([
            [
                'nama_jabatan' => 'Staff IT',
                'gaji_pokok' => 4000000,
                'tunjangan_transport' => 500000,
                'uang_makan' => 300000,
            ],
            [
                'nama_jabatan' => 'HRD',
                'gaji_pokok' => 3500000,
                'tunjangan_transport' => 400000,
                'uang_makan' => 250000,
            ]
        ]);
    }
}
