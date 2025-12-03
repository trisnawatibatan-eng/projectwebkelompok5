<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan ini mengarah ke Model User Anda
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Akun Admin Utama
        User::create([
            'name' => 'Admin Utama Klinik',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('admin123'), // Password: admin123
            'role' => 'admin', 
        ]);

        // 2. Akun Petugas Pendaftaran
        User::create([
            'name' => 'Petugas Pendaftaran',
            'email' => 'pendaftaran@klinik.com',
            'password' => Hash::make('123456'), // Password: 123456
            'role' => 'pendaftaran', 
        ]);

        // 3. Akun Dokter (Diperbaiki: menggunakan role 'dokter')
        User::create([
            'name' => 'Dr. Umum/Gigi',
            'email' => 'dokter@klinik.com',
            'password' => Hash::make('123456'),
            'role' => 'dokter', 
        ]);
        
        // 4. Akun Petugas Apotek
        User::create([
            'name' => 'Petugas Apotek',
            'email' => 'apotek@klinik.com',
            'password' => Hash::make('123456'),
            'role' => 'apotek', 
        ]);
        
        // 5. Akun Kasir (Untuk melengkapi peran yang ada di migrasi)
        User::create([
            'name' => 'Petugas Kasir',
            'email' => 'kasir@klinik.com',
            'password' => Hash::make('123456'),
            'role' => 'kasir', 
        ]);
    }
}