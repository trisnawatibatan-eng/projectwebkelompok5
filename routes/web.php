<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemeriksaanController;

// 🔐 Autentikasi
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 📊 Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 📝 Pendaftaran Pasien Baru
Route::get('/pendaftaran/pasien-baru', [PasienController::class, 'create'])->name('pasien.baru');
Route::post('/pendaftaran/pasien-baru', [PasienController::class, 'store'])->name('pasien.store');

// 🔍 Pendaftaran Pasien Lama
Route::get('/pendaftaran/pasien-lama', [PasienController::class, 'searchForm'])->name('pasien.lama');
Route::get('/pendaftaran/pasien-lama/cari', [PasienController::class, 'searchByNoRM'])->name('pasien.cari');

// 📁 Data Master Pasien
Route::get('/data_master', [PasienController::class, 'index'])->name('data.master');
Route::post('/data_master/cari', [PasienController::class, 'search'])->name('data.master.search');
Route::get('/data_master/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
Route::put('/data_master/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
Route::delete('/data_master/delete/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

// 🩺 Pemeriksaan Dokter
Route::get('/pemeriksaan/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
Route::post('/pemeriksaan/store', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');