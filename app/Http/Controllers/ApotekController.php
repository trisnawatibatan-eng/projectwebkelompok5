<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Resep;

class ApotekController extends Controller
{
    public function index()
    {
        $reseps = Resep::with('pemeriksaan')->latest()->get();
        return view('apotek.index', [
            'title' => 'Apotek - Daftar Transaksi',
            'reseps' => $reseps
        ]);
    }

    /**
     * Tampilkan form pembuatan resep untuk pemeriksaan tertentu.
     */
    public function createResep(Request $request)
    {
        $pemeriksaan_id = $request->query('pemeriksaan_id');

        // Jika tidak ada pemeriksaan_id, kembali ke daftar apotek dengan error
        if (!$pemeriksaan_id) {
            return redirect()->route('apotek.index')->with('error', 'ID pemeriksaan tidak ditemukan.');
        }

        // Ambil data pemeriksaan nyata dari tabel pemeriksaan
        $pemeriksaan = Pemeriksaan::find($pemeriksaan_id);
        if (!$pemeriksaan) {
            return redirect()->route('apotek.index')->with('error', 'Data pemeriksaan dengan ID #' . $pemeriksaan_id . ' tidak ditemukan.');
        }

        return view('apotek.resep_create', compact('pemeriksaan'));
    }

    /**
     * Simpan resep yang dikirim dari form.
     */
    public function storeResep(Request $request)
    {
        $validated = $request->validate([
            'pemeriksaan_id' => 'required|numeric',
            'items' => 'nullable|array',
        ]);

        // Generate no resep otomatis
        $lastResep = Resep::orderBy('id', 'desc')->first();
        $nextId = $lastResep ? $lastResep->id + 1 : 1;
        $no_resep = 'AP-' . date('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Simpan resep ke database
        $resep = Resep::create([
            'pemeriksaan_id' => $validated['pemeriksaan_id'],
            'no_resep' => $no_resep,
            'items' => json_encode($validated['items'] ?? []),
            'total_biaya' => 0,
            'status' => 'Pending',
        ]);

        return redirect()->route('apotek.index')->with('success', 'Resep berhasil disimpan!');
    }
}
