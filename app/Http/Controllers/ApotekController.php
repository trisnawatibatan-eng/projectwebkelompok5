<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Resep;

class ApotekController extends Controller
{
    public function index()
    {
        $reseps = Resep::with('pemeriksaan')->where('status', '!=', 'Paid')->latest()->get();
        return view('apotek.index', [
            'title' => 'Apotek - Daftar Resep',
            'reseps' => $reseps
        ]);
    }

    /**
     * Tampilkan form pembuatan resep untuk pemeriksaan tertentu.
     */
    public function createResep(Request $request)
    {
        $pemeriksaan_id = $request->query('pemeriksaan_id');

        if (!$pemeriksaan_id) {
            return redirect()->route('apotek.index')->with('error', 'ID pemeriksaan tidak ditemukan.');
        }

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

        $lastResep = Resep::orderBy('id', 'desc')->first();
        $nextId = $lastResep ? $lastResep->id + 1 : 1;
        $no_resep = 'RES-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $items = $validated['items'] ?? [];
        $total = 0;
        foreach ($items as $it) {
            $qty = isset($it['qty']) ? (int)$it['qty'] : 0;
            $price = isset($it['price']) ? (float)$it['price'] : 0;
            $total += $qty * $price;
        }

        $resep = Resep::create([
            'pemeriksaan_id' => $validated['pemeriksaan_id'],
            'no_resep' => $no_resep,
            'items' => json_encode($items),
            'total_biaya' => $total,
            'status' => 'Ready',
        ]);

        return redirect()->route('apotek.index')->with('success', 'Resep berhasil disimpan!');
    }

    /**
     * Proses resep: ubah status dari Pending → Ready
     */
    public function proseResep($resepId)
    {
        $resep = Resep::findOrFail($resepId);
        $resep->update(['status' => 'Ready']);

        return redirect()->route('apotek.index')
            ->with('success', 'Resep ' . $resep->no_resep . ' sudah siap diambil. Pasien dapat ke Kasir untuk pembayaran.');
    }
}
