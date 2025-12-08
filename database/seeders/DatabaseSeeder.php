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
        // Gunakan $this->call() untuk memanggil semua Seeder yang dibutuhkan.
        // Urutan pemanggilan PENTING: Panggil tabel induk (tanpa Foreign Key)
        // terlebih dahulu sebelum tabel anak (yang memiliki Foreign Key).
        
        $this->call([
            // 1. Panggil Seeder untuk data User (Admin/Dokter)
            UserSeeder::class, 

            // 2. Panggil Seeder untuk data pengujian Resep (Prescription)
            // Asumsi: Seeder ini mungkin membuat data Obat, Pasien, Resep, dan Detail Resep.
            TestPrescriptionSeeder::class,
            
            // Jika Anda memiliki Seeder lain (misalnya PasienSeeder, ObatSeeder yang terpisah, TindakanSeeder),
            // pastikan Anda menambahkannya di sini, disesuaikan dengan urutan ketergantungan tabel.
        ]);
    }
}