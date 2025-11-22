<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;

class PemeriksaanController extends Controller
{
    /**
     * Menampilkan form pemeriksaan dokter
     */
    public function create()
    {
        return view('pemeriksaan.create', [
            'title' => 'Form Pemeriksaan Dokter'
        ]);
    }

    /**
     * Menyimpan data pemeriksaan ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'no_rm'           => 'required|string|max:20',
            'nama'            => 'nullable|string|max:100',
            'keluhan_utama'   => 'required|string',
            'riwayat_penyakit'=> 'nullable|string',
            'suhu'            => 'nullable|numeric',
            'tekanan_darah'   => 'nullable|string|max:20',
            'nadi'            => 'nullable|numeric',
            'respirasi'       => 'nullable|numeric',
            'diagnosa'        => 'required|string',
            'terapi'          => 'required|string',
            'rujukan'         => 'nullable|string|max:100',
        ]);

        // Simpan ke tabel pemeriksaan
        Pemeriksaan::create($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('pemeriksaan.create')
                         ->with('success', 'Data pemeriksaan berhasil disimpan.');
    }
}