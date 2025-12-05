<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Kunjungan;

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
     * Simpan pasien baru + generate No RM otomatis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik',
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required',
            
            // Data tambahan dari form pendaftaran pasien baru (Wajib divalidasi)
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'penjamin' => 'required',
            'poliklinik_tujuan' => 'required', 
            'tanggal_kunjungan' => 'required|date',
            'no_bpjs' => 'nullable|max:13',
            
            // Kolom opsional yang mungkin ada di form
            'email' => 'nullable|email',
            'agama' => 'nullable|string',
            'status_keluarga' => 'nullable|string',
            'golongan_darah' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);
        
        // Atur agar NIK dan No BPJS menjadi nullable jika tidak diisi
        $validated['nik'] = $validated['nik'] ?? null;
        $validated['no_bpjs'] = $validated['no_bpjs'] ?? null;
        
        // 1. Generate No RM otomatis: RM00001, RM00002, dst
        $lastPasien = Pasien::orderBy('id', 'desc')->first();
        $nextId = $lastPasien ? $lastPasien->id + 1 : 1;
        $no_rm = 'RM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $validated['no_rm'] = $no_rm;

        // Map short gender values (L/P) to DB enum values (Laki-laki/Perempuan)
        if (isset($validated['jenis_kelamin'])) {
            $validated['jenis_kelamin'] = $validated['jenis_kelamin'] === 'L'
                ? 'Laki-laki'
                : ($validated['jenis_kelamin'] === 'P' ? 'Perempuan' : $validated['jenis_kelamin']);
        }

        // --- PERBAIKAN: Pisahkan data Pasien dari data Antrian ---
        $pasienData = array_filter($validated, function($key) {
            // Filter kolom yang disimpan ke tabel 'pasiens'
            return in_array($key, [
                'nik', 'no_rm', 'nama', 'alamat', 'jenis_kelamin', 'tanggal_lahir', 
                'no_telepon', 'email', 'agama', 'status_keluarga', 'golongan_darah', 
                'pekerjaan', 'provinsi', 'kota', 'kecamatan', 'no_bpjs'
            ]);
        }, ARRAY_FILTER_USE_KEY);

        // Pastikan kolom 'alamat' lengkap, gabungkan kecamatan/kota jika perlu (tergantung kebutuhan tampilan)
        $pasien = Pasien::create($pasienData);

        // 2. LOGIC ANTRIAN BARU
        // Dapatkan ID pasien yang baru dibuat
        $pasien_id = $pasien->id; 

        // Tentukan data antrian
        $dataAntrian = [
            'pasien_id' => $pasien_id,
            'poli_tujuan' => $validated['poliklinik_tujuan'],
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'penjamin' => $validated['penjamin'],
            'status' => 'Menunggu', 
        ];

        // Tentukan slug poli dari nilai pilihan (sederhanakan ke 'umum','gigi','kia')
        $poliInput = strtolower($dataAntrian['poli_tujuan']);
        if (str_contains($poliInput, 'gigi')) {
            $poliSlug = 'gigi';
        } elseif (str_contains($poliInput, 'kia') || str_contains($poliInput, 'kb')) {
            $poliSlug = 'kia';
        } else {
            $poliSlug = 'umum';
        }

        // Simpan kunjungan/entry antrian ke tabel kunjungans
        Kunjungan::create([
            'pasien_id' => $dataAntrian['pasien_id'],
            'poli_slug' => $poliSlug,
            'tanggal_kunjungan' => $dataAntrian['tanggal_kunjungan'],
            'penjamin' => $dataAntrian['penjamin'],
            'status' => $dataAntrian['status'],
        ]);

        // 3. Redirect
        return redirect()->route('kunjungan.index')
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
        // Terima masukan lengkap ('Laki-laki'/'Perempuan') dari form edit,
        // konversi ke singkatan agar validasi tetap bekerja (L/P)
        if ($request->has('jenis_kelamin')) {
            if ($request->input('jenis_kelamin') === 'Laki-laki') {
                $request->merge(['jenis_kelamin' => 'L']);
            } elseif ($request->input('jenis_kelamin') === 'Perempuan') {
                $request->merge(['jenis_kelamin' => 'P']);
            }
        }

        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik,' . $id,
            'no_rm' => 'required|unique:pasiens,no_rm,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        // Map short gender values (L/P) to DB enum values (Laki-laki/Perempuan)
        if (isset($validated['jenis_kelamin'])) {
            $validated['jenis_kelamin'] = $validated['jenis_kelamin'] === 'L'
                ? 'Laki-laki'
                : ($validated['jenis_kelamin'] === 'P' ? 'Perempuan' : $validated['jenis_kelamin']);
        }

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