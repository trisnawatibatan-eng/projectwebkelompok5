<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
// PENTING: Hapus komentar jika Model Antrian sudah dibuat:
// use App\Models\Antrian; 

class PasienController extends Controller
{
    /**
     * Tampilkan semua pasien (Data Master)
     */
    public function index()
    {
        $pasien = Pasien::all();
        return view('data_master', [
            'title' => 'Data Master Pasien',
            'pasien' => $pasien,
            'keyword' => null
        ]);
    }

    /**
     * Form pendaftaran pasien baru
     */
    public function create()
    {
        return view('pendaftaran.pasien_baru', [
            'title' => 'Pendaftaran Pasien Baru'
        ]);
    }

    /**
     * Simpan pasien baru + generate No RM otomatis, dan buat Antrian.
     */
    public function store(Request $request)
    {
        // PENTING: Validasi Nama dan No Asuransi secara kondisional
        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik',
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in:L,P', 
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required',
            
            // Data Alamat & Penjamin
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'penjamin' => 'required|in:Umum,Asuransi', // Hanya izinkan Umum atau Asuransi
            'poliklinik_tujuan' => 'required', 
            'tanggal_kunjungan' => 'required|date',
            
            // Kolom Asuransi (Divalidasi sebagai required jika penjamin == 'Asuransi')
            'nama_asuransi' => 'nullable|string|required_if:penjamin,Asuransi',
            'no_asuransi' => 'nullable|string|required_if:penjamin,Asuransi',
            
            // Kolom Opsional Lainnya
            'email' => 'nullable|email',
            'agama' => 'nullable|string',
            'status_keluarga' => 'nullable|string',
            'golongan_darah' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);
        
        // 1. Membersihkan dan Mempersiapkan Data Pasien
        
        // JIKA BUKAN ASURANSI, SET KOLOM ASURANSI MENJADI NULL (PENTING untuk database)
        if ($validated['penjamin'] !== 'Asuransi') {
            $validated['nama_asuransi'] = null;
            $validated['no_asuransi'] = null;
        }

        // Generate No RM otomatis: RM00001, RM00002, dst
        $lastPasien = Pasien::orderBy('id', 'desc')->first();
        $nextId = $lastPasien ? $lastPasien->id + 1 : 1;
        $no_rm = 'RM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        $validated['no_rm'] = $no_rm;
        
        // Filter data untuk tabel 'pasiens' (pastikan kolom NIK, Asuransi, dll. ada di Model/Migrasi Pasien)
        $pasienData = array_filter($validated, function($key) {
            // Kolom-kolom yang ada di formulir pasien baru DAN harus masuk ke tabel `pasiens`
            return in_array($key, [
                'nik', 'no_rm', 'nama', 'alamat', 'jenis_kelamin', 'tanggal_lahir', 
                'no_telepon', 'email', 'agama', 'status_keluarga', 'golongan_darah', 
                'pekerjaan', 'provinsi', 'kota', 'kecamatan', 'nama_asuransi', 'no_asuransi' 
            ]);
        }, ARRAY_FILTER_USE_KEY);

        $pasien = Pasien::create($pasienData);

        // 2. LOGIC ANTRIAN BARU
        $pasien_id = $pasien->id; 

        $dataAntrian = [
            'pasien_id' => $pasien_id,
            'poli_tujuan' => $validated['poliklinik_tujuan'],
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'penjamin' => $validated['penjamin'],
            'status' => 'Menunggu', 
            // Tambahkan kolom nomor_antrian yang dihitung di sini
        ];

        // *** PENTING: Hapus komentar di bawah ini setelah Model Antrian Anda siap ***
        // Antrian::create($dataAntrian);

        // 3. Redirect
        return redirect()->route('data.master')
            ->with('success', 'Pasien baru berhasil didaftarkan dan masuk antrian ' . $validated['poliklinik_tujuan'] . '! No RM: ' . $no_rm);
    }

    /**
     * Form pencarian pasien lama
     */
    public function searchForm()
    {
        return view('pendaftaran.pasien_lama', [
            'title' => 'Pendaftaran Pasien Lama',
            'pasien' => null,
            'hasil' => null
        ]);
    }

    /**
     * Cari pasien berdasarkan No RM / NIK / Nama (untuk pasien lama)
     */
    public function searchByNoRM(Request $request)
    {
        $keyword = $request->keyword;

        $pasien = Pasien::where('no_rm', $keyword)
            ->orWhere('nik', $keyword)
            ->orWhere('nama', 'like', "%$keyword%")
            ->first();

        if ($pasien) {
            return view('pendaftaran.pasien_lama', [
                'title' => 'Pendaftaran Pasien Lama',
                'pasien' => $pasien,
                'hasil' => null
            ]);
        } else {
            return redirect()->route('pasien.lama')
                ->with('info', 'Pasien tidak ditemukan. Silakan daftar sebagai pasien baru.');
        }
    }

    /**
     * Pencarian di Data Master
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $pasien = Pasien::where('nik', 'like', "%$keyword%")
            ->orWhere('nama', 'like', "%$keyword%")
            ->orWhere('no_rm', 'like', "%$keyword%")
            ->get();

        return view('data_master', [
            'title' => 'Hasil Pencarian Pasien',
            'pasien' => $pasien,
            'keyword' => $keyword
        ]);
    }

    /**
     * Form edit pasien
     */
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('edit_pasien', [
            'title' => 'Edit Data Pasien',
            'pasien' => $pasien
        ]);
    }

    /**
     * Update data pasien
     */
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik,' . $id,
            'no_rm' => 'required|unique:pasiens,no_rm,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        $pasien->update($validated);

        return redirect()->route('data.master')
            ->with('success', 'Data pasien berhasil diperbarui!');
    }

    /**
     * Hapus pasien
     */
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('data.master')
            ->with('success', 'Data pasien berhasil dihapus!');
    }

    // FITUR BARU: AJAX Live Search untuk semua Poli (KIA, Gigi, Umum, dll)
    public function cariPasienAjax(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $pasien = Pasien::where('no_rm', 'LIKE', "%{$query}%")
            ->orWhere('nama', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->select([
                'id',
                'no_rm',
                'nama',
                'tanggal_lahir',
                'jenis_kelamin',
                'alamat',
                'no_telepon'
            ])
            ->limit(10)
            ->get();

        return response()->json($pasien);
    }
}