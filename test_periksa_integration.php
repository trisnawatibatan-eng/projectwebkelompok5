<?php
/**
 * Test Script: Fitur PERIKSA Integrasi Pendaftaran ke Poliklinik
 * 
 * Jalankan dengan: php test_periksa_integration.php
 */

// Include Laravel bootstrap
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\Pemeriksaan;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║  TEST FITUR PERIKSA - INTEGRASI PENDAFTARAN KE POLIKLINIK     ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$tests = [
    'database_connected' => false,
    'kunjungans_table_exists' => false,
    'kunjungan_dengan_pasien_exists' => false,
    'status_pending_found' => false,
    'form_data_prepopulated' => false,
    'pemeriksaan_tersimpan' => false,
    'status_berubah_selesai' => false,
];

$details = [];

// TEST 1: Database Connected
echo "TEST 1: Verifikasi Koneksi Database\n";
echo "─────────────────────────────────────\n";
try {
    DB::connection()->getPdo();
    $tests['database_connected'] = true;
    $details['database'] = "✅ Terhubung ke database: " . env('DB_DATABASE');
    echo "✅ Database terhubung\n\n";
} catch (\Exception $e) {
    $details['database'] = "❌ Error: " . $e->getMessage();
    echo "❌ Gagal terhubung database: " . $e->getMessage() . "\n\n";
}

// TEST 2: Tabel Kunjungans Exists
echo "TEST 2: Verifikasi Tabel Kunjungans\n";
echo "─────────────────────────────────────\n";
try {
    $tableExists = DB::getSchemaBuilder()->hasTable('kunjungans');
    if ($tableExists) {
        $tests['kunjungans_table_exists'] = true;
        $columnCount = DB::getSchemaBuilder()->getColumnListing('kunjungans');
        $details['kunjungans_table'] = "✅ Tabel kunjungans ditemukan dengan " . count($columnCount) . " kolom";
        echo "✅ Tabel 'kunjungans' ditemukan\n";
        echo "   Kolom: " . implode(", ", $columnCount) . "\n\n";
    } else {
        $details['kunjungans_table'] = "❌ Tabel kunjungans tidak ditemukan";
        echo "❌ Tabel 'kunjungans' tidak ditemukan\n\n";
    }
} catch (\Exception $e) {
    $details['kunjungans_table'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// TEST 3: Kunjungan dengan Pasien Exists
echo "TEST 3: Verifikasi Data Kunjungan & Relasi Pasien\n";
echo "────────────────────────────────────────────────\n";
try {
    $kunjungan = Kunjungan::with('pasien')->first();
    if ($kunjungan) {
        $tests['kunjungan_dengan_pasien_exists'] = true;
        $pasienName = $kunjungan->pasien ? $kunjungan->pasien->nama : 'N/A';
        $details['kunjungan_pasien'] = "✅ Kunjungan ID {$kunjungan->id} ditemukan - Pasien: {$pasienName}";
        echo "✅ Kunjungan ditemukan\n";
        echo "   ID: {$kunjungan->id}\n";
        echo "   No RM: {$kunjungan->no_rm}\n";
        echo "   Poli: {$kunjungan->poli}\n";
        echo "   Pasien: {$pasienName}\n";
        echo "   Status: {$kunjungan->status}\n";
        echo "   Keluhan: {$kunjungan->keluhan_utama}\n\n";
    } else {
        $details['kunjungan_pasien'] = "⚠️  Tidak ada kunjungan di database (normal jika baru setup)";
        echo "⚠️  Tidak ada kunjungan ditemukan (silakan daftar pasien terlebih dahulu)\n\n";
    }
} catch (\Exception $e) {
    $details['kunjungan_pasien'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// TEST 4: Status Pending Found
echo "TEST 4: Verifikasi Kunjungan dengan Status PENDING\n";
echo "───────────────────────────────────────────────────\n";
try {
    $pendingCount = Kunjungan::where('status', 'pending')->count();
    $pendingKunjungans = Kunjungan::where('status', 'pending')->with('pasien')->get();
    
    if ($pendingCount > 0) {
        $tests['status_pending_found'] = true;
        $details['pending_kunjungans'] = "✅ Ditemukan {$pendingCount} kunjungan dengan status PENDING";
        echo "✅ Ditemukan {$pendingCount} kunjungan dengan status PENDING\n";
        foreach ($pendingKunjungans as $kj) {
            echo "   • {$kj->no_rm} - {$kj->pasien->nama} ({$kj->poli})\n";
        }
        echo "\n";
    } else {
        $details['pending_kunjungans'] = "⚠️  Tidak ada kunjungan PENDING (silakan daftar pasien)";
        echo "⚠️  Tidak ada kunjungan dengan status PENDING\n\n";
    }
} catch (\Exception $e) {
    $details['pending_kunjungans'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// TEST 5: Form Data Pre-Populated Check
echo "TEST 5: Verifikasi Data untuk Pre-Populate Form\n";
echo "─────────────────────────────────────────────────\n";
try {
    $kunjungan = Kunjungan::with('pasien')->where('status', 'pending')->first();
    if ($kunjungan && $kunjungan->pasien) {
        $tests['form_data_prepopulated'] = true;
        $usia = $kunjungan->pasien->tanggal_lahir ? 
                \Carbon\Carbon::parse($kunjungan->pasien->tanggal_lahir)->age : 'N/A';
        
        $details['form_prepopulate'] = "✅ Data ready untuk pre-populate form";
        echo "✅ Data siap untuk pre-populate:\n";
        echo "   No RM: {$kunjungan->no_rm}\n";
        echo "   Nama: {$kunjungan->pasien->nama}\n";
        echo "   Usia: {$usia} tahun\n";
        echo "   Jenis Kelamin: {$kunjungan->pasien->jenis_kelamin}\n";
        echo "   Alamat: {$kunjungan->pasien->alamat}\n";
        echo "   Poli Tujuan: {$kunjungan->poli}\n";
        echo "   Keluhan Utama: {$kunjungan->keluhan_utama}\n";
        echo "   Dokter: " . ($kunjungan->dokter ?? 'Belum ditentukan') . "\n\n";
    } else {
        $details['form_prepopulate'] = "⚠️  Data tidak lengkap untuk pre-populate";
        echo "⚠️  Data tidak lengkap untuk pre-populate\n\n";
    }
} catch (\Exception $e) {
    $details['form_prepopulate'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// TEST 6: Pemeriksaan Tersimpan
echo "TEST 6: Verifikasi Pemeriksaan Tersimpan\n";
echo "─────────────────────────────────────────\n";
try {
    $pemeriksaanCount = Pemeriksaan::count();
    $latestPemeriksaan = Pemeriksaan::latest()->first();
    
    if ($pemeriksaanCount > 0) {
        $tests['pemeriksaan_tersimpan'] = true;
        $details['pemeriksaan'] = "✅ Ditemukan {$pemeriksaanCount} record pemeriksaan";
        echo "✅ Ditemukan {$pemeriksaanCount} record pemeriksaan\n";
        if ($latestPemeriksaan) {
            echo "   Pemeriksaan Terbaru:\n";
            echo "   • No RM: {$latestPemeriksaan->no_rm}\n";
            echo "   • Nama: {$latestPemeriksaan->nama}\n";
            echo "   • Diagnosa: {$latestPemeriksaan->diagnosa}\n";
            echo "   • Tanggal: {$latestPemeriksaan->created_at->format('d-m-Y H:i')}\n";
        }
        echo "\n";
    } else {
        $details['pemeriksaan'] = "⚠️  Belum ada pemeriksaan (akan ada setelah petugas mengisi form)";
        echo "⚠️  Belum ada pemeriksaan di database\n\n";
    }
} catch (\Exception $e) {
    $details['pemeriksaan'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// TEST 7: Status Berubah Selesai
echo "TEST 7: Verifikasi Kunjungan Status SELESAI\n";
echo "──────────────────────────────────────────\n";
try {
    $selesaiCount = Kunjungan::where('status', 'selesai')->count();
    $selesaiKunjungans = Kunjungan::where('status', 'selesai')
                                   ->whereNotNull('pemeriksaan_id')
                                   ->with('pasien', 'pemeriksaan')
                                   ->get();
    
    if ($selesaiCount > 0) {
        $tests['status_berubah_selesai'] = true;
        $details['selesai_kunjungans'] = "✅ Ditemukan {$selesaiCount} kunjungan SELESAI dengan pemeriksaan";
        echo "✅ Ditemukan {$selesaiCount} kunjungan dengan status SELESAI\n";
        foreach ($selesaiKunjungans as $kj) {
            echo "   • {$kj->no_rm} - {$kj->pasien->nama} - Pemeriksaan ID: {$kj->pemeriksaan_id}\n";
        }
        echo "\n";
    } else {
        $details['selesai_kunjungans'] = "⚠️  Belum ada kunjungan SELESAI (akan ada setelah pemeriksaan)";
        echo "⚠️  Belum ada kunjungan dengan status SELESAI\n\n";
    }
} catch (\Exception $e) {
    $details['selesai_kunjungans'] = "❌ Error: " . $e->getMessage();
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}

// SUMMARY
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  RINGKASAN TEST HASIL                                          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$passed = array_sum(array_values($tests));
$total = count($tests);
$percentage = ($passed / $total) * 100;

foreach ($tests as $testName => $result) {
    $status = $result ? "✅ PASS" : "⚠️  PENDING";
    echo "{$status} - " . str_replace('_', ' ', ucfirst($testName)) . "\n";
}

echo "\n";
echo "SKOR: $passed/$total (" . number_format($percentage, 1) . "%)\n\n";

if ($percentage >= 80) {
    echo "✅ SISTEM SIAP DIGUNAKAN!\n";
    echo "Fitur PERIKSA telah berhasil diintegrasikan.\n";
    echo "Next: Lakukan testing manual dengan akses halaman:\n";
    echo "  1. http://localhost:8000/poliklinik/daftar-kunjungan\n";
    echo "  2. Klik tombol PERIKSA pada pasien\n";
    echo "  3. Verifikasi data pre-populated dan isi form pemeriksaan\n";
} else {
    echo "⚠️  Beberapa komponen masih setup. Ikuti langkah:\n";
    echo "  1. Daftar pasien baru di Pendaftaran\n";
    echo "  2. Periksa database apakah kunjungan tercipta\n";
    echo "  3. Jalankan test script ini kembali\n";
}

echo "\n";
?>
