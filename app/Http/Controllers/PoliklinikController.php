<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Jika Anda menggunakan Model Pasien, pastikan di-import di sini
use App\Models\Pasien;
use App\Models\Pemeriksaan;
<<<<<<< HEAD
use App\Models\Kunjungan;
use App\Models\Resep;
=======
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4

class PoliklinikController extends Controller
{
    /**
     * Tampilkan daftar kunjungan (pasien terdaftar) untuk poliklinik
     */
    public function daftarKunjungan()
    {
        $kunjungans = Kunjungan::with('pasien')
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('poliklinik.daftar_kunjungan', compact('kunjungans'));
    }

    /**
     * Tampilkan form pemeriksaan dengan data pasien ter-populate dari kunjungan
     */
    public function pemeriksaanKunjungan($kunjunganId)
    {
        $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);

        // Jika sudah ada pemeriksaan, redirect ke edit
        if ($kunjungan->pemeriksaan_id) {
            return redirect()->route('poliklinik.edit_kunjungan', $kunjungan->pemeriksaan_id);
        }

        return view('poliklinik.form_pemeriksaan_kunjungan', compact('kunjungan'));
    }

    /**
     * Simpan pemeriksaan dari kunjungan
     */
    public function simpanPemeriksaanKunjungan(Request $request, $kunjunganId)
    {
        $kunjungan = Kunjungan::findOrFail($kunjunganId);

        $validated = $request->validate([
            'keluhan_utama' => 'required|string',
            'riwayat_penyakit' => 'nullable|string',
            'suhu' => 'nullable|numeric|between:35,42',
            'tekanan_darah' => 'nullable|string',
            'nadi' => 'nullable|integer',
            'respirasi' => 'nullable|integer',
            'diagnosa' => 'required|string',
            'terapi' => 'required|string',
            'rujukan' => 'nullable|string',
            'resep_items' => 'nullable|array',
        ]);

        // Buat record pemeriksaan
        $pemeriksaan = Pemeriksaan::create([
            'no_rm' => $kunjungan->no_rm,
            'nama' => $kunjungan->pasien->nama,
            'keluhan_utama' => $validated['keluhan_utama'],
            'riwayat_penyakit' => $validated['riwayat_penyakit'],
            'suhu' => $validated['suhu'],
            'tekanan_darah' => $validated['tekanan_darah'],
            'nadi' => $validated['nadi'],
            'respirasi' => $validated['respirasi'],
            'diagnosa' => $validated['diagnosa'],
            'terapi' => $validated['terapi'],
            'rujukan' => $validated['rujukan'],
        ]);

        // Update kunjungan: link ke pemeriksaan dan ubah status
        $kunjungan->update([
            'pemeriksaan_id' => $pemeriksaan->id,
            'status' => 'selesai',
        ]);

        // Jika ada resep obat, buat resep otomatis
        $resepItems = $validated['resep_items'] ?? [];
        $resepItems = array_filter($resepItems, function($item){
            return !empty($item['name']);
        });

        if(!empty($resepItems)){
            $totalBiaya = 0;
            foreach($resepItems as $item){
                $totalBiaya += ((int)($item['qty'] ?? 0)) * ((float)($item['price'] ?? 0));
            }

            $lastResep = Resep::orderBy('id', 'desc')->first();
            $nextId = $lastResep ? $lastResep->id + 1 : 1;
            $noResep = 'RES-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $resep = Resep::create([
                'pemeriksaan_id' => $pemeriksaan->id,
                'no_resep' => $noResep,
                'items' => json_encode($resepItems),
                'total_biaya' => $totalBiaya,
                'status' => 'Pending', // Menunggu diproses apotek
            ]);

            // Redirect ke apotek untuk proses resep
            return redirect()->route('apotek.index')
                ->with('success', '✅ Pemeriksaan & resep pasien ' . $kunjungan->pasien->nama . ' berhasil disimpan. Silakan proses resep di Apotek.')
                ->with('resep_id', $resep->id);
        }

        return redirect()->route('poliklinik.daftar_kunjungan')
            ->with('success', '✅ Pemeriksaan pasien ' . $kunjungan->pasien->nama . ' berhasil disimpan!');
    }

    /**
     * Tampilkan form pemeriksaan khusus untuk poli tujuan dari kunjungan
     * Route: /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa
     */

    public function periksaKunjunganByPoli($kunjunganId)
    {
        $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);

        // Jika sudah ada pemeriksaan, redirect ke halaman edit
        if ($kunjungan->pemeriksaan_id) {
            return redirect()->route('kunjungan.edit', $kunjungan->pemeriksaan_id)
                ->with('info', 'Pasien ini sudah pernah diperiksa. Berikut data pemeriksaannya:');
        }

        // Update status kunjungan menjadi 'proses'
        $kunjungan->update(['status' => 'proses']);

        return view('poliklinik.form_pemeriksaan_kunjungan', compact('kunjungan'));
    }
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
     * Tampilkan daftar tunggu berdasarkan poli slug (umum, gigi, kia)
     */
    public function daftarKunjunganByPoli($poli_slug)
    {
        // Peta slug ke nama poli yang tersimpan di DB
        $map = [
            'umum' => 'Poli Umum',
            'gigi' => 'Poli Gigi & Mulut',
            'kia'  => 'Poli KIA / KB'
        ];

        $poli_name = $map[$poli_slug] ?? null;
        if (!$poli_name) {
            return redirect()->route('poliklinik.daftar_kunjungan')
                ->with('error', 'Poliklinik tidak ditemukan.');
        }

        $kunjungans = Kunjungan::with('pasien')
            ->where('poli', $poli_name)
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('tanggal_kunjungan', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('poliklinik.daftar_kunjungan', compact('kunjungans'))
            ->with('filter_poli', $poli_name);
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
        // Log incoming request to help debug missing saves
        \Log::info('Pemeriksaan request incoming', $request->all());

        // Validasi input
        $validated = $request->validate([
            'pasien_id' => 'required|numeric',
            'poli_slug' => 'required|string',
            'keluhan_utama' => 'required|string',
            'td' => 'nullable|string',
            'suhu' => 'nullable|numeric',
            'nadi' => 'nullable|integer',
            'rr' => 'nullable|integer',
            'diagnosis' => 'required|string',
            'terapi' => 'nullable|string',
            'action' => 'required|string|in:save_only,save_and_next',
        ]);

        // Log validated data
        \Log::info('Pemeriksaan validated', $validated);

        // Ambil data pasien nyata dari tabel pasiens
        $pasien = Pasien::find($validated['pasien_id']);
        if (!$pasien) {
            return redirect()->back()->withInput()->with('error', 'Pasien tidak ditemukan. Silakan pilih pasien terlebih dahulu.');
        }

        // Simpan pemeriksaan ke tabel 'pemeriksaan' menggunakan model Pemeriksaan
        $pemeriksaan = Pemeriksaan::create([
            'no_rm' => $pasien->no_rm,
            'nama' => $pasien->nama,
            'keluhan_utama' => $validated['keluhan_utama'],
            'riwayat_penyakit' => $request->input('riwayat_penyakit_sekarang') ?? $request->input('riwayat_penyakit_dahulu') ?? null,
            'suhu' => $validated['suhu'] ?? null,
            'tekanan_darah' => $validated['td'] ?? null,
            'nadi' => $validated['nadi'] ?? null,
            'respirasi' => $validated['rr'] ?? null,
            'diagnosa' => $validated['diagnosis'],
            'terapi' => $validated['terapi'] ?? null,
            'rujukan' => null,
        ]);

        // Jika pengguna memilih lanjut ke resep (apotek), arahkan dengan ID pemeriksaan nyata
        if ($validated['action'] === 'save_and_next') {
            return redirect()->route('apotek.resep.create', ['pemeriksaan_id' => $pemeriksaan->id])
                         ->with('success', 'Pemeriksaan berhasil disimpan. Lanjutkan ke form Resep Obat.');
        }

        return redirect()->route('poliklinik')->with('success', 'Pemeriksaan berhasil disimpan, pasien siap dirujuk atau pulang.');
    }

    /**
     * Tampilkan daftar kunjungan/pemeriksaan terbaru.
     */
    public function kunjungan()
    {
        // Ambil pemeriksaan terbaru, gunakan pagination (15 per halaman)
        $kunjungan = Pemeriksaan::orderBy('created_at', 'desc')->paginate(15);

        return view('poliklinik.kunjungan', compact('kunjungan'));
    }

    /**
     * Tampilkan formulir edit untuk satu kunjungan/pemeriksaan.
     */
    public function editKunjungan($id)
    {
        $p = Pemeriksaan::findOrFail($id);
        return view('poliklinik.edit_kunjungan', ['pemeriksaan' => $p]);
    }

    /**
     * Update data pemeriksaan.
     */
    public function updateKunjungan(Request $request, $id)
    {
        $validated = $request->validate([
            'keluhan_utama' => 'required|string',
            'diagnosa' => 'required|string',
            'terapi' => 'nullable|string',
            'suhu' => 'nullable|numeric',
            'td' => 'nullable|string',
            'nadi' => 'nullable|integer',
            'rr' => 'nullable|integer',
            'rujukan' => 'nullable|string',
        ]);

        $p = Pemeriksaan::findOrFail($id);
        $p->keluhan_utama = $validated['keluhan_utama'];
        $p->diagnosa = $validated['diagnosa'];
        $p->terapi = $validated['terapi'] ?? null;
        $p->suhu = $validated['suhu'] ?? null;
        $p->tekanan_darah = $validated['td'] ?? null;
        $p->nadi = $validated['nadi'] ?? null;
        $p->respirasi = $validated['rr'] ?? null;
        $p->rujukan = $validated['rujukan'] ?? null;
        $p->save();

        return redirect()->route('kunjungan.index')->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    /**
     * Hapus satu kunjungan/pemeriksaan.
     */
    public function destroyKunjungan($id)
    {
        $p = Pemeriksaan::findOrFail($id);
        $p->delete();

        return redirect()->route('kunjungan.index')->with('success', 'Kunjungan berhasil dihapus.');
    }
}
