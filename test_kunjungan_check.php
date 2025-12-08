<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kunjungan;

echo "=== Test Kunjungan Data ===\n\n";

// Count all kunjungans
$totalKunjungans = Kunjungan::count();
echo "Total Kunjungans: $totalKunjungans\n";

// Count by status
$pendingCount = Kunjungan::where('status', 'pending')->count();
$prosesCount = Kunjungan::where('status', 'proses')->count();
$selesaiCount = Kunjungan::where('status', 'selesai')->count();

echo "Status Breakdown:\n";
echo "  - Pending: $pendingCount\n";
echo "  - Proses: $prosesCount\n";
echo "  - Selesai: $selesaiCount\n\n";

// Display pending kunjungans
echo "Pending Kunjungans:\n";
$pendingKunjungans = Kunjungan::where('status', 'pending')
    ->with('pasien')
    ->get();

if ($pendingKunjungans->isEmpty()) {
    echo "  [No pending kunjungans]\n";
} else {
    foreach ($pendingKunjungans as $kunjungan) {
        echo "  - ID: {$kunjungan->id}, Pasien: {$kunjungan->pasien->nama}, Poli: {$kunjungan->poli}, Status: {$kunjungan->status}\n";
    }
}

echo "\n";
?>
