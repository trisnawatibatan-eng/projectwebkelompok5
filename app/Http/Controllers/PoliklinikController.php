<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Jika Anda menggunakan Model Pasien, pastikan di-import di sini
// use App\Models\Pasien; 
// use App\Models\Pemeriksaan;

class PoliklinikController extends Controller
{
    /**
     * Menampilkan daftar poliklinik yang tersedia.
     */
    public function index()
    {
        // Daftar poliklinik yang ditampilkan (Hanya 3 Poli yang disepakati)
        $polikliniks = [
            [
                'nama'  => 'Poli Umum',
                'icon'  => 'user-md', // Ikon yang lebih spesifik untuk Umum
                'warna' => 'poli-umum-icon', // Kelas warna kustom untuk ikon
                'slug'  => 'umum',
                'desc'  => 'Pelayanan kesehatan umum, konsultasi, dan pengobatan bagi semua usia.'
            ],
            [
                'nama'  => 'Poli Gigi & Mulut',
                'icon'  => 'tooth', // Ikon Gigi
                'warna' => 'poli-gigi-icon', // Kelas warna kustom untuk ikon
                'slug'  => 'gigi',
                'desc'  => 'Perawatan gigi, scaling, penambalan, pencabutan, dan konsultasi kesehatan mulut.'
            ],
            [
                'nama'  => 'Poli KIA / KB',
                'icon'  => 'baby', // Ikon KIA/Bayi
                'warna' => 'poli-kia-icon', // Kelas warna kustom untuk ikon
                'slug'  => 'kia',
                'desc'  => 'Ibu hamil, nifas, imunisasi bayi, dan program keluarga berencana (KB).'
            ],
        ];

        return view('poliklinik.index', compact('polikliniks'));
    }

    /**
     * Menampilkan formulir pemeriksaan berdasarkan poli yang dipilih.
     */
    public function create(Request $request)
    {
        $poli_slug = $request->query('poli', 'umum');
        $pasien_id = $request->query('pasien_id'); // ID Pasien yang dipilih (dari route)

        // --- Simulasi Data Pasien ---
        // PENTING: Ganti dengan Pasien::findOrFail($pasien_id) di aplikasi nyata
        if (!$pasien_id) {
            // Jika tidak ada ID pasien (misal diakses langsung), gunakan data placeholder
            $pasien = (object) ['id' => 999, 'no_rm' => 'RM000999', 'nama' => 'Pasien Placeholder'];
        } else {
            // Dalam aplikasi nyata: $pasien = Pasien::findOrFail($pasien_id);
            $pasien = (object) ['id' => $pasien_id, 'no_rm' => "RM000{$pasien_id}", 'nama' => 'Pasien Tes ID ' . $pasien_id];
        }
        
        // Pilih view berdasarkan slug poli. Semua view berada di resources/views/poliklinik
        return match ($poli_slug) {
            'umum'   => view('poliklinik.umum', compact('pasien')),
            'kia'    => view('poliklinik.kia', compact('pasien')), 
            'gigi'   => view('poliklinik.gigi', compact('pasien')), 
            default  => redirect()->route('poliklinik')->with('error', 'Poliklinik tidak ditemukan atau belum tersedia.'),
        };
    }

    /**
     * Menyimpan data pemeriksaan dan menautkan ke Apotek jika diperlukan.
     */
    public function store(Request $request)
    {
        // PENTING: Lakukan Validasi Data di sini sebelum disimpan
        $request->validate([
            'pasien_id' => 'required|numeric',
            'poli_slug' => 'required|string',
            'keluhan_utama' => 'required|string',
            'td' => 'nullable|string',
            // ... tambahkan validasi untuk field formulir lainnya ...
            'diagnosis' => 'required|string',
            'action' => 'required|string|in:save_only,save_and_next',
        ]);
        
        // --- SIMULASI PENYIMPANAN DATA (GANTI DENGAN LOGIC NYATA) ---
        // Dalam aplikasi nyata: $pemeriksaan = Pemeriksaan::create($request->all());
        $pemeriksaan_id = rand(100, 999); // Simulasikan ID Pemeriksaan yang baru disimpan
        
        // Logika Pengarahan (Tautan)
        if ($request->input('action') == 'save_and_next') {
            // Redirect ke Apotek (Form Resep Baru)
            // Route 'apotek.resep.create' harus didefinisikan di web.php
            return redirect()->route('apotek.resep.create', [
                'pemeriksaan_id' => $pemeriksaan_id
            ])->with('success', 'Pemeriksaan berhasil disimpan. Lanjutkan ke form Resep Obat.');
        } 
        
        // Jika action == 'save_only'
        return redirect()->route('poliklinik')
             ->with('success', 'Pemeriksaan berhasil disimpan, pasien siap dirujuk atau pulang.');
    }
}
