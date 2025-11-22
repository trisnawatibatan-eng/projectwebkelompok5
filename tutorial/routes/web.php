<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LoginController,
    DashboardController,
    RegistrasiController,
    PemeriksaanController,
    PasienController,
    FarmasiController,
    KasirController
};

// Redirect ke login
Route::get('/', fn() => redirect('/login'));

// === AUTH ===
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// UBAH: Gunakan POST untuk logout (lebih aman)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// === DASHBOARD ===
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// === MASTER PASIEN (CRUD + DETAIL) ===
Route::resource('pasien', PasienController::class);

// === REGISTRASI ===
Route::get('/registrasi', [RegistrasiController::class, 'index'])->name('registrasi.index');
Route::get('/registrasi/baru', [RegistrasiController::class, 'baru'])->name('registrasi.baru');
Route::post('/registrasi/simpan', [RegistrasiController::class, 'simpan'])->name('registrasi.simpan');
Route::get('/registrasi/lama', [RegistrasiController::class, 'lama'])->name('registrasi.lama');
Route::post('/registrasi/lama', [RegistrasiController::class, 'cariPasien'])->name('registrasi.cariPasien');
Route::post('/registrasi/lama/simpan', [RegistrasiController::class, 'simpanPasienLama'])->name('registrasi.simpanLama');

// === PEMERIKSAAN ===
Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
Route::get('/pemeriksaan/soap', [PemeriksaanController::class, 'soap'])->name('pemeriksaan.soap');
Route::get('/pemeriksaan/resume', [PemeriksaanController::class, 'resume'])->name('pemeriksaan.resume');

// === FARMASI ===
Route::get('/farmasi', [FarmasiController::class, 'resep'])->name('farmasi.resep');

// === KASIR ===
Route::get('/kasir', [KasirController::class, 'pembayaran'])->name('kasir.pembayaran');