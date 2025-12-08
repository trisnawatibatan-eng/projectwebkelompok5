# 📋 FITUR KUNJUNGAN INTEGRATION - DOKUMENTASI LENGKAP

## 🎯 Tujuan Fitur
Menghubungkan sistem Pendaftaran Pasien dengan Poliklinik sehingga:
- Ketika pasien didaftar di modul Pendaftaran, data otomatis masuk ke sistem Kunjungan
- Petugas Poliklinik dapat melihat daftar pasien yang menunggu pemeriksaan
- Petugas dapat langsung klik tombol "Periksa" untuk membuka form pemeriksaan dengan data pasien sudah ter-populate
- Tidak perlu re-entry data pasien di Poliklinik

## ✅ Fitur yang Sudah Diimplementasikan

### 1. Database Schema - Tabel `kunjungans`
**File:** `database/migrations/2025_12_04_000001_create_kunjungans_table.php`

Struktur Tabel:
```
- id (Primary Key)
- pasien_id (Foreign Key → pasiens)
- no_rm (string 20) - Nomor Rekam Medis
- poli (string 50) - Nama Poliklinik Tujuan
- dokter (string 100, nullable) - Nama Dokter/Perawat
- tanggal_kunjungan (date) - Tanggal Kunjungan
- keluhan_utama (text, nullable) - Keluhan Utama Pasien
- status (enum) - pending, proses, selesai, batal (default: pending)
- pemeriksaan_id (FK → pemeriksaan, nullable) - Link ke hasil pemeriksaan
- timestamps
```

### 2. Eloquent Models

#### Model `Kunjungan` (NEW)
**File:** `app/Models/Kunjungan.php`

Relationships:
- `belongsTo(Pasien)` - Relasidengan Pasien
- `belongsTo(Pemeriksaan)` - Relasi dengan hasil Pemeriksaan (nullable)

#### Model `Pasien` (UPDATED)
**File:** `app/Models/Pasien.php`

New Relationship:
- `hasMany(Kunjungan)` - Satu pasien bisa punya banyak kunjungan

### 3. Routes - Halaman & Workflow
**File:** `routes/web.php`

```
GET  /poliklinik/daftar-kunjungan
     ↓ Menampilkan daftar kunjungan dengan status pending/proses
     Controller: PoliklinikController@daftarKunjungan
     View: poliklinik.daftar_kunjungan

GET  /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa
     ↓ Tampilkan form pemeriksaan untuk poli spesifik
     Controller: PoliklinikController@periksaKunjunganByPoli
     View: poliklinik.form_pemeriksaan_kunjungan

POST /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan
     ↓ Simpan hasil pemeriksaan dan mark kunjungan sebagai selesai
     Controller: PoliklinikController@simpanPemeriksaanKunjungan
```

### 4. Controller Methods - PoliklinikController

#### Method: `daftarKunjungan()`
Mengambil semua kunjungan dengan status pending/proses, menampilkan dalam table format dengan:
- No RM, Nama Pasien, Poli Tujuan, Dokter, Tanggal Kunjungan, Keluhan Utama, Status
- Tombol "Periksa" untuk setiap kunjungan (hanya jika belum selesai/batal)

#### Method: `periksaKunjunganByPoli($poli, $kunjunganId)`
- Ambil data kunjungan berdasarkan ID
- Validasi bahwa poli di URL sesuai dengan poli di kunjungan
- Jika sudah ada pemeriksaan, redirect ke halaman edit
- Update status kunjungan menjadi "proses"
- Render form pemeriksaan dengan data pasien ter-populate

#### Method: `simpanPemeriksaanKunjungan(Request $request, $kunjunganId)`
- Validasi input form pemeriksaan
- Buat record Pemeriksaan baru
- Link pemeriksaan ke kunjungan (pemeriksaan_id)
- Update status kunjungan menjadi "selesai"
- Redirect dengan success message

### 5. Views - User Interface

#### View 1: `resources/views/poliklinik/daftar_kunjungan.blade.php`
**Tampilan:**
- Bootstrap responsive table dengan 8 kolom
- Status badges (Pending=warning, Proses=info, Selesai=success, Batal=danger)
- Tombol "Periksa" dengan icon stethoscope
- Pagination untuk daftar panjang
- Empty state message jika tidak ada kunjungan

**Tombol Periksa Route:**
```blade
route('poliklinik.periksa_by_poli', [
    'poli' => str_replace(' ', '-', strtolower($kunjungan->poli)),
    'kunjunganId' => $kunjungan->id
])
```

#### View 2: `resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php`
**Tampilan:**
- Kartu informasi pasien (No RM, Nama, Umur, Alamat, No Telepon, Poli, Dokter)
- Form dengan field pemeriksaan:
  - Keluhan Utama (pre-populated dari kunjungan)
  - Riwayat Penyakit
  - Vital Signs: Suhu, Tekanan Darah, Nadi, Respirasi
  - Diagnosa
  - Terapi/Rencana Tindakan
  - Rujukan
- Tombol "Simpan Pemeriksaan" & "Kembali"

### 6. Integrasi dengan Pendaftaran Pasien Baru

#### Update: `app/Http/Controllers/PasienController.php`
**File:** `app/Http/Controllers/PasienController.php`

**New Import:**
```php
use App\Models\Kunjungan;
```

**Modified Method: `store(Request $request)`**
Setelah membuat Pasien baru, otomatis membuat Kunjungan record dengan data:
- pasien_id (dari pasien yang baru dibuat)
- no_rm (dari pasien)
- poli (dari request: poliklinik_tujuan)
- tanggal_kunjungan (dari request)
- keluhan_utama (dari request)
- status = 'pending'

#### Update: `resources/views/pendaftaran/pasien_baru.blade.php`
**New Fields:**
```html
<!-- Poli Tujuan -->
<select name="poliklinik_tujuan" required>
    <option value="">-- Pilih Poli --</option>
    <option value="Poli Umum">Poli Umum</option>
    <option value="Poli Gigi & Mulut">Poli Gigi & Mulut</option>
    <option value="Poli KIA / KB">Poli KIA / KB</option>
</select>

<!-- Tanggal Kunjungan -->
<input type="date" name="tanggal_kunjungan" required>

<!-- Keluhan Utama -->
<textarea name="keluhan_utama" placeholder="Deskripsikan keluhan utama pasien" required></textarea>
```

### 7. Navigation - Menu Link Update

#### Updated: `resources/views/poliklinik/index.blade.php`
**Change:**
```blade
<!-- BEFORE -->
<a href="{{ route('kunjungan.index') }}" class="btn btn-outline-dark">
    <i class="fas fa-list"></i> Daftar Kunjungan
</a>

<!-- AFTER -->
<a href="{{ route('poliklinik.daftar_kunjungan') }}" class="btn btn-primary">
    <i class="fas fa-list"></i> Daftar Kunjungan
</a>
```

## 🔄 Workflow Lengkap

### Use Case 1: Register Pasien Baru
```
1. User akses: /pendaftaran/pasien-baru
2. Input data pasien (nama, alamat, dll)
3. Input Poli Tujuan (pilihan: Poli Umum, Gigi & Mulut, KIA/KB)
4. Input Tanggal Kunjungan (date picker)
5. Input Keluhan Utama (text area)
6. Click "Daftar Pasien"
   ↓
   Controller: PasienController@store
   ├─ Create Pasien record
   └─ Create Kunjungan record (status=pending)
7. Redirect ke data master dengan success message
```

### Use Case 2: Lihat Daftar Pasien Menunggu
```
1. Petugas Poliklinik akses: /poliklinik → Menu Poliklinik
2. Click tombol "Daftar Kunjungan"
   ↓
   Route: /poliklinik/daftar-kunjungan
   Controller: PoliklinikController@daftarKunjungan
3. Lihat tabel dengan:
   - Pasien yang status: pending / proses
   - Tombol "Periksa" untuk setiap pasien
```

### Use Case 3: Periksa Pasien & Input Hasil
```
1. Petugas lihat daftar kunjungan
2. Click tombol "Periksa" untuk pasien tertentu
   ↓
   Route: /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa
   Controller: PoliklinikController@periksaKunjunganByPoli
   └─ Update status kunjungan → proses
3. Form pemeriksaan dibuka dengan data pasien ter-populate:
   - No RM, Nama, Umur, Poli, Keluhan Utama sudah terisi
4. Petugas input hasil pemeriksaan:
   - Vital signs (suhu, tekanan darah, nadi, respirasi)
   - Diagnosa
   - Terapi
   - Rujukan (optional)
5. Click "Simpan Pemeriksaan"
   ↓
   Route: POST /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan
   Controller: PoliklinikController@simpanPemeriksaanKunjungan
   ├─ Create Pemeriksaan record
   ├─ Link ke Kunjungan (pemeriksaan_id)
   ├─ Update status kunjungan → selesai
   └─ Redirect dengan success message
```

## 📊 Database Relationships

```
Pasien (1) ──┬─→ (Many) Kunjungan
             └─→ (Many) Pemeriksaan

Kunjungan (Many) ──→ (1) Pasien
          ├─→ (1) Pemeriksaan (optional)
          └─→ Status: pending → proses → selesai/batal

Pemeriksaan (1) ←─ (Many) Kunjungan
```

## 🔐 Access Control

Routes dilindungi dengan middleware auth (pengguna harus login):
- Admin role: Dapat akses semua
- Dokter role: Dapat akses pemeriksaan
- Pendaftaran role: Dapat register pasien baru
- User role: Limited access

## ✨ Key Features

✅ **Automatic Data Transfer:** Pasien dari Pendaftaran otomatis masuk ke Kunjungan
✅ **No Re-entry:** Data pasien ter-populate di form pemeriksaan
✅ **Status Tracking:** Kunjungan berstatus pending → proses → selesai
✅ **Poli-specific:** Form pemeriksaan disesuaikan per poli tujuan
✅ **Pagination:** Daftar kunjungan dengan pagination untuk data banyak
✅ **Responsive:** Table responsive design untuk mobile & desktop
✅ **User Friendly:** Badge status dengan warna yang jelas & intuitive

## 🧪 Testing

### Test Data
- Pasien: RM00001 (nuramadani) → Poli Umum, Status: pending
- Route generated: `/poliklinik/poli-umum/kunjungan/1/periksa`

### Manual Testing Steps
1. Login: admin@klinik.com / admin123
2. Navigate to: /poliklinik/daftar-kunjungan
3. Lihat daftar kunjungan
4. Click tombol "Periksa" pada kunjungan pertama
5. Verify form pre-populated dengan data pasien
6. Input hasil pemeriksaan
7. Click "Simpan" dan verify status berubah ke selesai

## 📁 Files Modified/Created

### Created:
- `database/migrations/2025_12_04_000001_create_kunjungans_table.php`
- `app/Models/Kunjungan.php`
- `resources/views/poliklinik/daftar_kunjungan.blade.php`
- `resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php`

### Modified:
- `app/Models/Pasien.php` - Added hasMany relationship
- `app/Http/Controllers/PoliklinikController.php` - Added 4 methods
- `app/Http/Controllers/PasienController.php` - Updated store() method, added Kunjungan import
- `routes/web.php` - Added 4 new routes
- `resources/views/pendaftaran/pasien_baru.blade.php` - Added new fields
- `resources/views/poliklinik/index.blade.php` - Updated link to daftar_kunjungan

## 🚀 Next Steps (Optional)

1. Add notification system untuk petugas saat ada kunjungan baru
2. Add SMS/Email reminder ke pasien sebelum kunjungan
3. Add dokter assignment (auto-assign atau manual select)
4. Add patient history view
5. Add reports/statistics untuk analisis kunjungan

---

**Status:** ✅ COMPLETE
**Tanggal:** 4 December 2025
**Version:** 1.0
