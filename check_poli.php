<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$kunjungans = \App\Models\Kunjungan::all();
foreach($kunjungans as $k) {
    echo "ID: {$k->id}, Poli: '{$k->poli}', Status: {$k->status}\n";
}
?>
