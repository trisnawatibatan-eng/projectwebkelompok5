<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Digunakan untuk JOIN atau query kompleks

// Asumsi Anda memiliki model Pasien, Pemeriksaan, dan Resep/Apotek
// use App\Models\Pasien; 
// use App\Models\Pemeriksaan;
// use App\Models\Resep; 

class KasirController extends Controller
{
    /**
     * Menampilkan formulir Kasir (titik awal pembayaran).
     */
    public function index()
    {
        return view('kasir.index');
    }

    /**
     * API: Mencari tagihan yang belum dibayar berdasarkan No. RM atau ID Pemeriksaan.
     * Dihubungkan ke frontend melalui route('kasir.cari.tagihan')
     */
    public function cariTagihan(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // --- PENTING: LOGIKA PENCARIAN TAGIHAN NYATA ---
        /* * Dalam implementasi nyata, Anda akan:
         * 1. Mencari Pasien/Pemeriksaan berdasarkan $query.
         * 2. Filter hanya Pemeriksaan yang STATUS_BAYAR = 'Belum Lunas'.
         * 3. JOIN dengan tabel Tindakan dan Resep untuk mengagregasi total tagihan.
         * 4. Mengembalikan rincian dalam format yang sama dengan dummyTagihan di frontend.
         */

        // Contoh Struktur Data Output (Sama persis dengan dummyTagihan di JS)
        $tagihanBelumBayar = [
            // Contoh data yang diambil dari database:
            [
                'id' => 1, // ID Pemeriksaan
                'no_rm' => 'RM000002',
                'nama_pasien' => 'Marlina Kan',
                'poli' => 'Gigi & Mulut',
                'tagihan_list' => [
                    ['desc' => 'Jasa Dokter Poli Gigi', 'biaya' => 50000],
                    ['desc' => 'Scaling & Polishing', 'biaya' => 150000],
                    // Jika terintegrasi dengan Apotek, ambil item resep di sini:
                    ['desc' => 'Resep Obat (Apotek)', 'biaya' => 25000],
                ]
            ],
            // ... Tambahkan tagihan lain yang belum dibayar ...
        ];

        // Lakukan filter simulasi berdasarkan query (HAPUS SIMULASI INI DI APLIKASI NYATA)
        $results = collect($tagihanBelumBayar)->filter(function($tagihan) use ($query) {
            return str_contains(strtolower($tagihan['no_rm']), strtolower($query)) || 
                   str_contains(strtolower($tagihan['nama_pasien']), strtolower($query));
        })->values();

        return response()->json($results);
    }
    
    /**
     * Memproses penyimpanan pembayaran (POST).
     * Dihubungkan ke frontend melalui route('kasir.bayar')
     */
    public function bayar(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'pemeriksaan_id' => 'required|exists:pemeriksaan,id', // Pastikan ID pemeriksaan valid
            'metode_pembayaran' => 'required|in:Tunai,Debit,Transfer',
            'jumlah_bayar' => 'required|numeric|min:0',
            // Total tagihan harus divalidasi juga (biasanya dikirim sebagai input hidden)
        ]);
        
        // 2. LOGIKA PEMBAYARAN NYATA
        /*
         * a. Ambil total tagihan dari database (berdasarkan pemeriksaan_id).
         * b. Hitung kembalian dan pastikan jumlah_bayar >= total_tagihan.
         * c. Catat transaksi pembayaran di tabel 'pembayaran'.
         * d. Perbarui status di tabel 'pemeriksaan' (STATUS_BAYAR = 'Lunas').
         */

        // SIMULASI
        $pemeriksaanId = $request->pemeriksaan_id;
        
        // Redirect dengan sukses
        return redirect()->route('dashboard') 
            ->with('success', "Pembayaran untuk ID Pemeriksaan #{$pemeriksaanId} berhasil diproses!");
    }
}