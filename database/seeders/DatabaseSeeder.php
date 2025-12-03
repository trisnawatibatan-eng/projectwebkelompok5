<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Panggil UserSeeder di sini agar dieksekusi
            UserSeeder::class,
            // Jika Anda memiliki seeder lain (misal PasienSeeder, PoliSeeder), tambahkan di bawah.
        ]);
    }
}