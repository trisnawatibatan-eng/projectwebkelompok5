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
        User::updateOrCreate(
            ['email' => 'admin@klinik.com'],
            [
                'name' => 'Admin Utama Klinik',
                'password' => Hash::make('admin123'), // Password: admin123
                'role' => 'admin',
            ]
        );

        // 2. Akun Petugas Pendaftaran
        User::updateOrCreate(
            ['email' => 'pendaftaran@klinik.com'],
            [
                'name' => 'Petugas Pendaftaran',
                'password' => Hash::make('123456'), // Password: 123456
                'role' => 'pendaftaran',
            ]
        );

        // 3. Akun Dokter (Diperbaiki: menggunakan role 'dokter')
        User::updateOrCreate(
            ['email' => 'dokter@klinik.com'],
            [
                'name' => 'Dr. Umum/Gigi',
                'password' => Hash::make('123456'),
                'role' => 'dokter',
            ]
        );
        
        // 4. Akun Petugas Apotek
        User::updateOrCreate(
            ['email' => 'apotek@klinik.com'],
            [
                'name' => 'Petugas Apotek',
                'password' => Hash::make('123456'),
                'role' => 'perawat',
            ]
        );
        
        // 5. Akun Kasir (Untuk melengkapi peran yang ada di migrasi)
        User::updateOrCreate(
            ['email' => 'kasir@klinik.com'],
            [
                'name' => 'Petugas Kasir',
                'password' => Hash::make('123456'),
                'role' => 'kasir',
            ]
        );
    }
}