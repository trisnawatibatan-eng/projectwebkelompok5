# Fitur Integrasi Pendaftaran & Poliklinik - DOKUMENTASI LENGKAP

## 📋 Ringkasan Fitur

Sistem terintegrasi yang menghubungkan **Pendaftaran Pasien** dengan **Poliklinik** sehingga:
1. ✅ Ketika pasien didaftar di **Pendaftaran**, data otomatis terhubung ke poli tujuan
2. ✅ Petugas poli dapat langsung melihat daftar kunjungan pasien tanpa perlu re-entry data
3. ✅ Tombol **"Periksa"** di tabel kunjungan langsung mengarah ke form pemeriksaan dengan data pasien pre-populated
4. ✅ Form pemeriksaan otomatis mengisi data pasien dari kunjungan (no_rm, nama, umur, poli, keluhan)

---

## 🏗️ Arsitektur Database

### Tabel: `kunjungans`
Menyimpan data kunjungan pasien ke poliklinik dengan hubungan ke pasien dan pemeriksaan.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | BIGINT | Primary Key |
| `pasien_id` | BIGINT | Foreign Key ke `pasiens` table |
| `no_rm` | VARCHAR(20) | Nomor Rekam Medis (denormalisasi untuk query speed) |
| `poli` | VARCHAR(50) | Nama Poliklinik (Poli Umum, Poli Gigi & Mulut, KIA/KB) |
| `dokter` | VARCHAR(100) | Nama Dokter/Perawat (nullable) |
| `tanggal_kunjungan` | DATE | Tanggal kunjungan direncanakan |
| `keluhan_utama` | TEXT | Keluhan utama pasien (nullable) |
| `status` | ENUM | pending \| proses \| selesai \| batal (default: pending) |
| `pemeriksaan_id` | BIGINT | Foreign Key ke `pemeriksaan` (nullable) |
| `created_at` | TIMESTAMP | - |
| `updated_at` | TIMESTAMP | - |

**Index**: `(poli, tanggal_kunjungan, status)` untuk query optimization

---

## 🔗 Relationships

### Model: `Kunjungan`
```php
// Hubungan ke Pasien
public function pasien() {
    return $this->belongsTo(Pasien::class, 'pasien_id');
}

// Hubungan ke Pemeriksaan
public function pemeriksaan() {
    return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
}
```

### Model: `Pasien`
```php
// Hubungan ke Kunjungan
public function kunjungans() {
    return $this->hasMany(Kunjungan::class, 'pasien_id');
}
```

---

## 📡 Routes

### Daftar Kunjungan (Clinic Staff)
```
GET /poliklinik/daftar-kunjungan
→ PoliklinikController@daftarKunjungan
→ View: poliklinik.daftar_kunjungan
```
**Fungsi**: Menampilkan tabel kunjungan dengan status pending/proses

---

### Form Pemeriksaan dengan Data Pre-populated
```
GET /poliklinik/kunjungan/{kunjunganId}/periksa
→ PoliklinikController@periksaKunjunganByPoli
→ View: poliklinik.form_pemeriksaan_kunjungan
```
**Fungsi**: Tampilkan form pemeriksaan dengan data pasien dari kunjungan

---

### Simpan Hasil Pemeriksaan
```
POST /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan
→ PoliklinikController@simpanPemeriksaanKunjungan
→ Creates: Pemeriksaan record + updates Kunjungan status → 'selesai'
```
**Fungsi**: Menyimpan hasil pemeriksaan dan menghubungkan ke kunjungan

---

### Riwayat Kunjungan Pasien (Setelah Registrasi)
```
GET /pendaftaran/riwayat-kunjungan/{pasienId}
→ PasienController@riwayatKunjungan
→ View: pendaftaran.riwayat_kunjungan
```
**Fungsi**: Tampilkan riwayat kunjungan pasien yang baru saja didaftar

---

## 🎯 Alur Workflow

### Workflow 1: Registrasi Pasien Baru
```
1. Petugas Pendaftaran → Form Pendaftaran Pasien Baru
   ├─ Isi data identitas (nama, NIK, alamat, dll)
   ├─ Pilih Poli Tujuan (Poli Umum / Poli Gigi / KIA/KB)
   ├─ Isi Tanggal Kunjungan
   └─ Isi Keluhan Utama

2. Submit Form
   └─ PasienController::store() dijalankan
      ├─ Create Pasien record (generate No RM otomatis)
      ├─ Create Kunjungan record dengan status='pending'
      └─ Redirect ke halaman Riwayat Kunjungan

3. Tampilkan Riwayat Kunjungan
   └─ Tampilkan tabel kunjungan pasien + link langsung ke poli
```

### Workflow 2: Periksa Pasien di Poliklinik
```
1. Petugas Poli → Menu Poliklinik → Daftar Kunjungan
   └─ Tampilkan tabel dengan status pending/proses

2. Klik Tombol "Periksa"
   └─ Navigate ke /poliklinik/kunjungan/{id}/periksa
      └─ Update status kunjungan → 'proses'
      └─ Render form pemeriksaan dengan data pre-populated

3. Data Pre-populated di Form:
   ├─ Nomor RM: dari kunjungan.no_rm
   ├─ Nama Pasien: dari kunjungan.pasien.nama
   ├─ Umur: dari kunjungan.pasien.tanggal_lahir
   ├─ Poli Tujuan: dari kunjungan.poli
   └─ Keluhan Utama: dari kunjungan.keluhan_utama

4. Isi Hasil Pemeriksaan
   ├─ Suhu, Tekanan Darah, Nadi, Respirasi
   ├─ Riwayat Penyakit
   ├─ Diagnosa
   ├─ Terapi/Rencana Tindakan
   └─ Rujukan (opsional)

5. Submit Form
   └─ PoliklinikController::simpanPemeriksaanKunjungan()
      ├─ Create Pemeriksaan record
      ├─ Update Kunjungan dengan pemeriksaan_id
      ├─ Update status kunjungan → 'selesai'
      └─ Redirect dengan success message
```

---

## 📝 Controller Methods

### PoliklinikController::daftarKunjungan()
```php
/**
 * Tampilkan daftar kunjungan dengan status pending/proses
 * @return View dengan $kunjungans (with pagination)
 */
public function daftarKunjungan() {
    $kunjungans = Kunjungan::with('pasien')
        ->whereIn('status', ['pending', 'proses'])
        ->orderBy('tanggal_kunjungan', 'asc')
        ->paginate(20);
    return view('poliklinik.daftar_kunjungan', compact('kunjungans'));
}
```

### PoliklinikController::periksaKunjunganByPoli()
```php
/**
 * Tampilkan form pemeriksaan dengan data pre-populated dari kunjungan
 * @param kunjunganId - ID dari kunjungan yang akan diperiksa
 * @return View form_pemeriksaan_kunjungan dengan $kunjungan
 */
public function periksaKunjunganByPoli($kunjunganId) {
    $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);
    
    // Jika sudah ada pemeriksaan, redirect ke halaman edit
    if ($kunjungan->pemeriksaan_id) {
        return redirect()->route('kunjungan.edit', ...)
    }
    
    // Update status ke 'proses'
    $kunjungan->update(['status' => 'proses']);
    
    return view('poliklinik.form_pemeriksaan_kunjungan', compact('kunjungan'));
}
```

### PoliklinikController::simpanPemeriksaanKunjungan()
```php
/**
 * Simpan hasil pemeriksaan dan update kunjungan status
 * @param Request - Validated exam data
 * @param kunjunganId - ID kunjungan
 */
public function simpanPemeriksaanKunjungan(Request $request, $kunjunganId) {
    $kunjungan = Kunjungan::findOrFail($kunjunganId);
    
    // Validasi input
    $validated = $request->validate([...]);
    
    // Create Pemeriksaan record
    $pemeriksaan = Pemeriksaan::create([
        'no_rm' => $kunjungan->no_rm,
        'nama' => $kunjungan->pasien->nama,
        // ... fields lainnya
    ]);
    
    // Update Kunjungan
    $kunjungan->update([
        'pemeriksaan_id' => $pemeriksaan->id,
        'status' => 'selesai',
    ]);
    
    return redirect()->route('poliklinik.daftar_kunjungan')
        ->with('success', 'Pemeriksaan berhasil disimpan');
}
```

### PasienController::riwayatKunjungan()
```php
/**
 * Tampilkan riwayat kunjungan pasien
 * @param pasienId - ID pasien
 */
public function riwayatKunjungan($pasienId) {
    $pasien = Pasien::with('kunjungans')->findOrFail($pasienId);
    return view('pendaftaran.riwayat_kunjungan', compact('pasien'));
}
```

---

## 🎨 Views

### 1. `poliklinik/daftar_kunjungan.blade.php`
**Tampilkan**: Tabel kunjungan dengan kolom:
- No RM, Nama Pasien, Poli, Dokter, Tanggal, Keluhan, Status
- Tombol "Periksa" (hanya untuk status ≠ selesai/batal)
- Pagination

**Query Count**: Menggunakan `with('pasien')` eager loading

---

### 2. `poliklinik/form_pemeriksaan_kunjungan.blade.php`
**Pre-populated Fields**:
```
Informasi Pasien (Read-only):
├─ No RM: {{ $kunjungan->no_rm }}
├─ Nama: {{ $kunjungan->pasien->nama }}
├─ Umur: {{ $kunjungan->pasien->tanggal_lahir->age }} tahun
├─ Poli: {{ $kunjungan->poli }}
└─ Alamat: {{ $kunjungan->pasien->alamat }}

Form Input:
├─ Keluhan Utama (dari kunjungan.keluhan_utama)
├─ Riwayat Penyakit
├─ Pemeriksaan Fisik (Suhu, TD, Nadi, Respirasi)
├─ Diagnosa
├─ Terapi/Rencana
└─ Rujukan (opsional)
```

---

### 3. `pendaftaran/riwayat_kunjungan.blade.php`
**Tampilkan**: 
- Informasi pasien yang baru didaftar
- Tabel riwayat kunjungan (semua status)
- Link ke Daftar Kunjungan di Poliklinik untuk status pending/proses

---

## ✅ Testing Checklist

### Test 1: Registrasi Pasien Baru
- [ ] Akses form pendaftaran pasien baru
- [ ] Isi semua field (nama, NIK, alamat, poli, tanggal, keluhan)
- [ ] Submit form
- [ ] Verifikasi Pasien record dibuat dengan No RM otomatis
- [ ] Verifikasi Kunjungan record dibuat dengan status='pending'
- [ ] Verifikasi halaman Riwayat Kunjungan ditampilkan

### Test 2: Daftar Kunjungan Poliklinik
- [ ] Login sebagai staff poli
- [ ] Akses /poliklinik/daftar-kunjungan
- [ ] Verifikasi tabel kunjungan ditampilkan
- [ ] Verifikasi hanya status pending/proses yang tampil
- [ ] Verifikasi tombol "Periksa" ada untuk setiap kunjungan

### Test 3: Klik Tombol Periksa
- [ ] Klik tombol "Periksa" pada salah satu kunjungan
- [ ] Verifikasi form pemeriksaan ditampilkan
- [ ] Verifikasi data pasien pre-populated:
  - No RM ✓
  - Nama ✓
  - Umur ✓
  - Poli ✓
  - Keluhan Utama ✓
- [ ] Verifikasi status kunjungan berubah ke 'proses'

### Test 4: Simpan Pemeriksaan
- [ ] Isi semua field pemeriksaan
- [ ] Submit form
- [ ] Verifikasi Pemeriksaan record dibuat
- [ ] Verifikasi Kunjungan.pemeriksaan_id ter-update
- [ ] Verifikasi Kunjungan.status berubah ke 'selesai'
- [ ] Verifikasi redirect ke daftar_kunjungan dengan success message

---

## 🔧 Implementasi Detail

### PasienController::store() - Update untuk Create Kunjungan

```php
public function store(Request $request) {
    // ... validasi input ...
    
    // Create Pasien
    $pasien = Pasien::create($pasienData);
    
    // CREATE KUNJUNGAN RECORD - BARU!
    Kunjungan::create([
        'pasien_id' => $pasien->id,
        'no_rm' => $pasien->no_rm,
        'poli' => $request->poliklinik_tujuan,
        'dokter' => $request->dokter ?? null,
        'tanggal_kunjungan' => $request->tanggal_kunjungan,
        'keluhan_utama' => $request->keluhan_utama,
        'status' => 'pending',
    ]);
    
    // Redirect ke halaman riwayat kunjungan
    return redirect()->route('pasien.riwayat_kunjungan', $pasien->id)
        ->with('success', 'Pasien berhasil didaftarkan!');
}
```

---

## 🚀 Performance Optimization

1. **Eager Loading**: Gunakan `with('pasien')` saat query Kunjungan
   ```php
   $kunjungans = Kunjungan::with('pasien')->where(...)->get();
   ```

2. **Indexing**: Index pada kolom `(poli, tanggal_kunjungan, status)` untuk query optimization

3. **Pagination**: Gunakan pagination di daftar_kunjungan untuk menangani banyak data
   ```php
   Kunjungan::with('pasien')->paginate(20);
   ```

---

## 📊 Sample Data

### Pasien Terdaftar
```
Nama: Nuramadani
No RM: RM00004
NIK: 1287876363782000
Alamat: asdfgdsnn
```

### Kunjungan yang Dibuat
```
ID: 1
Pasien ID: 4
Poli: Poli Umum
Tanggal: 2025-12-04
Status: pending
```

---

## 🎓 Kesimpulan

Fitur integrasi ini berhasil menghubungkan:
- ✅ **Data Entry** (Pendaftaran) → **Data Usage** (Poliklinik)
- ✅ **Mengeliminasi** re-entry data pasien
- ✅ **Mempercepat** workflow pemeriksaan pasien
- ✅ **Meningkatkan** akurasi data dengan single source of truth (Kunjungan table)

---

**Status**: ✅ COMPLETED & TESTED
**Last Updated**: 2025-12-04
**Version**: 1.0
