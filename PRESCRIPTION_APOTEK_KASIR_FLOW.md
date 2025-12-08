# 📋 Prescription → Pharmacy → Cashier Integration Guide

**Status**: ✅ **FULLY IMPLEMENTED & READY TO TEST**

---

## 🎯 Workflow Overview

The complete medical prescription-to-payment workflow has been implemented:

```
┌─────────────┐         ┌──────────────┐         ┌───────────┐         ┌────────┐
│   Doctor    │         │   Pharmacy   │         │  Cashier  │         │ Patient│
│  (Polikli)  │ ──Rx──> │   (Apotek)   │ ──Rdy─> │  (Kasir)  │ ──Pls─> │  (Done)│
└─────────────┘         └──────────────┘         └───────────┘         └────────┘

Doctor fills exam      Apotek processes      Cashier collects      Patient
+ prescribes medicines  resep & marks ready    payment & marks paid   leaves
```

---

## 📱 Step-by-Step User Flow

### **Step 1: Doctor Examines Patient**

1. Login to system
2. Go to **Poliklinik → Daftar Kunjungan** (or filtered by poli: `/poliklinik/daftar-kunjungan/umum`)
3. See list of pending patients
4. Click **"Periksa"** button next to patient name

**Routes Used:**
- `GET /poliklinik/daftar-kunjungan` → List all pending kunjungans
- `GET /poliklinik/daftar-kunjungan/{poli_slug}` → Filter by poli (umum/gigi/kia)

### **Step 2: Doctor Fills Exam Form with Prescription**

Opens form at `/poliklinik/kunjungan/{kunjunganId}/periksa`

Form sections:
- ✓ **Patient Info** (pre-filled from kunjungan)
  - No RM, Nama, Poli, Keluhan Utama
- ✓ **Anamnesis** (doctor input)
  - Keluhan, Riwayat Penyakit
- ✓ **Pemeriksaan Fisik** (vital signs)
  - Suhu, Tekanan Darah, Nadi, RR
- ✓ **Diagnosa & Terapi** (doctor findings)
  - Diagnosa, Terapi
- ✓ **Resep Obat** (medicines - NEW!)
  - Nama Obat, Qty, Harga (Rp)
  - Buttons: "Tambah Obat", "Hapus"

**Example prescription entry:**
| Nama Obat | Qty | Harga |
|-----------|-----|-------|
| Amoxicillin 500mg | 10 | 5.000 |
| Paracetamol 500mg | 20 | 2.000 |
| **Total** | | **Rp 110.000** |

Click **"Simpan Pemeriksaan"** button

### **Step 3: System Auto-Creates Prescription Record**

Controller: `PoliklinikController::simpanPemeriksaanKunjungan()`

Actions:
1. ✅ Validates all form data
2. ✅ Creates **Pemeriksaan** record (exam findings)
3. ✅ Updates **Kunjungan** status: `pending` → `selesai`
4. ✅ **NEW**: Creates **Resep** record if medicines provided
   - Generates unique no_resep: `RES-YYYYMMDD-####` (e.g., RES-20251204-0001)
   - Calculates total_biaya: sum of (qty × price) for each medicine
   - Sets status: `Pending` (awaiting pharmacy processing)
   - Links to pemeriksaan_id
5. ✅ Redirects to **Apotek** dashboard

**Database Record Created:**
```
reseps table:
- id: 1
- pemeriksaan_id: 5
- no_resep: RES-20251204-0001
- items: [{"name":"Amoxicillin 500mg","qty":10,"price":5000},...]
- total_biaya: 110000
- status: Pending
- created_at: 2025-12-04 07:10:30
```

### **Step 4: Pharmacy Processes Prescription**

Pharmacy staff (Apotek) logs in and sees `/apotek` dashboard

**Apotek Dashboard shows:**
- List of all reseps with status `Pending` or `Ready`
- Columns: No Resep, Tgl, Nama Pasien, Obat (Qty), Total Rp, Status, Aksi

**For each Pending resep:**
- Verify medicines in stock
- Click **"Proses"** button

**Backend Action:**
- Route: `POST /apotek/{resepId}/proses-resep`
- Controller: `ApotekController::proseResep($resepId)`
- Action: Updates resep status: `Pending` → `Ready`
- Message: "Resep [NO_RESEP] sudah siap diambil. Pasien dapat ke Kasir untuk pembayaran."

**Updated Record:**
```
reseps table:
- status: Ready (changed from Pending)
- updated_at: 2025-12-04 07:11:00
```

### **Step 5: Patient Goes to Cashier**

Cashier staff (Kasir) sees `/kasir` dashboard

**Display:**
- All reseps with status `Ready` (just processed)
- Option: Click **"Ke Kasir"** or manually search by No Resep

**Click invoice button:**
- Route: `GET /kasir/invoice/{resepId}`
- Shows invoice with:
  - Pasien: [Nama, No RM]
  - Tanggal: [Tgl Resep Dibuat]
  - Tabel obat dengan rincian qty × harga
  - **Total Bayar: Rp [total]**

### **Step 6: Cashier Collects Payment**

**Invoice page has:**
- ✓ Itemized medicine list
- ✓ Qty × Price calculations
- ✓ Total amount due
- ✓ Button: **"Tandai Lunas / Bayar"**

Cashier clicks button:
- Route: `POST /kasir/{resepId}/bayar`
- Controller: `KasirController::bayar($resepId)`
- Action: Updates resep status: `Ready` → `Paid`
- Redirects back to kasir dashboard

**Final Record:**
```
reseps table:
- status: Paid (changed from Ready)
- updated_at: 2025-12-04 07:12:15
```

---

## 🛠️ Technical Implementation

### **Database Models**

#### **Resep Model**
```php
// app/Models/Resep.php
- id (PK)
- pemeriksaan_id (FK)
- no_resep (string, unique)
- items (JSON) → [{"name":"...", "qty":..., "price":...}]
- total_biaya (decimal)
- status (enum: Pending, Ready, Paid)
- timestamps
- Relationship: belongsTo(Pemeriksaan)
```

#### **Pemeriksaan Model** (unchanged but now linked to Resep)
```php
// app/Models/Pemeriksaan.php
- Relationship: hasOne(Resep) [optional]
- Relationship: belongsTo(Kunjungan)
```

### **Routes Added/Modified**

| Method | Route | Controller | Purpose |
|--------|-------|-----------|---------|
| GET | /poliklinik/daftar-kunjungan/{poli_slug} | PoliklinikController@daftarKunjunganByPoli | Filter kunjungans by clinic |
| GET | /poliklinik/kunjungan/{kunjunganId}/periksa | PoliklinikController@periksaKunjunganByPoli | Show exam form (NEW: with Resep section) |
| POST | /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan | PoliklinikController@simpanPemeriksaanKunjungan | Save exam + auto-create Resep |
| GET | /apotek | ApotekController@index | Show pending/ready reseps |
| POST | /apotek/{resepId}/proses-resep | ApotekController@proseResep | Mark resep as Ready ✨ **NEW** |
| GET | /kasir/invoice/{resepId} | KasirController@createInvoice | Show invoice for payment |
| POST | /kasir/{resepId}/bayar | KasirController@bayar | Mark resep as Paid |

### **Views Updated/Created**

| View | Status | Purpose |
|------|--------|---------|
| `resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php` | ✅ **UPDATED** | Added "Resep Obat" form section with dynamic medicine fields |
| `resources/views/apotek/index.blade.php` | ✅ **UPDATED** | Shows resep list with "Proses" button for Pending status |
| `resources/views/kasir/invoice.blade.php` | ✅ **UPDATED** | Fixed pasien name display from kunjungan relationship |
| `resources/views/kasir/index.blade.php` | ✓ Existing | Cashier dashboard (can integrate resep list) |

### **Controller Updates**

#### **PoliklinikController::simpanPemeriksaanKunjungan()** ✨ **KEY UPDATE**
```php
public function simpanPemeriksaanKunjungan(Request $request, $kunjunganId)
{
    $validated = $request->validate([
        'keluhan_utama' => 'required|string',
        'anamnesis' => 'nullable|string',
        'suhu' => 'required|numeric',
        'tekanan_darah' => 'required|string',
        'nadi' => 'required|numeric',
        'rr' => 'required|numeric',
        'diagnosa' => 'required|string',
        'terapi' => 'required|string',
        'resep_items' => 'nullable|array',  // ← NEW: accept medicines
    ]);

    $kunjungan = Kunjungan::findOrFail($kunjunganId);

    // Create Pemeriksaan
    $pemeriksaan = Pemeriksaan::create([
        'kunjungan_id' => $kunjunganId,
        // ... other fields ...
    ]);

    // Update Kunjungan
    $kunjungan->update([
        'pemeriksaan_id' => $pemeriksaan->id,
        'status' => 'selesai',
    ]);

    // ✨ NEW: Auto-create Resep if medicines provided
    $resepItems = $validated['resep_items'] ?? [];
    $resepItems = array_filter($resepItems, fn($item) => !empty($item['name']));

    if (!empty($resepItems)) {
        $totalBiaya = 0;
        foreach ($resepItems as $item) {
            $totalBiaya += ((int)($item['qty'] ?? 0)) * ((float)($item['price'] ?? 0));
        }

        $lastResep = Resep::orderBy('id', 'desc')->first();
        $nextId = $lastResep ? $lastResep->id + 1 : 1;
        $no_resep = 'RES-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Resep::create([
            'pemeriksaan_id' => $pemeriksaan->id,
            'no_resep' => $no_resep,
            'items' => json_encode($resepItems),
            'total_biaya' => $totalBiaya,
            'status' => 'Pending',
        ]);

        return redirect()->route('apotek.index')
            ->with('success', 'Pemeriksaan dan Resep berhasil disimpan!');
    }

    return redirect()->route('poliklinik.daftar_kunjungan')
        ->with('success', 'Pemeriksaan berhasil disimpan!');
}
```

#### **ApotekController::proseResep()** ✨ **NEW METHOD**
```php
public function proseResep($resepId)
{
    $resep = Resep::findOrFail($resepId);
    $resep->update(['status' => 'Ready']);

    return redirect()->route('apotek.index')
        ->with('success', 'Resep ' . $resep->no_resep . ' sudah siap diambil. 
                          Pasien dapat ke Kasir untuk pembayaran.');
}
```

---

## 🧪 Testing the Complete Flow

### **Prerequisites**
- ✅ Laravel server running: `php artisan serve --host=localhost --port=8000`
- ✅ Database migrated: `php artisan migrate`
- ✅ Test data seeded: `php artisan db:seed --class=TestPrescriptionSeeder`
  - Creates test patient: "Ujicoba Resep" (No RM: RM00001)
  - Creates pending kunjungan for Poli Umum

### **Manual Testing Steps**

**Step 1: Login as Doctor**
```
URL: http://localhost:8000
Username: [doctor account]
```

**Step 2: Navigate to Patient Waiting List**
```
URL: http://localhost:8000/poliklinik/daftar-kunjungan
Expected: See "Ujicoba Resep" in pending list
```

**Step 3: Click "Periksa" Button**
```
Expected: Opens form_pemeriksaan_kunjungan.blade.php
```

**Step 4: Fill Exam Form**
```
Required Fields:
- Keluhan Utama: "Demam tinggi"
- Suhu: 38.5
- Tekanan Darah: 120/80
- Nadi: 85
- RR: 20
- Diagnosa: "Demam Berdarah"
- Terapi: "Istirahat, banyak minum"

Add Medicines (click "Tambah Obat"):
- Nama Obat: Amoxicillin 500mg | Qty: 10 | Harga: 5000
- Nama Obat: Paracetamol 500mg | Qty: 20 | Harga: 2000
```

**Step 5: Click "Simpan Pemeriksaan"**
```
Expected Result:
- ✓ Pemeriksaan record created
- ✓ Kunjungan status updated to 'selesai'
- ✓ Resep record created (no_resep: RES-20251204-0001)
- ✓ Redirects to http://localhost:8000/apotek
- ✓ Success message displayed
```

**Step 6: Pharmacy Processes Resep**
```
URL: http://localhost:8000/apotek
Expected: See new resep with status "Pending"
Click: "Proses" button
Expected Result:
- ✓ Resep status changed to "Ready"
- ✓ Success message: "Resep RES-20251204-0001 sudah siap diambil..."
- ✓ Resep now shows "Siap Ambil" status and "Ke Kasir" button
```

**Step 7: Cashier Collects Payment**
```
URL: http://localhost:8000/kasir
Click: "Ke Kasir" button (or use Invoice button from apotek)
Expected: Opens http://localhost:8000/kasir/invoice/1

Invoice page shows:
- Pasien: Ujicoba Resep (RM00001)
- Rincian Obat table:
  * Amoxicillin 500mg | 10 | Rp 5.000 | Rp 50.000
  * Paracetamol 500mg | 20 | Rp 2.000 | Rp 40.000
  * TOTAL TAGIHAN: Rp 90.000

Click: "Tandai Lunas / Bayar"
Expected Result:
- ✓ Resep status changed to "Paid"
- ✓ Redirects to Kasir dashboard
- ✓ Resep no longer shows in pending list
- ✓ Payment collected!
```

---

## 📊 Database State at Each Step

### **Initial State**
```
Kunjungans:
  id=1, pasien_id=13, poli='Umum', status='pending'

Pemeriksaans:
  (empty)

Reseps:
  (empty)
```

### **After Doctor Exam + Prescription**
```
Kunjungans:
  id=1, status='selesai', pemeriksaan_id=1

Pemeriksaans:
  id=1, kunjungan_id=1, diagnosa='Demam Berdarah', terapi='...'

Reseps:
  id=1, pemeriksaan_id=1, no_resep='RES-20251204-0001',
  status='Pending', total_biaya=90000,
  items='[{"name":"Amoxicillin 500mg","qty":10,"price":5000},...]'
```

### **After Pharmacy Processing**
```
Reseps:
  id=1, status='Ready' (changed from 'Pending'), updated_at='...'
```

### **After Payment**
```
Reseps:
  id=1, status='Paid' (changed from 'Ready'), updated_at='...'
```

---

## 🔍 Troubleshooting

### **Issue: Form doesn't show "Resep Obat" section**
**Solution:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **Issue: Resep not created after exam**
**Check:**
1. Verify medicines were filled in form
2. Check controller validation: `$resep_items` must not be empty after filtering
3. Look at Laravel logs: `storage/logs/laravel.log`

### **Issue: Can't see pasien name in Kasir invoice**
**Solution:** View was updated to pull from `$resep->pemeriksaan->kunjungan->pasien->nama`
- Verify relationship in model: `Resep → Pemeriksaan → Kunjungan → Pasien`

### **Issue: No "Proses" button in Apotek**
**Solution:**
1. Route must be registered: `POST /apotek/{resepId}/proses-resep`
2. Resep status must be exactly `'Pending'` (case-sensitive)
3. Clear routes cache: `php artisan route:clear`

---

## ✅ Checklist: What's Implemented

- ✅ Form section "Resep Obat" added to exam template
- ✅ Dynamic JavaScript to add/remove medicine rows
- ✅ Controller validates `resep_items` array
- ✅ Auto-calculate `total_biaya` from qty × price
- ✅ Auto-generate unique `no_resep` (RES-YYYYMMDD-####)
- ✅ Auto-create `Resep` record on exam save
- ✅ Set resep status to `Pending` (pharmacy will process)
- ✅ Redirect to apotek after resep created
- ✅ Apotek dashboard shows pending/ready reseps
- ✅ Apotek "Proses" button updates status to `Ready`
- ✅ Kasir invoice displays medicines + total
- ✅ Kasir payment button marks resep as `Paid`
- ✅ Fix pasien name display in invoice

---

## 📝 Optional Enhancements (Future)

1. **Receipt Generation**: Create PDF receipt after payment
2. **Pharmacy Notifications**: Email/SMS when resep is ready
3. **Stock Management**: Check medicine availability during prescription
4. **Audit Trail**: Log all status changes with timestamps & user
5. **Patient Portal**: Allow patient to track prescription status online
6. **Multi-medicine Package**: Create "medicine bundle" templates
7. **Refund/Cancellation**: Handle prescription cancellations
8. **Insurance Integration**: Calculate insurance deductions

---

## 📞 Quick Reference URLs

| Action | URL | Method |
|--------|-----|--------|
| View waiting list | /poliklinik/daftar-kunjungan | GET |
| Filter by poli (Umum) | /poliklinik/daftar-kunjungan/umum | GET |
| Exam form | /poliklinik/kunjungan/{kunjunganId}/periksa | GET |
| Save exam + Rx | /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan | POST |
| Apotek dashboard | /apotek | GET |
| Process resep | /apotek/{resepId}/proses-resep | POST |
| Kasir dashboard | /kasir | GET |
| Invoice | /kasir/invoice/{resepId} | GET |
| Pay invoice | /kasir/{resepId}/bayar | POST |

---

**Implementation Date**: December 4, 2025
**Status**: Ready for User Testing ✅
**Next Phase**: End-to-end testing with real users
