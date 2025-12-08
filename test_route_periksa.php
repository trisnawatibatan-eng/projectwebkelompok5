<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test route periksaKunjunganByPoli
$kunjungan = \App\Models\Kunjungan::first();
if ($kunjungan) {
    $url = route('poliklinik.periksa_by_poli', ['kunjunganId' => $kunjungan->id]);
    echo "✅ Route berhasil dibuat:\n";
    echo "URL: $url\n";
    echo "Kunjungan ID: {$kunjungan->id}\n";
    echo "Poli: {$kunjungan->poli}\n";
    echo "Status: {$kunjungan->status}\n";
} else {
    echo "❌ Tidak ada kunjungan di database\n";
}
?>
