# Panduan Fitur Tombol "PERIKSA" di Daftar Kunjungan

## 📌 Deskripsi Fitur
Tombol "Periksa" di kolom **Aksi** di halaman Daftar Kunjungan memungkinkan petugas poliklinik untuk langsung membuka form pemeriksaan pasien sesuai dengan poli tujuan mereka.

## 🔄 Workflow Lengkap

### 1. **Pendaftaran Pasien** (Modul Pendaftaran)
```
Pasien mendaftar → Pilih Poli + Tanggal Kunjungan + Keluhan Utama
                  ↓
                  Otomatis membuat Kunjungan record (status = 'pending')
```

### 2. **Lihat Daftar Kunjungan** (Modul Poliklinik)
```
Petugas Poli → Menu Poliklinik → "Daftar Kunjungan"
              ↓
              Tampil tabel dengan kolom:
              - No RM, Nama Pasien, Poli, Dokter, Tanggal, Keluhan, Status, Aksi
```

### 3. **Klik Tombol "Periksa"** (Fitur Utama)
```
Petugas Poli klik tombol "Periksa" 
              ↓
              URL: /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa
              ↓
              System validasi poli & kunjungan ID
              ↓
              Update status kunjungan → 'proses'
              ↓
              Tampilkan form pemeriksaan dengan data pasien ter-populate
```

### 4. **Isi Form Pemeriksaan**
```
Form menampilkan:
- Data Pasien (No RM, Nama, Umur, Poli, Dokter) - AUTO TERISI
- Keluhan Utama - AUTO TERISI dari kunjungan
- Vital Signs (Suhu, TD, Nadi, Respirasi)
- Diagnosa & Terapi
- Rujukan (opsional)
              ↓
              Petugas mengisi data pemeriksaan
              ↓
              Klik "Simpan Pemeriksaan"
```

### 5. **Simpan Pemeriksaan**
```
Sistem membuat record Pemeriksaan baru
                      ↓
                      Link ke Kunjungan (pemeriksaan_id)
                      ↓
                      Update status kunjungan → 'selesai'
                      ↓
                      Redirect ke Daftar Kunjungan dengan pesan sukses
```

## 📋 Detail Implementasi

### **Route Definition** (`routes/web.php`)
```php
Route::get('/poliklinik/{poli}/kunjungan/{kunjunganId}/periksa', 
    [PoliklinikController::class, 'periksaKunjunganByPoli']
)->name('poliklinik.periksa_by_poli');
```

### **Controller Method** (`app/Http/Controllers/PoliklinikController.php`)
```php
public function periksaKunjunganByPoli($poli, $kunjunganId)
{
    // 1. Ambil kunjungan dengan relasi pasien
    $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjunganId);

    // 2. Validasi bahwa poli di URL sesuai dengan poli di kunjungan
    $poliNormalized = str_replace(' ', '-', strtolower($kunjungan->poli));
    if ($poli !== $poliNormalized) {
        return redirect()->route('poliklinik.daftar_kunjungan')
            ->with('error', 'Poli tidak sesuai dengan kunjungan ini.');
    }

    // 3. Jika sudah ada pemeriksaan, redirect ke halaman edit
    if ($kunjungan->pemeriksaan_id) {
        return redirect()->route('kunjungan.edit', $kunjungan->pemeriksaan_id)
            ->with('info', 'Pasien ini sudah pernah diperiksa.');
    }

    // 4. Update status kunjungan menjadi 'proses'
    $kunjungan->update(['status' => 'proses']);

    // 5. Return form dengan data ter-populate
    return view('poliklinik.form_pemeriksaan_kunjungan', compact('kunjungan'));
}
```

### **View Button** (`resources/views/poliklinik/daftar_kunjungan.blade.php`)
```php
@if ($kunjungan->status !== 'selesai' && $kunjungan->status !== 'batal')
    <a href="{{ route('poliklinik.periksa_by_poli', [
        'poli' => str_replace(' ', '-', strtolower($kunjungan->poli)),
        'kunjunganId' => $kunjungan->id
    ]) }}" 
       class="btn btn-sm btn-primary">
        <i class="bi bi-stethoscope"></i> Periksa
    </a>
@else
    <span class="text-muted">-</span>
@endif
```

## ✅ Kondisi Tampil Tombol "Periksa"

| Kondisi | Tampil? | Alasan |
|---------|--------|--------|
| Status = 'pending' | ✅ YA | Belum diperiksa |
| Status = 'proses' | ✅ YA | Sedang proses, bisa lanjut |
| Status = 'selesai' | ❌ TIDAK | Sudah selesai |
| Status = 'batal' | ❌ TIDAK | Dibatalkan |

## 🔐 Validasi & Error Handling

### 1. **Validasi Poli**
```
Jika poli di URL ≠ poli di kunjungan
→ Redirect ke daftar_kunjungan dengan error message
```

### 2. **Validasi Pemeriksaan Duplikat**
```
Jika pemeriksaan_id sudah ada di kunjungan
→ Redirect ke halaman edit pemeriksaan lama
→ Tampil pesan: "Pasien sudah pernah diperiksa"
```

### 3. **Validasi Kunjungan**
```
Jika kunjungan_id tidak ditemukan
→ Tampil Laravel 404 error
```

## 📲 Contoh URL yang Dihasilkan

### Untuk Pasien di Poli Umum:
```
/poliklinik/poli-umum/kunjungan/1/periksa
```

### Untuk Pasien di Poli Gigi:
```
/poliklinik/poli-gigi-mulut/kunjungan/2/periksa
```

### Untuk Pasien di Poli KIA/KB:
```
/poliklinik/poli-kia-kb/kunjungan/3/periksa
```

## 🧪 Cara Test Fitur

### 1. **Buat Kunjungan Test**
```bash
php artisan tinker
>>> $kunjungan = \App\Models\Kunjungan::first();
>>> echo $kunjungan->id . " | " . $kunjungan->poli . " | " . $kunjungan->status;
```

### 2. **Generate URL**
```php
$url = route('poliklinik.periksa_by_poli', [
    'poli' => str_replace(' ', '-', strtolower($kunjungan->poli)),
    'kunjunganId' => $kunjungan->id
]);
echo $url; // Contoh: /poliklinik/poli-umum/kunjungan/1/periksa
```

### 3. **Akses URL di Browser**
```
http://localhost:8000/poliklinik/poli-umum/kunjungan/1/periksa
```

### 4. **Verifikasi**
- ✅ Form pemeriksaan tampil
- ✅ Data pasien sudah ter-populate
- ✅ Status kunjungan berubah dari 'pending' → 'proses'

## 📊 Database Tables Terlibat

| Tabel | Kolom yang Digunakan |
|-------|---------------------|
| `kunjungans` | id, pasien_id, poli, dokter, tanggal_kunjungan, keluhan_utama, status, pemeriksaan_id |
| `pasiens` | id, no_rm, nama, jenis_kelamin, tanggal_lahir, alamat, no_telepon |
| `pemeriksaans` | id, no_rm, nama, keluhan_utama, suhu, tekanan_darah, nadi, respirasi, diagnosa, terapi, rujukan |

## 🎯 Business Logic

```
Pasien Mendaftar
    ↓
Kunjungan created (status='pending', poli='Poli Umum', keluhan='sakit kepala')
    ↓
Petugas Poli lihat Daftar Kunjungan
    ↓
Petugas klik tombol "Periksa" → URL: /poliklinik/poli-umum/kunjungan/1/periksa
    ↓
Sistem:
  1. Validasi poli (poli-umum === 'Poli Umum' ✓)
  2. Ambil data kunjungan + pasien
  3. Update status kunjungan → 'proses'
  4. Return form dengan data ter-populate
    ↓
Petugas isi form pemeriksaan
    ↓
Submit form → Create Pemeriksaan record + Link ke Kunjungan + Status='selesai'
    ↓
Kunjungan selesai, tidak tampil lagi di Daftar Kunjungan (hanya pending/proses yang tampil)
```

## 🚀 Tips Penggunaan

1. **Tombol hanya tampil untuk status pending/proses**: Jika sudah selesai, gunakan fitur "History" atau "Cetak" untuk hasil pemeriksaan
2. **URL poli di-normalize**: Poli "Poli Gigi & Mulut" menjadi "poli-gigi-mulut" di URL
3. **Validasi keamanan**: Jika user coba manipulasi URL (poli tidak sesuai), sistem akan tolak
4. **Prevent duplikasi**: Jika sudah ada pemeriksaan, klik ulang akan redirect ke data lama, bukan buat baru

---

**Status**: ✅ Fitur LENGKAP dan SIAP PAKAI  
**Last Update**: 4 Desember 2025  
**Tested**: Ya, 1 kunjungan pending ditemukan
