<?php
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Load models
use App\Models\Kunjungan;
use App\Models\Pemeriksaan;
use App\Models\Resep;

echo "=== Testing Prescription → Pharmacy → Cashier Flow ===\n\n";

// 1. Check pending kunjungans
echo "1. Checking pending kunjungans...\n";
$pending = Kunjungan::with('pasien')->where('status', 'pending')->get();
echo "   Found: " . $pending->count() . " pending kunjungans\n";
if ($pending->count() > 0) {
    foreach ($pending as $k) {
        echo "   - ID: {$k->id}, Pasien: {$k->pasien->nama}, Poli: {$k->poli}\n";
    }
}

// 2. Check existing pemeriksaans
echo "\n2. Checking existing pemeriksaans...\n";
$pemeriksaans = Pemeriksaan::with('kunjungan.pasien')->get();
echo "   Found: " . $pemeriksaans->count() . " pemeriksaans\n";
if ($pemeriksaans->count() > 0) {
    foreach ($pemeriksaans as $p) {
        echo "   - ID: {$p->id}, Kunjungan: {$p->kunjungan_id}\n";
    }
}

// 3. Check existing reseps
echo "\n3. Checking existing reseps...\n";
$reseps = Resep::with('pemeriksaan.kunjungan.pasien')->get();
echo "   Found: " . $reseps->count() . " reseps\n";
if ($reseps->count() > 0) {
    foreach ($reseps as $r) {
        $items = json_decode($r->items, true);
        echo "   - No Resep: {$r->no_resep}, Status: {$r->status}, Total: Rp " . number_format($r->total_biaya, 0) . "\n";
        if (is_array($items)) {
            foreach ($items as $item) {
                echo "     * {$item['name']}: {$item['qty']} x Rp " . number_format($item['price'], 0) . "\n";
            }
        }
    }
}

echo "\n✓ Database check complete!\n";
echo "\nNext steps:\n";
echo "1. Open browser and go to: http://localhost:8000/poliklinik/daftar-kunjungan\n";
echo "2. Click 'Periksa' on a pending patient\n";
echo "3. Fill exam form with at least 1 medicine\n";
echo "4. Click 'Simpan Pemeriksaan'\n";
echo "5. Should redirect to apotek with new resep\n";
