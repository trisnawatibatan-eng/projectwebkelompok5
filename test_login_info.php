<?php
// Simple test untuk verify login credentials
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "=== Login Test ===\n\n";

$email = 'admin@klinik.com';
$password = 'admin123';

$user = User::where('email', $email)->first();

if (!$user) {
    echo "✗ User tidak ditemukan\n";
    exit;
}

echo "✓ User ditemukan: {$user->name}\n";
echo "  Email: {$user->email}\n";
echo "  Role: {$user->role}\n\n";

// Test password
if (Hash::check($password, $user->password)) {
    echo "✓ Password valid!\n";
    echo "\nLogin Credentials Valid!\n";
    echo "URL untuk akses: http://localhost:8000/login\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "✗ Password tidak valid\n";
}

echo "\nSetelah login, akses: http://localhost:8000/poliklinik/daftar-kunjungan\n";
?>
