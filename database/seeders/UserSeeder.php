<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User Pegawai
        User::create([
            'name' => 'Pegawai Satu',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai'
        ]);

        // Admin
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}
