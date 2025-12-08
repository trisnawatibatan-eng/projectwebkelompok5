<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Test 1: Check if user exists
$user = User::where('email', 'admin@klinik.com')->first();
if (!$user) {
    echo "FAIL: User admin@klinik.com not found\n";
    exit(1);
}
echo "PASS: User admin@klinik.com found\n";

// Test 2: Try Auth::attempt
$credentials = ['email' => 'admin@klinik.com', 'password' => 'admin123'];
if (Auth::attempt($credentials)) {
    echo "PASS: Auth::attempt() succeeded\n";
    $loggedInUser = Auth::user();
    echo "Logged in as: " . $loggedInUser->name . " (Role: " . $loggedInUser->role . ")\n";
} else {
    echo "FAIL: Auth::attempt() failed\n";
    exit(1);
}

echo "\nAll tests passed!\n";
