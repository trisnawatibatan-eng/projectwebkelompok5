<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel pemeriksaan
     */
    public function up(): void
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm', 20);                 // Nomor Rekam Medis
            $table->string('nama', 100)->nullable();     // Nama pasien (opsional, bisa auto-load dari tabel pasien)
            $table->text('keluhan_utama');               // Keluhan utama
            $table->text('riwayat_penyakit')->nullable();// Riwayat penyakit
            $table->decimal('suhu', 4, 1)->nullable();   // Suhu tubuh (misal 36.5)
            $table->string('tekanan_darah', 20)->nullable(); // Tekanan darah (misal 120/80)
            $table->integer('nadi')->nullable();         // Nadi per menit
            $table->integer('respirasi')->nullable();    // Respirasi per menit
            $table->text('diagnosa');                    // Diagnosa medis
            $table->text('terapi');                      // Terapi / rencana tindakan
            $table->string('rujukan', 100)->nullable();  // Rujukan (jika ada)
            $table->timestamps();                        // created_at & updated_at
        });
    }

    /**
     * Rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};