<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->string('no_rm', 20);                    // Nomor Rekam Medis (denormalisasi untuk kemudahan)
            $table->string('poli', 50);                     // Nama poli (Poli Umum, Gigi, KIA/KB, dll)
            $table->string('dokter', 100)->nullable();      // Nama dokter/perawat yang akan menangani
            $table->date('tanggal_kunjungan');              // Tanggal kunjungan
            $table->text('keluhan_utama')->nullable();      // Keluhan pasien saat pendaftaran
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending'); // Status kunjungan
            $table->unsignedBigInteger('pemeriksaan_id')->nullable(); // Link ke tabel pemeriksaan setelah periksa
            $table->timestamps();

            // Foreign key
            $table->foreign('pasien_id')->references('id')->on('pasiens')->onDelete('cascade');
            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaan')->onDelete('set null');

            // Index untuk query cepat
            $table->index(['poli', 'tanggal_kunjungan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
