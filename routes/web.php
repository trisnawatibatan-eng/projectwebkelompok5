<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\ApotekController; // Tambahkan ini
use App\Http\Controllers\KasirController; // PENTING: Tambahkan import KasirController

// ===================================================================
// LOGIN & LOGOUT
// ===================================================================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================================================================
// SEMUA ROUTE YANG BUTUH LOGIN
// ===================================================================
Route::middleware('checksession')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PASIEN BARU
    Route::get('/pendaftaran/pasien-baru', [PasienController::class, 'create'])->name('pasien.baru');
    Route::post('/pendaftaran/pasien-baru', [PasienController::class, 'store'])->name('pasien.store');

    // PASIEN LAMA
    Route::get('/pendaftaran/pasien-lama', [PasienController::class, 'searchForm'])->name('pasien.lama');
    Route::get('/pendaftaran/pasien-lama/cari', [PasienController::class, 'searchByNoRM'])->name('pasien.cari');

    // DATA MASTER
    Route::get('/data_master', [PasienController::class, 'index'])->name('data.master');
    Route::post('/data_master/cari', [PasienController::class, 'search'])->name('data.master.search');
    Route::get('/data_master/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/data_master/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/data_master/delete/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    // POLIKLINIK
    Route::get('/poliklinik', [PoliklinikController::class, 'index'])->name('poliklinik');
    Route::get('/pemeriksaan/create', [PoliklinikController::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan/store', [PoliklinikController::class, 'store'])->name('pemeriksaan.store');

    // DAFTAR KUNJUNGAN (Antrian)
    Route::get('/kunjungan', [App\Http\Controllers\KunjunganController::class, 'index'])->name('kunjungan.index');

    // AJAX PASIEN (Digunakan oleh formulir Poliklinik)
    Route::get('/pasien/cari-ajax', [PasienController::class, 'cariPasienAjax'])->name('pasien.cari.ajax');

    // APOTEK & RESEP 
    Route::get('/apotek', [ApotekController::class, 'index'])->name('apotek.index');
    Route::get('/apotek/resep/create', [ApotekController::class, 'createResep'])->name('apotek.resep.create');
    Route::post('/apotek/resep/store', [ApotekController::class, 'storeResep'])->name('apotek.resep.store');
    
    // ===================================================================
    // KASIR / PEMBAYARAN (BARU)
    // ===================================================================
    // Route untuk halaman Kasir utama
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index'); 
    // Route untuk memproses pembayaran (action dari form)
    Route::post('/kasir/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar'); 
    // API untuk Pencarian Tagihan via AJAX (digunakan oleh JavaScript)
    Route::get('/api/kasir/tagihan', [KasirController::class, 'cariTagihan'])->name('kasir.cari.tagihan');

});