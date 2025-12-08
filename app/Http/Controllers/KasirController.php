<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\Pemeriksaan;
use App\Models\Invoice; // Asumsi Anda punya model Invoice/Pembayaran
use App\Models\Kunjungan; // Hanya untuk memastikan import lengkap jika diperlukan

class KasirController extends Controller
{
    /**
     * Menampilkan formulir Kasir (titik awal pembayaran).
     */
    public function index()
    {
         // Ambil semua data resep + relasi pemeriksaan, kunjungan, dan pasien
         // Relasi 'pemeriksaan.kunjungan.pasien' diasumsikan sudah diperbaiki
         $reseps = Resep::with(['pemeriksaan.kunjungan.pasien'])->get();

         // Kirim ke view
         return view('kasir.index', compact('reseps'));
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

        // 🚨 Tambahkan logika pencarian tagihan di sini
        // Contoh: Cari Resep atau Pemeriksaan berdasarkan No. RM atau ID
        $tagihan = Resep::whereHas('pemeriksaan.kunjungan.pasien', function ($q) use ($query) {
            $q->where('no_rm', 'like', "%{$query}%");
        })
        ->orWhere('pemeriksaan_id', $query)
        ->get();

        return response()->json($tagihan);
    }
    
    /**
     * 🟢 METODE YANG HILANG (createInvoice) 🟢
     * Menangani proses pembuatan dan penyimpanan invoice/tagihan.
     * Ini dipanggil dari route yang menyebabkan error sebelumnya.
     */
    public function createInvoice(Request $request)
    {
        // 1. Validasi Input (ID Resep, Jumlah Bayar, Metode Pembayaran, dll.)
        $request->validate([
            'resep_id' => 'required|exists:reseps,id',
            'jumlah_bayar' => 'required|numeric|min:0',
            // ... validasi lain
        ]);
        
        $resepId = $request->input('resep_id');
        $jumlahBayar = $request->input('jumlah_bayar');

        // 2. Ambil data Resep dan hitung total tagihan
        $resep = Resep::with(['pemeriksaan', 'detailObat'])->findOrFail($resepId);
        
        // 🚨 Logika perhitungan tagihan total 🚨
        $biayaObat = $resep->detailObat->sum(function ($detail) {
             return $detail->harga * $detail->jumlah;
        });
        
        $biayaJasa = $resep->pemeriksaan->biaya_jasa ?? 0; // Asumsi kolom biaya_jasa ada di model Pemeriksaan
        $totalTagihan = $biayaObat + $biayaJasa;

        if ($jumlahBayar < $totalTagihan) {
            return back()->with('error', 'Jumlah bayar kurang dari total tagihan.');
        }

        // 3. Simpan record Invoice/Pembayaran
        $invoice = Invoice::create([
            'resep_id' => $resepId,
            'pemeriksaan_id' => $resep->pemeriksaan_id,
            'total_tagihan' => $totalTagihan,
            'jumlah_bayar' => $jumlahBayar,
            'kembalian' => $jumlahBayar - $totalTagihan,
            'status' => 'Lunas', // Atur status pembayaran
            'tanggal_bayar' => now(),
            'user_kasir_id' => auth()->id(), // Asumsi user kasir terautentikasi
        ]);

        // 4. Update status Resep/Pemeriksaan (opsional, tergantung alur Anda)
        // $resep->status = 'Dibayar';
        // $resep->save();
        
        // 5. Redirect atau kembalikan response
        return redirect()->route('kasir.index')
            ->with('success', '✅ Pembayaran dan Invoice berhasil dibuat! Total: ' . number_format($totalTagihan));
    }
}