<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Jabatan;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $userPegawai = User::where('role', 'pegawai')->first();
        $jabatan = Jabatan::first();

        Karyawan::create([
            'user_id' => $userPegawai->id,
            'jabatan_id' => $jabatan->id,
            'nip' => '1987654321',
            'nama_lengkap' => 'Pegawai Satu',
            'jenis_kelamin' => 'Laki-laki',
            'foto' => null,
            'status' => 'Aktif',
            'nomor_telepon' => '081234567890',
            'tanggal_masuk' => '2024-01-01',
        ]);
    }
}
