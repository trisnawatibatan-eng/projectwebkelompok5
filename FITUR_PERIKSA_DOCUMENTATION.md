# FITUR PERIKSA - Dokumentasi Implementasi

**Tanggal**: 4 Desember 2025  
**Status**: ✅ SELESAI

## 📋 Deskripsi Fitur

Fitur "Periksa" memungkinkan petugas poliklinik untuk langsung melakukan pemeriksaan pasien dari daftar kunjungan, dengan data pasien otomatis ter-populate ke form pemeriksaan sesuai dengan poli tujuan pasien.

## 🎯 User Flow

```
Pasien Baru Didaftar (Pendaftaran Module)
    ↓
Kunjungan Record Dibuat (Poli Umum, Poli Gigi, atau Poli KIA/KB)
    ↓
Petugas Poli Buka "Daftar Kunjungan"
    ↓
Petugas Klik Tombol "Periksa" pada Pasien
    ↓
Form Pemeriksaan Terbuka (Pre-populated dengan data pasien & poli tujuan)
    ↓
Petugas Isi Data Pemeriksaan (vital signs, diagnosis, therapy)
    ↓
Petugas Klik "Simpan Pemeriksaan"
    ↓
Pemeriksaan Record Dibuat
    ↓
Kunjungan Status Berubah ke "Selesai"
```

## 🛠️ Implementasi Teknis

### 1. Database Structure

**Tabel `kunjungans`**:
- Menyimpan catatan kunjungan pasien ke poli tujuan
- Fields utama: pasien_id, no_rm, poli, dokter, tanggal_kunjungan, keluhan_utama, status, pemeriksaan_id

### 2. Models & Relationships

**Pasien Model**:
```php
public function kunjungans()
{
    return $this->hasMany(Kunjungan::class, 'pasien_id');
}
```

**Kunjungan Model**:
```php
public function pasien()
{
    return $this->belongsTo(Pasien::class, 'pasien_id');
}

public function pemeriksaan()
{
    return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
}
```

### 3. Route Definition

```php
// Route untuk langsung ke form pemeriksaan dengan poli spesifik
Route::get('/poliklinik/{poli}/kunjungan/{kunjunganId}/periksa', 
    [PoliklinikController::class, 'periksaKunjunganByPoli'])->name('poliklinik.periksa_by_poli');
```

### 4. Controller Method

**PoliklinikController::periksaKunjunganByPoli()**:
- Validasi poli di URL sesuai dengan poli di kunjungan
- Cek apakah sudah ada pemeriksaan:
  - Jika ada: redirect ke halaman edit pemeriksaan
  - Jika tidak: lanjut ke step berikutnya
- Update status kunjungan dari "pending" → "proses"
- Return view `form_pemeriksaan_kunjungan` dengan data kunjungan

### 5. View Components

**daftar_kunjungan.blade.php**:
- Menampilkan tabel kunjungan dengan status pending/proses
- Tombol "Periksa" hanya tampil untuk status yang bukan "selesai" atau "batal"
- Tombol menggunakan route: `route('poliklinik.periksa_by_poli', ['poli' => formatted_poli, 'kunjunganId' => $kunjungan->id])`

**form_pemeriksaan_kunjungan.blade.php**:
- Pre-populated fields dari kunjungan:
  - No RM: `{{ $kunjungan->no_rm }}`
  - Nama: `{{ $kunjungan->pasien->nama }}`
  - Umur: calculated from tanggal_lahir
  - Poli Tujuan: `{{ $kunjungan->poli }}`
  - Dokter/Perawat: `{{ $kunjungan->dokter }}`
  - Keluhan Utama: `{{ $kunjungan->keluhan_utama }}`
  - Tanggal Kunjungan: `{{ $kunjungan->tanggal_kunjungan }}`

- Input fields untuk pemeriksaan:
  - Keluhan Utama (editable)
  - Riwayat Penyakit
  - Vital Signs: Suhu, Tekanan Darah, Nadi, Respirasi
  - Diagnosa (required)
  - Terapi (required)
  - Rujukan (optional)

## 🔄 Status Workflow

```
pending  →  (Tombol Periksa diklik)  →  proses  →  (Simpan Pemeriksaan)  →  selesai
```

## 📊 Database State During Flow

### Initial State
```
Kunjungan:
- id: 1
- pasien_id: 4
- poli: "Poli Umum"
- status: "pending"
- pemeriksaan_id: NULL
```

### After Click "Periksa"
```
Kunjungan:
- id: 1
- status: "proses"  ← BERUBAH
- pemeriksaan_id: NULL (masih kosong)
```

### After Submit "Simpan Pemeriksaan"
```
Kunjungan:
- id: 1
- status: "selesai"  ← BERUBAH
- pemeriksaan_id: 5  ← DIISI dengan ID pemeriksaan baru

Pemeriksaan:
- id: 5
- no_rm: "RM00004"
- diagnosa: "Dari form"
- terapi: "Dari form"
```

## ✅ Validasi & Error Handling

1. **Poli Mismatch**: Jika URL poli tidak sesuai dengan poli di database, redirect dengan error
2. **Sudah Diperiksa**: Jika kunjungan sudah memiliki pemeriksaan_id, redirect ke edit page
3. **Validasi Form**: Semua required fields harus diisi sebelum submit
4. **Duplikasi**: Sistem mencegah pemeriksaan duplikat dengan check pemeriksaan_id

## 🧪 Test Data

**Existing Kunjungan**:
- ID: 1
- No RM: RM00004
- Pasien: (nama dari pasien_id 4)
- Poli: Poli Umum
- Status: pending
- Keluhan: fjdkdska

## 📍 File-File Terkait

| File | Status | Fungsi |
|------|--------|--------|
| `routes/web.php` | ✅ Updated | Route `poliklinik.periksa_by_poli` |
| `app/Models/Kunjungan.php` | ✅ Created | Model Kunjungan dengan relasi |
| `app/Http/Controllers/PoliklinikController.php` | ✅ Updated | Method `periksaKunjunganByPoli` |
| `resources/views/poliklinik/daftar_kunjungan.blade.php` | ✅ Updated | Tombol Periksa dengan route |
| `resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php` | ✅ Created | Form dengan pre-populated data |
| `database/migrations/2025_12_04_000001_create_kunjungans_table.php` | ✅ Created | Migration kunjungans |

## 🚀 Cara Testing

### Manual Test
1. Buka: `http://127.0.0.1:8000/poliklinik/daftar-kunjungan`
2. Lihat tabel dengan kolom: No RM, Nama, Poli, Dokter, Tanggal, Keluhan, Status, Aksi
3. Klik tombol "Periksa" pada baris pasien dengan status "Pending"
4. Verifikasi:
   - ✅ Form pemeriksaan terbuka
   - ✅ No RM, Nama, Poli ter-populate
   - ✅ Keluhan Utama ter-populate
   - ✅ Status di database berubah ke "proses" (check DB jika diperlukan)
5. Isi form (Diagnosa, Terapi, Vital Signs)
6. Klik "Simpan Pemeriksaan"
7. Verifikasi:
   - ✅ Pemeriksaan record dibuat
   - ✅ Status kunjungan berubah ke "selesai"
   - ✅ Tombol Periksa tidak tampil lagi untuk pasien ini

### SQL Verification

```sql
-- Check kunjungan status flow
SELECT id, no_rm, poli, status, pemeriksaan_id FROM kunjungans;

-- Check pemeriksaan linked
SELECT k.id as kunjungan_id, k.no_rm, p.id as pemeriksaan_id, p.diagnosa 
FROM kunjungans k 
LEFT JOIN pemeriksaan p ON k.pemeriksaan_id = p.id
WHERE k.status = 'selesai';
```

## 🎨 UI/UX Elements

**Tombol Periksa**:
- Icon: `<i class="bi bi-stethoscope"></i>`
- Color: `btn-primary` (biru)
- Size: `btn-sm` (small)
- Conditional: Hanya tampil untuk status selain "selesai" dan "batal"

**Form Title**: "Form Pemeriksaan - [Nama Pasien]"

**Status Badges**:
- Pending: `badge bg-warning` (kuning)
- Proses: `badge bg-info` (biru)
- Selesai: `badge bg-success` (hijau)
- Batal: `badge bg-danger` (merah)

## 📈 Future Enhancements

1. ✅ FUTURE: Integrasi dengan sistem apotek (resep otomatis dari diagnosa)
2. ✅ FUTURE: Riwayat pemeriksaan lengkap per pasien
3. ✅ FUTURE: PDF cetak hasil pemeriksaan
4. ✅ FUTURE: Reminder follow-up pasien
5. ✅ FUTURE: Multi-dokter assignment per kunjungan

---

**Terakhir Diperbarui**: 4 Desember 2025, 02:30 WIB  
**Verified By**: System Test  
**Status**: ✅ READY FOR PRODUCTION
