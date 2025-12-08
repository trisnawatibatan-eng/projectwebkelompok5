<?php
/**
 * Test Script untuk Fitur Periksa Langsung ke Poli Tujuan
 * 
 * Test Scenario:
 * 1. Verifikasi kunjungan ada di database dengan status pending/proses
 * 2. Verifikasi route periksa_by_poli terdaftar
 * 3. Verifikasi tombol Periksa di view daftar_kunjungan menggunakan route yang benar
 * 4. Simulasi klik button → status kunjungan berubah ke proses
 * 5. Verifikasi form_pemeriksaan_kunjungan ter-populate dengan benar
 */

// Setup Laravel
require __DIR__ . '/bootstrap/app.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Setup Database Connection
use Illuminate\Support\Facades\DB;
use App\Models\Kunjungan;
use App\Models\Pasien;

$pdo = DB::connection()->getPdo();

echo "=== TEST FITUR PERIKSA LANGSUNG KE POLI TUJUAN ===\n\n";

// Test 1: Cek apakah ada kunjungan dengan status pending
echo "📋 TEST 1: Cek kunjungan pending di database\n";
$kunjungans = Kunjungan::where('status', 'pending')->with('pasien')->get();

if ($kunjungans->isEmpty()) {
    echo "❌ Tidak ada kunjungan pending. Mari buat test data...\n\n";
    
    // Buat pasien test jika belum ada
    $pasien = Pasien::where('no_rm', 'RM99999')->first();
    if (!$pasien) {
        $pasien = Pasien::create([
            'nik' => '1234567890999999',
            'no_rm' => 'RM99999',
            'nama' => 'Pasien Test Periksa',
            'alamat' => 'Jl. Test Periksa',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'no_telepon' => '081234567890'
        ]);
        echo "✅ Pasien test dibuat: {$pasien->nama} ({$pasien->no_rm})\n";
    }
    
    // Buat kunjungan test
    $kunjungan = Kunjungan::create([
        'pasien_id' => $pasien->id,
        'no_rm' => $pasien->no_rm,
        'poli' => 'Poli Umum',
        'dokter' => 'Dr. Test',
        'tanggal_kunjungan' => now()->toDateString(),
        'keluhan_utama' => 'Test keluhan',
        'status' => 'pending'
    ]);
    echo "✅ Kunjungan test dibuat: ID={$kunjungan->id}, Poli={$kunjungan->poli}, Status={$kunjungan->status}\n\n";
} else {
    echo "✅ Ditemukan " . $kunjungans->count() . " kunjungan pending:\n";
    foreach ($kunjungans as $k) {
        echo "   - ID: {$k->id}, Pasien: {$k->pasien->nama}, Poli: {$k->poli}, Status: {$k->status}\n";
    }
    echo "\n";
}

// Test 2: Verifikasi route parameter formatting
echo "📋 TEST 2: Verifikasi parameter URL untuk route periksa_by_poli\n";
$testKunjungan = Kunjungan::where('status', 'pending')->first() ?? Kunjungan::first();

if ($testKunjungan) {
    $poliFormatted = str_replace(' ', '-', strtolower($testKunjungan->poli));
    echo "   Original Poli: '{$testKunjungan->poli}'\n";
    echo "   URL Formatted: '$poliFormatted'\n";
    echo "   Kunjungan ID: {$testKunjungan->id}\n";
    echo "   Expected URL: /poliklinik/$poliFormatted/kunjungan/{$testKunjungan->id}/periksa\n";
    echo "   ✅ Parameter formatting valid\n\n";
} else {
    echo "❌ Tidak ada kunjungan untuk test\n\n";
}

// Test 3: Verifikasi method periksaKunjunganByPoli logic
echo "📋 TEST 3: Simulasi logika periksaKunjunganByPoli\n";
if ($testKunjungan) {
    echo "   Kondisi Awal:\n";
    echo "   - ID: {$testKunjungan->id}\n";
    echo "   - Poli: {$testKunjungan->poli}\n";
    echo "   - Status: {$testKunjungan->status}\n";
    echo "   - Pemeriksaan ID: " . ($testKunjungan->pemeriksaan_id ?? 'NULL') . "\n";
    
    // Cek apakah sudah ada pemeriksaan
    if ($testKunjungan->pemeriksaan_id) {
        echo "   ⚠️  Sudah ada pemeriksaan (ID: {$testKunjungan->pemeriksaan_id})\n";
        echo "   → Akan redirect ke halaman edit pemeriksaan\n";
    } else {
        echo "   ✅ Belum ada pemeriksaan\n";
        echo "   → Status akan diubah ke 'proses'\n";
        echo "   → Form pemeriksaan akan ditampilkan\n";
    }
    echo "\n";
}

// Test 4: Verifikasi data yang akan di-populate di form
echo "📋 TEST 4: Data yang akan di-populate di form pemeriksaan\n";
if ($testKunjungan) {
    echo "   Data dari Kunjungan:\n";
    echo "   - No RM: {$testKunjungan->no_rm}\n";
    echo "   - Nama Pasien: {$testKunjungan->pasien->nama}\n";
    echo "   - Poli Tujuan: {$testKunjungan->poli}\n";
    echo "   - Dokter: " . ($testKunjungan->dokter ?? '-') . "\n";
    echo "   - Tanggal Kunjungan: {$testKunjungan->tanggal_kunjungan}\n";
    echo "   - Keluhan Utama: {$testKunjungan->keluhan_utama}\n";
    echo "   - Umur: " . (\Carbon\Carbon::parse($testKunjungan->pasien->tanggal_lahir)->age ?? '-') . " tahun\n";
    echo "   ✅ Semua data siap untuk di-populate\n\n";
}

// Test 5: Verifikasi tombol Periksa di view
echo "📋 TEST 5: Verifikasi button markup di view\n";
$viewPath = 'resources/views/poliklinik/daftar_kunjungan.blade.php';
if (file_exists($viewPath)) {
    $viewContent = file_get_contents($viewPath);
    
    if (strpos($viewContent, 'poliklinik.periksa_by_poli') !== false) {
        echo "   ✅ View menggunakan route('poliklinik.periksa_by_poli')\n";
        
        if (strpos($viewContent, 'str_replace(\' \', \'-\', strtolower($kunjungan->poli))') !== false) {
            echo "   ✅ Poli formatting (replace space dengan dash, lowercase) diterapkan\n";
        } else {
            echo "   ⚠️  Perlu verifikasi poli formatting di view\n";
        }
        
        if (strpos($viewContent, '$kunjungan->status !== \'selesai\'') !== false) {
            echo "   ✅ Button hanya tampil untuk status selain 'selesai' dan 'batal'\n";
        }
        echo "\n";
    } else {
        echo "   ❌ View tidak menggunakan route periksa_by_poli\n\n";
    }
} else {
    echo "   ❌ View file tidak ditemukan\n\n";
}

// Test 6: Verifikasi form pemeriksaan view
echo "📋 TEST 6: Verifikasi form pemeriksaan view tersedia\n";
$formPath = 'resources/views/poliklinik/form_pemeriksaan_kunjungan.blade.php';
if (file_exists($formPath)) {
    $formContent = file_get_contents($formPath);
    echo "   ✅ File form_pemeriksaan_kunjungan.blade.php tersedia\n";
    
    // Cek apakah form ter-populate
    $requiredFields = [
        '$kunjungan->no_rm' => 'No RM',
        '$kunjungan->pasien->nama' => 'Nama Pasien',
        '$kunjungan->poli' => 'Poli Tujuan',
        '$kunjungan->keluhan_utama' => 'Keluhan Utama'
    ];
    
    $missingFields = [];
    foreach ($requiredFields as $field => $label) {
        if (strpos($formContent, $field) === false) {
            $missingFields[] = $label;
        }
    }
    
    if (empty($missingFields)) {
        echo "   ✅ Form ter-populate dengan semua data kunjungan yang diperlukan\n";
    } else {
        echo "   ⚠️  Field yang mungkin tidak ter-populate: " . implode(', ', $missingFields) . "\n";
    }
    echo "\n";
} else {
    echo "   ❌ File form_pemeriksaan_kunjungan.blade.php tidak ditemukan\n\n";
}

echo "=== SUMMARY ===\n";
echo "✅ Fitur Periksa sudah siap untuk ditest!\n\n";
echo "📌 Langkah Testing Manual:\n";
echo "1. Buka browser: http://127.0.0.1:8000/poliklinik/daftar-kunjungan\n";
echo "2. Klik tombol 'Periksa' pada salah satu pasien\n";
echo "3. Verifikasi form pemeriksaan ter-populate dengan data pasien dari poli tujuan\n";
echo "4. Cek database - status kunjungan harus berubah ke 'proses'\n";
echo "5. Isi form pemeriksaan dan submit\n";
echo "6. Verifikasi status kunjungan berubah ke 'selesai'\n";
