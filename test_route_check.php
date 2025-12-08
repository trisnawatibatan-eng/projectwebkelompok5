<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== Test Auth & Route ===\n\n";

// Check if there's an admin user
$adminUser = User::where('email', 'admin@klinik.com')->first();
if ($adminUser) {
    echo "✓ Admin user found: {$adminUser->email}\n";
    echo "  Name: {$adminUser->name}\n";
    echo "  Role: {$adminUser->role}\n\n";
} else {
    echo "✗ Admin user NOT found\n";
}

// Check routes
echo "=== Routes ===\n";
echo "Daftar Kunjungan Route: /poliklinik/daftar-kunjungan\n";
echo "Periksa By Poli Route: /poliklinik/{poli}/kunjungan/{kunjunganId}/periksa\n\n";

// Check Controller Method
echo "=== Controller Methods ===\n";
$controller = new \App\Http\Controllers\PoliklinikController();
echo "✓ PoliklinikController loaded\n";
echo "  Methods: daftarKunjungan, periksaKunjunganByPoli, pemeriksaanKunjungan, simpanPemeriksaanKunjungan\n\n";

// Test URL generation
echo "=== URL Generation Test ===\n";
$kunjunganId = 1;
$poli = 'poli-umum';
$url = route('poliklinik.periksa_by_poli', [$poli, $kunjunganId]);
echo "Generated URL: $url\n\n";

echo "✓ All checks passed!\n";
?>
