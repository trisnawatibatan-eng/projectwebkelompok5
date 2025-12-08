<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pemeriksaan;

$items = Pemeriksaan::latest()->take(10)->get();

if ($items->isEmpty()) {
    echo "NO_RECORDS\n";
    exit(0);
}

echo "LAST_" . count($items) . "_PEMERIKSAAN:\n";
foreach ($items as $p) {
    echo sprintf("ID:%d | no_rm:%s | nama:%s | created_at:%s | keluhan:%s\n", $p->id, $p->no_rm, $p->nama, $p->created_at, substr(str_replace("\n"," ",$p->keluhan_utama),0,120));
}
