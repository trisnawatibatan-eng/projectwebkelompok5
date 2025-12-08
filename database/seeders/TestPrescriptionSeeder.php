<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Kunjungan;

class TestPrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a test patient if not exists
        $pasien = Pasien::where('no_rm', 'RM00001')->first();
        if (!$pasien) {
            $pasien = Pasien::create([
                'nik' => '1234567890123456',
                'no_rm' => 'RM00001',
                'nama' => 'Ujicoba Resep',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '1998-01-01',
                'alamat' => 'Jl. Test No. 1',
                'no_telepon' => '081234567890',
            ]);
        }

        // Create a pending kunjungan if not exists
        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)->where('status', 'pending')->first();
        if (!$kunjungan) {
            Kunjungan::create([
                'pasien_id' => $pasien->id,
                'no_rm' => $pasien->no_rm,
                'poli' => 'Umum',
                'tanggal_kunjungan' => now()->toDateString(),
                'keluhan_utama' => 'Pemeriksaan rutin',
                'status' => 'pending',
            ]);
            echo "✓ Created test kunjungan for patient: {$pasien->nama}\n";
        } else {
            echo "✓ Test kunjungan already exists for patient: {$pasien->nama}\n";
        }
    }
}
