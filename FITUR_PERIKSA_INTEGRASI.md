# 📋 Dokumentasi Fitur "PERIKSA" - Integrasi Pendaftaran ke Poliklinik

## Daftar Isi
1. [Pengenalan Fitur](#pengenalan-fitur)
2. [Alur Kerja Lengkap](#alur-kerja-lengkap)
3. [Komponen Teknis](#komponen-teknis)
4. [Testing & Verifikasi](#testing--verifikasi)
5. [Troubleshooting](#troubleshooting)

---

## Pengenalan Fitur

### Tujuan
Mengintegrasikan sistem **Pendaftaran Pasien (Pendaftaran Module)** dengan **Poliklinik (Clinic Module)** sehingga:
- ✅ Pasien didaftar sekali di Pendaftaran dengan pemilihan poli tujuan
- ✅ Data otomatis tersimpan di tabel `kunjungans` (tidak ada duplikasi input)
- ✅ Petugas Poliklinik melihat daftar pasien di "Daftar Kunjungan"
- ✅ Klik tombol "Periksa" → langsung ke form pemeriksaan dengan data pasien **pre-populated**
- ✅ Form pemeriksaan otomatis tersimpan dan linked ke poli tujuan

### User Story
```
SEBAGAI: Petugas Poliklinik
SAYA INGIN: Melihat pasien terdaftar tanpa perlu re-entry data
SEHINGGA: Proses pemeriksaan lebih cepat dan tidak ada kesalahan duplikasi input
```

---

## Alur Kerja Lengkap

### 1️⃣ Fase Pendaftaran (Pendaftaran Module)

#### Langkah A: Pasien Mendaftar
```
Petugas Pendaftaran → Klik "Pendaftaran Pasien Baru"
   ↓
Form Input:
  - NIK, Nama, Alamat
  - Jenis Kelamin, Tanggal Lahir, No Telepon
  - ✨ NEW: Pilih Poli Tujuan (Umum/Gigi/KIA)
  - ✨ NEW: Pilih Dokter/Perawat (optional)
  - ✨ NEW: Tanggal Kunjungan (default: hari ini)
  - ✨ NEW: Keluhan Utama
   ↓
Submit
```

#### Langkah B: Sistem Menyimpan Data
```
PasienController::store()
  1. Buat record di tabel `pasiens` (generate No RM otomatis)
  2. ✨ BARU: Buat record di tabel `kunjungans`:
     - pasien_id: (ID pasien yang baru dibuat)
     - no_rm: (dari pasien)
     - poli: "Poli Umum" / "Poli Gigi & Mulut" / "Poli KIA/KB"
     - dokter: (dari form)
     - tanggal_kunjungan: (dari form)
     - keluhan_utama: (dari form)
     - status: 'pending' (menunggu pemeriksaan)
  3. Redirect ke halaman sukses
```

**Database Impact:**
```sql
-- Tabel kunjungans baru terisi:
INSERT INTO kunjungans (pasien_id, no_rm, poli, dokter, tanggal_kunjungan, keluhan_utama, status)
VALUES (1, 'RM00001', 'Poli Umum', 'Dr. Budi', '2025-12-04', 'Sakit Kepala', 'pending');
```

---

### 2️⃣ Fase Poliklinik - Daftar Kunjungan

#### Langkah A: Petugas Poliklinik Login
```
Petugas Poliklinik → Dashboard
   ↓
Menu "Poliklinik" → Sub-menu "Daftar Kunjungan"
   ↓
Route: GET /poliklinik/daftar-kunjungan
Controller: PoliklinikController::daftarKunjungan()
```

#### Langkah B: Tampilkan Daftar Kunjungan
```
View: resources/views/poliklinik/daftar_kunjungan.blade.php
Tampilkan Tabel:
  - No RM | Nama Pasien | Poli | Dokter | Tgl Kunjungan | Keluhan | Status | [PERIKSA]
  
Status Badge:
  - 🟡 pending (kuning) - belum periksa
  - 🔵 proses (biru) - sedang periksa
  - 🟢 selesai (hijau) - pemeriksaan selesai
  - 🔴 batal (merah) - kunjungan dibatalkan

Pagination: 20 per halaman
```

**Output Contoh:**
```
No RM  | Nama Pasien    | Poli        | Dokter      | Tanggal    | Keluhan          | Status  | Aksi
-------|----------------|-------------|-------------|------------|------------------|---------|----------
RM001  | Rina Sofiana   | Poli Umum   | Dr. Budi    | 04-12-2025 | Sakit Kepala     | Pending | [PERIKSA]
RM002  | Ahmad Hidayat  | Poli Gigi   | drg. Siti   | 04-12-2025 | Gigi Berlubang   | Pending | [PERIKSA]
RM003  | Siti Nur       | Poli KIA/KB | Ibu Nita    | 04-12-2025 | Konsultasi KB    | Proses  | -
```

---

### 3️⃣ Fase Poliklinik - Periksa (NEW FEATURE ✨)

#### Langkah A: Klik Tombol "PERIKSA"
```
Petugas Poliklinik → Klik tombol "PERIKSA" di baris pasien
   ↓
Route: GET /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa
       (contoh: /poliklinik/poli-umum/kunjungan/1/periksa)
   ↓
Controller: PoliklinikController::periksaKunjunganByPoli($poli, $kunjunganId)
```

#### Langkah B: Validasi & Update Status
```
Controller Logic:
  1. Fetch kunjungan dengan relasi pasien
  2. Validasi: poli di URL = poli di kunjungan
  3. Cek: Apakah sudah ada pemeriksaan?
     - YA → Redirect ke halaman edit (data sudah ada)
     - TIDAK → Lanjut ke step 4
  4. ✨ Update status: kunjungan.status = 'proses' (sedang diproses)
  5. Return view form pemeriksaan
```

#### Langkah C: Tampilkan Form Pemeriksaan (Pre-Populated ✨)
```
View: resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php
Route: GET /poliklinik/kunjungan/{kunjunganId}/pemeriksaan

Kartu Informasi Pasien (READ-ONLY):
  ┌─────────────────────────────────────┐
  │ 📋 INFORMASI PASIEN                 │
  │─────────────────────────────────────│
  │ No RM: RM00001                      │
  │ Nama: Rina Sofiana                  │
  │ Jenis Kelamin: Perempuan            │
  │ Umur: 35 tahun                      │
  │ Alamat: Jl. Merdeka No 123          │
  │ No Telepon: 0812-3456-7890          │
  │─────────────────────────────────────│
  │ Poli Tujuan: Poli Umum              │
  │ Dokter/Perawat: Dr. Budi            │
  └─────────────────────────────────────┘

Form Anamnesis (INPUT):
  ├─ Keluhan Utama: [Sakit Kepala] ← PRE-FILLED dari kunjungan
  └─ Riwayat Penyakit: [____________] (optional)

Form Pemeriksaan Fisik (INPUT):
  ├─ Suhu Tubuh (°C): [___]
  ├─ Tekanan Darah: [___] mmHg
  ├─ Nadi: [___] x/menit
  └─ Respirasi: [___] x/menit

Form Diagnosa & Terapi (INPUT):
  ├─ Diagnosa: [_________________________] (WAJIB)
  ├─ Terapi: [_________________________] (WAJIB)
  └─ Rujukan: [_________________________] (optional)

Tombol:
  [✅ Simpan Pemeriksaan]  [← Kembali]
```

#### Langkah D: Submit Form
```
Form POST: /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan
Controller: PoliklinikController::simpanPemeriksaanKunjungan()

Proses:
  1. Validasi semua input form
  2. Buat record baru di tabel `pemeriksaan`
  3. Link kunjungan ke pemeriksaan: kunjungan.pemeriksaan_id = pemeriksaan.id
  4. Update status kunjungan: status = 'selesai'
  5. Redirect ke daftar kunjungan dengan success message
```

**Database Impact:**
```sql
-- Tabel pemeriksaan:
INSERT INTO pemeriksaan (no_rm, nama, keluhan_utama, suhu, tekanan_darah, nadi, respirasi, diagnosa, terapi, rujukan)
VALUES ('RM00001', 'Rina Sofiana', 'Sakit Kepala', 36.5, '120/80', 80, 20, 'Migrain', 'Istirahat + Paracetamol', NULL);

-- Tabel kunjungans:
UPDATE kunjungans 
SET pemeriksaan_id = 1, status = 'selesai' 
WHERE id = 1;
```

---

## Komponen Teknis

### Database Schema

#### Tabel: `kunjungans`
```sql
CREATE TABLE kunjungans (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    pasien_id BIGINT UNSIGNED NOT NULL,
    no_rm VARCHAR(20) NOT NULL,
    poli VARCHAR(50),
    dokter VARCHAR(100),
    tanggal_kunjungan DATE,
    keluhan_utama TEXT,
    status ENUM('pending', 'proses', 'selesai', 'batal') DEFAULT 'pending',
    pemeriksaan_id BIGINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (pasien_id) REFERENCES pasiens(id) ON DELETE CASCADE,
    FOREIGN KEY (pemeriksaan_id) REFERENCES pemeriksaan(id) ON DELETE SET NULL,
    INDEX (poli, tanggal_kunjungan, status)
);
```

#### Relasi Model

**Pasien.php:**
```php
public function kunjungans()
{
    return $this->hasMany(Kunjungan::class, 'pasien_id');
}
```

**Kunjungan.php:**
```php
class Kunjungan extends Model
{
    protected $fillable = [
        'pasien_id', 'no_rm', 'poli', 'dokter', 
        'tanggal_kunjungan', 'keluhan_utama', 'status', 'pemeriksaan_id'
    ];
    
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
    
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }
}
```

### Routes

| Method | Route | Controller Method | Nama Route | Deskripsi |
|--------|-------|-------------------|-----------|-----------|
| GET | `/poliklinik/daftar-kunjungan` | `daftarKunjungan()` | `poliklinik.daftar_kunjungan` | Tampilkan daftar kunjungan |
| GET | `/poliklinik/{poli}/kunjungan/{kunjunganId}/periksa` | `periksaKunjunganByPoli()` | `poliklinik.periksa_by_poli` | **NEW**: Periksa pasien ke poli spesifik |
| GET | `/poliklinik/kunjungan/{kunjunganId}/pemeriksaan` | `pemeriksaanKunjungan()` | `poliklinik.pemeriksaan_kunjungan` | Tampilkan form pemeriksaan |
| POST | `/poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan` | `simpanPemeriksaanKunjungan()` | `poliklinik.simpan_pemeriksaan_kunjungan` | Simpan hasil pemeriksaan |

### Controller Methods

#### 1. `daftarKunjungan()`
```php
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
```

#### 2. `periksaKunjunganByPoli()` - **NEW ✨**
```php
/**
 * Tampilkan form pemeriksaan khusus untuk poli tujuan dari kunjungan
 */
public function periksaKunjunganByPoli($poli, $kunjunganId)
{
    $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);

    // Validasi poli
    $poliNormalized = str_replace(' ', '-', strtolower($kunjungan->poli));
    if ($poli !== $poliNormalized) {
        return redirect()->route('poliklinik.daftar_kunjungan')
            ->with('error', 'Poli tidak sesuai dengan kunjungan ini.');
    }

    // Jika sudah ada pemeriksaan, redirect ke halaman edit
    if ($kunjungan->pemeriksaan_id) {
        return redirect()->route('kunjungan.edit', $kunjungan->pemeriksaan_id)
            ->with('info', 'Pasien ini sudah pernah diperiksa.');
    }

    // Update status kunjungan menjadi 'proses'
    $kunjungan->update(['status' => 'proses']);

    return view('poliklinik.form_pemeriksaan_kunjungan', compact('kunjungan'));
}
```

#### 3. `simpanPemeriksaanKunjungan()`
```php
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

    // Update kunjungan
    $kunjungan->update([
        'pemeriksaan_id' => $pemeriksaan->id,
        'status' => 'selesai',
    ]);

    return redirect()->route('poliklinik.daftar_kunjungan')
        ->with('success', '✅ Pemeriksaan pasien ' . $kunjungan->pasien->nama . ' berhasil disimpan!');
}
```

---

## Testing & Verifikasi

### Test Case 1: Registrasi Pasien Baru
```
STEP 1: Akses form pendaftaran
  - URL: http://localhost:8000/pendaftaran/pasien-baru
  - Expected: Form muncul dengan field poli tujuan

STEP 2: Isi form dengan data:
  - NIK: 1234567890123456
  - Nama: Test Pasien
  - Alamat: Jl. Test
  - Jenis Kelamin: Laki-laki
  - Tanggal Lahir: 1990-01-01
  - No Telepon: 081234567890
  - Poli Tujuan: Poli Umum
  - Keluhan Utama: Sakit Kepala
  - Tanggal Kunjungan: 2025-12-04

STEP 3: Submit
  - Expected: Redirect ke data master dengan success message
  - Check Database: 
    - Pasien tercipta di tabel pasiens
    - Kunjungan tercipta di tabel kunjungans dengan status='pending'

STEP 4: Verifikasi data di database
  ```sql
  SELECT * FROM kunjungans WHERE pasien_id = (SELECT id FROM pasiens WHERE nama = 'Test Pasien');
  ```
  - Expected: 1 row dengan status='pending'
```

### Test Case 2: Daftar Kunjungan
```
STEP 1: Login sebagai petugas poliklinik
  - Email: dokter@klinik.com atau perawat@klinik.com
  - Password: (sesuai seeder)

STEP 2: Akses Daftar Kunjungan
  - URL: http://localhost:8000/poliklinik/daftar-kunjungan
  - Expected: Tabel dengan list kunjungan status='pending'/'proses'

STEP 3: Verifikasi kolom
  - No RM: RM00001 (atau sesuai pasien)
  - Nama Pasien: Test Pasien
  - Poli Tujuan: Poli Umum
  - Status: Pending (badge kuning)
  - Tombol: PERIKSA (visible dan clickable)
```

### Test Case 3: Periksa - Integrasi **NEW ✨**
```
STEP 1: Klik tombol PERIKSA di daftar kunjungan
  - Expected: Redirect ke form pemeriksaan

STEP 2: Verifikasi form pre-populated
  - No RM: RM00001 (dari kunjungan)
  - Nama: Test Pasien (dari kunjungan)
  - Poli Tujuan: Poli Umum (dari kunjungan, read-only)
  - Keluhan Utama: Sakit Kepala (dari kunjungan)

STEP 3: Cek database setelah klik PERIKSA
  ```sql
  SELECT * FROM kunjungans WHERE id = 1;
  ```
  - Expected: status='proses' (sudah diupdate)

STEP 4: Isi form pemeriksaan
  - Keluhan Utama: [Edit jika perlu]
  - Suhu: 36.5
  - Tekanan Darah: 120/80
  - Nadi: 80
  - Respirasi: 20
  - Diagnosa: Migrain
  - Terapi: Istirahat + Paracetamol

STEP 5: Submit form
  - Expected: Redirect ke daftar kunjungan dengan success message

STEP 6: Verifikasi database
  ```sql
  SELECT * FROM pemeriksaan WHERE no_rm = 'RM00001';
  SELECT * FROM kunjungans WHERE id = 1;
  ```
  - Expected: 
    - Pemeriksaan tercipta dengan data yang diinput
    - Kunjungan.pemeriksaan_id = pemeriksaan.id
    - Kunjungan.status = 'selesai'

STEP 7: Kembali ke daftar kunjungan
  - Expected: Status berubah menjadi "Selesai" (badge hijau)
  - Tombol PERIKSA: Tidak visible (hanya "-" atau disabled)
```

### Automated Test Script

**File: `test_periksa_feature.php`**
```php
<?php

require 'vendor/autoload.php';

// Test data
$testResults = [
    'kunjungan_dibuat' => false,
    'status_berubah_proses' => false,
    'pemeriksaan_tersimpan' => false,
    'status_berubah_selesai' => false,
];

echo "=== TEST: Fitur PERIKSA Integrasi ===\n";

// 1. Cek kunjungan dibuat
$kunjungan = DB::table('kunjungans')->where('status', 'pending')->first();
if ($kunjungan) {
    $testResults['kunjungan_dibuat'] = true;
    echo "✅ Kunjungan ditemukan: ID={$kunjungan->id}, Status={$kunjungan->status}\n";
} else {
    echo "❌ Tidak ada kunjungan pending\n";
}

// 2. Cek status berubah ke proses
$kunjungan = DB::table('kunjungans')->find(1);
if ($kunjungan && $kunjungan->status === 'proses') {
    $testResults['status_berubah_proses'] = true;
    echo "✅ Status berubah ke proses\n";
}

// 3. Cek pemeriksaan tersimpan
$pemeriksaan = DB::table('pemeriksaan')->where('no_rm', 'RM00001')->first();
if ($pemeriksaan) {
    $testResults['pemeriksaan_tersimpan'] = true;
    echo "✅ Pemeriksaan tersimpan: ID={$pemeriksaan->id}\n";
}

// 4. Cek status kembali ke selesai
$kunjungan = DB::table('kunjungans')->where('pemeriksaan_id', $pemeriksaan->id ?? null)->first();
if ($kunjungan && $kunjungan->status === 'selesai') {
    $testResults['status_berubah_selesai'] = true;
    echo "✅ Status berubah ke selesai\n";
}

// Summary
echo "\n=== SUMMARY ===\n";
$passed = array_sum(array_values($testResults));
$total = count($testResults);
echo "$passed/$total tests passed\n";

if ($passed === $total) {
    echo "✅ Semua test PASSED! Fitur PERIKSA siap digunakan.\n";
    exit(0);
} else {
    echo "❌ Ada test yang gagal. Silakan periksa kembali.\n";
    exit(1);
}
?>
```

---

## Troubleshooting

### ❌ Error: "Poli tidak sesuai dengan kunjungan ini"

**Penyebab:** URL poli tidak sesuai dengan poli di database kunjungan.

**Solusi:**
- Verifikasi format poli di database (harus "Poli Umum", "Poli Gigi & Mulut", "Poli KIA/KB")
- Cek transformasi string: `str_replace(' ', '-', strtolower('Poli Umum'))` = `'poli-umum'`
- Sesuaikan URL route

```php
// Verifikasi di controller
$poliNormalized = str_replace(' ', '-', strtolower($kunjungan->poli));
\Log::info("Poli dari URL: {$poli}, Poli dari DB (normalized): {$poliNormalized}");
```

### ❌ Tombol PERIKSA tidak muncul

**Penyebab:** Status kunjungan bukan 'pending' atau 'proses', atau sudah ada pemeriksaan.

**Solusi:**
```blade
@if ($kunjungan->status !== 'selesai' && $kunjungan->status !== 'batal')
    <!-- Tombol visible -->
@else
    <!-- Tombol tidak visible -->
@endif
```

**Cek database:**
```sql
SELECT id, status, pemeriksaan_id FROM kunjungans WHERE id = 1;
```

### ❌ Data pasien tidak pre-filled di form

**Penyebab:** Relasi `kunjungan->pasien` tidak ter-load atau view tidak mengakses dengan benar.

**Solusi:**
```php
// Di controller: pastikan eager load
$kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);

// Di view: akses dengan benar
{{ $kunjungan->pasien->nama }}
{{ $kunjungan->keluhan_utama }}
```

### ❌ Route "periksa_by_poli" tidak ditemukan

**Penyebab:** Route belum ter-register atau typo di nama route.

**Solusi:**
```bash
php artisan route:clear
php artisan route:cache
php artisan route:list | Select-String "periksa"
```

Verifikasi di `routes/web.php`:
```php
Route::get('/poliklinik/{poli}/kunjungan/{kunjunganId}/periksa', 
    [PoliklinikController::class, 'periksaKunjunganByPoli']
)->name('poliklinik.periksa_by_poli');
```

---

## Kesimpulan

✅ **Fitur PERIKSA telah berhasil diintegrasikan:**
- Pendaftaran pasien → Otomatis membuat kunjungan record
- Daftar kunjungan → Tampil semua pasien terdaftar
- Tombol PERIKSA → Langsung ke form pemeriksaan dengan data pre-filled
- Form pemeriksaan → Auto-simpan ke database tanpa duplikasi input
- Status tracking → pending → proses → selesai

🎯 **Benefit:**
- Zero duplikasi data input
- Proses pemeriksaan lebih cepat
- Integrasi seamless antara Pendaftaran & Poliklinik
- User experience lebih baik

---

**Last Updated:** 4 December 2025  
**Status:** ✅ READY FOR PRODUCTION
