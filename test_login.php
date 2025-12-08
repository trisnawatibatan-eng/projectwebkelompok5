<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email','admin@klinik.com')->first();
if (!$user) {
    echo "NO_USER\n";
    exit(0);
}
$hash = $user->password;
$check = password_verify('admin123', $hash) ? 'PASSWORD_OK' : 'PASSWORD_MISMATCH';
echo "USER_EXISTS\n";
echo "HASH:" . $hash . "\n";
echo $check . "\n";
