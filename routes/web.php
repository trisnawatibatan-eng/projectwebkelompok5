<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\ApotekController; 
use App\Http\Controllers\KasirController;

// ===================================================================
// LOGIN & LOGOUT
// ===================================================================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    // RIWAYAT KUNJUNGAN PASIEN
    Route::get('/pendaftaran/riwayat-kunjungan/{pasienId}', [PasienController::class, 'riwayatKunjungan'])->name('pasien.riwayat_kunjungan');

    // DATA MASTER
    Route::get('/data_master', [PasienController::class, 'index'])->name('data.master');
    Route::post('/data_master/cari', [PasienController::class, 'search'])->name('data.master.search');
    Route::get('/data_master/edit/{id}', [PasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/data_master/update/{id}', [PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/data_master/delete/{id}', [PasienController::class, 'destroy'])->name('pasien.destroy');

    // ===================================================================
    // POLIKLINIK (DIKOREKSI: Penambahan Route Spesifik per Poli)
    // ===================================================================
    Route::get('/poliklinik', [PoliklinikController::class, 'index'])->name('poliklinik');
<<<<<<< HEAD
    
    // ROUTE BARU UNTUK SUB-MENU DI SIDEBAR
    Route::get('/poliklinik/umum', [PoliklinikController::class, 'showPoliUmum'])->name('poliklinik.umum');
    Route::get('/poliklinik/gigi', [PoliklinikController::class, 'showPoliGigi'])->name('poliklinik.gigi');
    Route::get('/poliklinik/kia', [PoliklinikController::class, 'showPoliKia'])->name('poliklinik.kia');
    // Akhir ROUTE BARU

    Route::get('/poliklinik/daftar-kunjungan', [PoliklinikController::class, 'daftarKunjungan'])->name('poliklinik.daftar_kunjungan');
    // Daftar tunggu per poli (mis. /poliklinik/daftar-kunjungan/umum)
    Route::get('/poliklinik/daftar-kunjungan/{poli_slug}', [PoliklinikController::class, 'daftarKunjunganByPoli'])->name('poliklinik.daftar_kunjungan_by_poli');
    Route::get('/poliklinik/kunjungan/{kunjunganId}/pemeriksaan', [PoliklinikController::class, 'pemeriksaanKunjungan'])->name('poliklinik.pemeriksaan_kunjungan');
    Route::post('/poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan', [PoliklinikController::class, 'simpanPemeriksaanKunjungan'])->name('poliklinik.simpan_pemeriksaan_kunjungan');
    Route::get('/poliklinik/kunjungan/{kunjunganId}/periksa', [PoliklinikController::class, 'periksaKunjunganByPoli'])->name('poliklinik.periksa_by_poli');
=======
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4
    Route::get('/kunjungan', [PoliklinikController::class, 'kunjungan'])->name('kunjungan.index');
    Route::get('/kunjungan/{id}/edit', [PoliklinikController::class, 'editKunjungan'])->name('kunjungan.edit');
    Route::put('/kunjungan/{id}', [PoliklinikController::class, 'updateKunjungan'])->name('kunjungan.update');
    Route::delete('/kunjungan/{id}', [PoliklinikController::class, 'destroyKunjungan'])->name('kunjungan.destroy');
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
    Route::post('/apotek/{resepId}/proses-resep', [ApotekController::class, 'proseResep'])->name('apotek.proses-resep');
    
    // ===================================================================
    // KASIR / PEMBAYARAN
    // ===================================================================
    // Route untuk halaman Kasir utama
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index'); 

    Route::get('/kasir/invoice/{resepId}', [KasirController::class, 'createInvoice'])->name('kasir.invoice');
    Route::get('/kasir/invoice/{resepId}/print', [KasirController::class, 'printInvoice'])->name('kasir.print');
    Route::post('/kasir/{resepId}/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar'); 

    // Route untuk memproses pembayaran (action dari form)
    Route::post('/kasir/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar'); 
    // API untuk Pencarian Tagihan via AJAX (digunakan oleh JavaScript)

    Route::get('/api/kasir/tagihan', [KasirController::class, 'cariTagihan'])->name('kasir.cari.tagihan');

});