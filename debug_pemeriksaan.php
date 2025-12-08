<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pemeriksaan;

$rows = Pemeriksaan::orderBy('id','desc')->take(10)->get();

echo "Latest pemeriksaan records:\n";
foreach ($rows as $r) {
    echo "ID: {$r->id} | no_rm: {$r->no_rm} | nama: {$r->nama} | keluhan: " . substr($r->keluhan_utama,0,40) . "... | created_at: {$r->created_at}\n";
}

echo "Total: " . Pemeriksaan::count() . " records\n";
