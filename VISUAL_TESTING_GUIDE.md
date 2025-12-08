# 🔄 Prescription Flow - Visual Testing Guide

## 📱 Screen-by-Screen Walkthrough

### **Screen 1: Login**
```
┌────────────────────────────────────┐
│  SISTEM INFORMASI KLINIK           │
├────────────────────────────────────┤
│                                    │
│  Username: [________]              │
│  Password: [________]              │
│                                    │
│         [ Masuk ]                  │
│                                    │
└────────────────────────────────────┘

→ Click "Masuk"
```

---

### **Screen 2: Dashboard → Poliklinik**
```
┌────────────────────────────────────┐
│ DASHBOARD                          │
├────────────────────────────────────┤
│ Selamat datang, Dokter!            │
│                                    │
│ [ Pendaftaran Pasien ]             │
│ [ Poliklinik ]  ← CLICK HERE       │
│ [ Apotek ]                         │
│ [ Kasir ]                          │
│ [ Data Master ]                    │
│                                    │
└────────────────────────────────────┘

→ Click "Poliklinik"
```

---

### **Screen 3: Waiting List (Daftar Kunjungan)**
```
┌──────────────────────────────────────────────────────────┐
│ DAFTAR KUNJUNGAN - POLI UMUM                             │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ No  │ No RM  │ Nama Pasien    │ Keluhan  │ [Periksa]  │
├─────┼────────┼────────────────┼──────────┼────────────┤
│ 1   │RM00001│ Ujicoba Resep  │ Demam    │ [PERIKSA] │ ← CLICK
│ 2   │RM00002│ Pasien Lain    │ Batuk    │ [PERIKSA] │
│                                                          │
└──────────────────────────────────────────────────────────┘

→ Click "PERIKSA" button
```

---

### **Screen 4: Examination Form**
```
┌─────────────────────────────────────────────────────────────┐
│ FORMULIR PEMERIKSAAN                                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─ DATA PASIEN (Prefilled) ──────────────────────────────┐ │
│ │ No RM: RM00001                                         │ │
│ │ Nama: Ujicoba Resep                                    │ │
│ │ Poli: Umum                                             │ │
│ │ Keluhan Utama: Demam                                   │ │
│ └────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─ ANAMNESIS ────────────────────────────────────────────┐ │
│ │ Keluhan: [_____________________]                      │ │
│ │ Riwayat: [_____________________]                      │ │
│ └────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─ PEMERIKSAAN FISIK ────────────────────────────────────┐ │
│ │ Suhu: [____] °C                                        │ │
│ │ Tekanan Darah: [___/___] mmHg                          │ │
│ │ Nadi: [____] x/menit                                   │ │
│ │ RR: [____] x/menit                                     │ │
│ └────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─ DIAGNOSA & TERAPI ────────────────────────────────────┐ │
│ │ Diagnosa: [_____________________]                     │ │
│ │ Terapi: [_____________________]                       │ │
│ └────────────────────────────────────────────────────────┘ │
│                                                             │
│ ┌─ RESEP OBAT ◆ NEW ◆ ──────────────────────────────────┐ │
│ │ Nama Obat  │ Qty │ Harga (Rp) │ [Hapus]               │ │
│ ├────────────┼─────┼────────────┼────────────────────────┤ │
│ │ Amoxicillin│ 10  │ 5000       │ [X]  ← FILL HERE      │ │
│ │ Paracetamol│ 20  │ 2000       │ [X]  ← FILL HERE      │ │
│ │ [________] │[__] │[_______]   │ [X]  ← Add more        │ │
│ └────────────┴─────┴────────────┴────────────────────────┘ │
│                                                             │
│ [ Tambah Obat ]  [ Simpan Pemeriksaan ]  [ Batal ]         │
│                                                             │
└─────────────────────────────────────────────────────────────┘

→ Fill all fields with medicines
→ Click "Simpan Pemeriksaan"
```

---

### **Screen 5: Auto-Redirect to Apotek**
```
┌──────────────────────────────────────────────────────────┐
│ APOTEK - DAFTAR RESEP                                    │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ ✓ Success: "Pemeriksaan dan Resep berhasil disimpan!"  │
│                                                          │
│ No Resep │ Tgl        │ Pasien     │ Total   │ Status    │
├──────────┼────────────┼────────────┼─────────┼──────────┤
│RES-...01 │ 04/12/2025 │Ujicoba...  │Rp 90K   │ Pending  │
│          │            │            │         │ [PROSES] │ ← CLICK
│                                                          │
└──────────────────────────────────────────────────────────┘

→ Verify resep created with status "Pending"
→ Click "PROSES" button
```

---

### **Screen 6: After Pharmacy Processes**
```
┌──────────────────────────────────────────────────────────┐
│ APOTEK - DAFTAR RESEP                                    │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ ✓ Success: "Resep RES-20251204-0001 sudah siap..."     │
│                                                          │
│ No Resep │ Tgl        │ Pasien     │ Total   │ Status    │
├──────────┼────────────┼────────────┼─────────┼──────────┤
│RES-...01 │ 04/12/2025 │Ujicoba...  │Rp 90K   │ Ready    │
│          │            │            │         │ [KE KAS] │ ← CLICK
│                                                          │
└──────────────────────────────────────────────────────────┘

→ Status changed from "Pending" to "Ready"
→ Click "KE KASIR" button (or navigate to Kasir)
```

---

### **Screen 7: Kasir Invoice**
```
┌──────────────────────────────────────────────────────────┐
│ KASIR - INVOICE                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ Invoice: RES-20251204-0001                              │
│                                                          │
│ Pasien: Ujicoba Resep (RM00001)                          │
│ Tanggal: 04-12-2025 07:10                                │
│                                                          │
│ ┌──────────────────────────────────────────────────┐    │
│ │ RINCIAN OBAT                                     │    │
│ ├──────────────┬─────┬────────┬──────────────────┤    │
│ │ Nama Obat    │ Qty │ Harga  │ Subtotal         │    │
│ ├──────────────┼─────┼────────┼──────────────────┤    │
│ │ Amoxicillin  │ 10  │5000    │ Rp 50.000        │    │
│ │ Paracetamol  │ 20  │2000    │ Rp 40.000        │    │
│ ├──────────────┴─────┴────────┼──────────────────┤    │
│ │ TOTAL TAGIHAN              │ Rp 90.000        │    │
│ └────────────────────────────┴──────────────────┘    │
│                                                          │
│ [ Tandai Lunas / Bayar ]  [ Kembali ]                    │
│                                                          │
└──────────────────────────────────────────────────────────┘

→ Verify all items and total correct
→ Collect payment from patient
→ Click "Tandai Lunas / Bayar"
```

---

### **Screen 8: Payment Complete**
```
┌──────────────────────────────────────────────────────────┐
│ KASIR - DASHBOARD                                        │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ ✓ Success: "Pembayaran berhasil dicatat!"              │
│                                                          │
│ No Resep │ Tgl        │ Pasien     │ Total   │ Status    │
├──────────┼────────────┼────────────┼─────────┼──────────┤
│RES-...01 │ 04/12/2025 │Ujicoba...  │Rp 90K   │ Paid      │
│          │            │            │         │ ✓ Done    │
│                                                          │
└──────────────────────────────────────────────────────────┘

✅ WORKFLOW COMPLETE!
```

---

## ✅ Test Checklist

Use this checklist while testing:

```
STEP 1: DOCTOR EXAM
□ Can access Poliklinik → Daftar Kunjungan
□ See patient "Ujicoba Resep" in waiting list
□ Click "Periksa" opens exam form
□ Patient data pre-filled correctly
□ Can add medicines (name, qty, price)
□ Can add multiple medicines
□ Can remove medicines with [X] button

STEP 2: SAVE & AUTO-CREATE RESEP
□ Click "Simpan Pemeriksaan" works
□ Success message appears
□ Auto-redirected to Apotek dashboard
□ Database check: Pemeriksaan record created
□ Database check: Resep record created
□ Resep has unique no_resep (RES-YYYYMMDD-####)
□ Total biaya calculated correctly (10×5000 + 20×2000 = 90000)
□ Resep status is "Pending"

STEP 3: APOTEK PROCESSES
□ Access Apotek dashboard at /apotek
□ See resep with status "Pending"
□ See medicines listed (Amoxicillin × 10, Paracetamol × 20)
□ See total Rp 90.000
□ Click "Proses" button
□ Success message: "Resep RES-... sudah siap diambil"
□ Database check: Status changed to "Ready"
□ Button changed to "Ke Kasir"

STEP 4: KASIR COLLECTS PAYMENT
□ Click "Ke Kasir" button (or navigate to /kasir/invoice/1)
□ Invoice page loads
□ Pasien name shows correctly (not blank/error)
□ Medicines listed with qty, price, subtotal
□ Total Tagihan shows Rp 90.000
□ Can verify payment collected
□ Click "Tandai Lunas / Bayar"
□ Success message appears
□ Database check: Status changed to "Paid"
□ Resep no longer shows in pending lists

DATABASE VALIDATION (Optional)
□ SELECT * FROM reseps WHERE no_resep LIKE 'RES%';
  ✓ Shows: id, no_resep, total_biaya, status
□ SELECT * FROM pemeriksaan LIMIT 1;
  ✓ Shows: kunjungan_id, diagnosa, terapi
□ SELECT * FROM kunjungans WHERE pasien_id = 13;
  ✓ Shows: status = 'selesai', pemeriksaan_id populated
```

---

## 🔧 Troubleshooting During Test

### **Problem: "Resep Obat" section not visible**
```
Solution:
1. Refresh browser (Ctrl+F5)
2. Clear browser cache
3. If still not visible, check browser console (F12 → Console)
   for JavaScript errors
```

### **Problem: Resep not created**
```
Check:
1. Did you fill at least ONE medicine?
2. Are Qty and Price numeric values?
3. Check Laravel logs: storage/logs/laravel.log
4. Look for validation error messages
```

### **Problem: Pasien name shows blank in invoice**
```
Solution:
1. Verify relationship in model (should be auto-fixed)
2. Clear view cache: php artisan view:clear
3. Check database: kunjungan must have pasien_id
```

### **Problem: "Proses" button not showing**
```
Check:
1. Status must be exactly "Pending" (case-sensitive)
2. Clear route cache: php artisan route:clear
3. Verify route is registered: php artisan route:list | grep proses
```

### **Problem: Page shows error after clicking button**
```
Debug:
1. Check browser console (F12)
2. Check Laravel logs: storage/logs/laravel.log
3. Verify routes are correctly registered
4. Clear all caches:
   php artisan cache:clear && php artisan view:clear && php artisan route:clear
```

---

## 📊 Expected Values at Each Step

| Step | No Resep | Status | Total Biaya | Location |
|------|----------|--------|-------------|----------|
| After Doctor Exam | RES-20251204-0001 | Pending | 90,000 | Apotek table |
| After Pharmacy Process | RES-20251204-0001 | Ready | 90,000 | Apotek table |
| After Payment | RES-20251204-0001 | Paid | 90,000 | Database only |

---

## 🎬 Video Walk-Through (Text Version)

**Time**: 5-10 minutes for complete flow

1. **0:00-1:00** - Login
   - Enter credentials
   - Land on dashboard

2. **1:00-2:00** - Navigate to Poliklinik
   - Click Poliklinik menu
   - See waiting list
   - Locate "Ujicoba Resep"

3. **2:00-4:00** - Fill Exam Form
   - Click "Periksa"
   - Fill vital signs
   - Add medicines (watch total update)
   - Click "Simpan Pemeriksaan"

4. **4:00-5:00** - Observe Auto-Redirect
   - Success message appears
   - Auto-redirected to Apotek
   - Resep showing with "Pending" status

5. **5:00-6:00** - Process at Pharmacy
   - Review resep details
   - Click "Proses" button
   - Status changes to "Ready"

6. **6:00-8:00** - Kasir Invoice & Payment
   - Click "Ke Kasir"
   - Review invoice
   - Verify total Rp 90,000
   - Click "Tandai Lunas"
   - See success message

7. **8:00-10:00** - Verification
   - Check Apotek dashboard (resep gone)
   - Check Kasir dashboard (resep gone/paid)
   - Optional: Check database

---

## 💾 Database Inspection

### **Quick Queries to Verify**

```sql
-- Check patient
SELECT id, no_rm, nama FROM pasiens WHERE no_rm = 'RM00001';

-- Check kunjungan
SELECT id, pasien_id, poli, status, pemeriksaan_id 
FROM kunjungans 
WHERE pasien_id = 13 
ORDER BY created_at DESC LIMIT 1;

-- Check pemeriksaan
SELECT id, kunjungan_id, diagnosa, terapi 
FROM pemeriksaan 
WHERE kunjungan_id = 1;

-- Check resep (MOST IMPORTANT)
SELECT id, no_resep, total_biaya, status, created_at 
FROM reseps 
ORDER BY created_at DESC LIMIT 1;

-- View resep medicines (JSON)
SELECT items FROM reseps 
WHERE no_resep = 'RES-20251204-0001';
-- Expected: [{"name":"Amoxicillin 500mg","qty":10,"price":5000},...]

-- Full flow verification
SELECT 
  p.nama,
  k.poli,
  k.status as kunjungan_status,
  pm.diagnosa,
  r.no_resep,
  r.status as resep_status,
  r.total_biaya
FROM kunjungans k
JOIN pasiens p ON k.pasien_id = p.id
JOIN pemeriksaan pm ON k.pemeriksaan_id = pm.id
JOIN reseps r ON pm.id = r.pemeriksaan_id
WHERE k.id = 1;
```

---

## 🎓 Key Validation Points

When testing, confirm these 3 things happen:

### **1. Resep Auto-Created**
After doctor clicks "Simpan Pemeriksaan":
- ✅ Database: Resep record exists
- ✅ Database: no_resep generated (RES-YYYYMMDD-####)
- ✅ Database: total_biaya calculated correctly
- ✅ UI: Redirected to /apotek with success message

### **2. Status Progresses**
- ✅ Initial: status = 'Pending' (just created)
- ✅ After Proses: status = 'Ready'
- ✅ After Bayar: status = 'Paid'

### **3. Data Flows Correctly**
- ✅ Apotek sees medicines from doctor's prescription
- ✅ Kasir sees same medicines in invoice
- ✅ Total biaya matches throughout

---

## 📱 Browser Developer Tools

If something seems wrong, open **F12** and check:

1. **Network Tab**
   - Request to `/apotek` returns 200
   - Request to `POST /apotek/{resepId}/proses-resep` returns 200
   - No 404/500 errors

2. **Console Tab**
   - No red errors
   - No warnings about missing resources

3. **Application → Cookies**
   - LARAVEL_SESSION exists
   - User remains logged in

4. **Laravel Logs**
   - `storage/logs/laravel.log`
   - No exception messages
   - Clean execution

---

**Ready to Test?** Start with **Screen 1: Login** above! ✅

---

*Implementation Complete: December 4, 2025*  
*Status: Ready for User Testing*
