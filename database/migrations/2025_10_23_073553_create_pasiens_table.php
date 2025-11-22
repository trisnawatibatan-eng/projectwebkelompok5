<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: membuat tabel pasiens
     */
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id(); // primary key auto increment
            $table->string('nik', 20)->unique(); // NIK biasanya max 16 digit
            $table->string('no_rm', 20)->unique(); // Nomor rekam medis otomatis
            $table->string('nama', 100); // nama pasien
            $table->text('alamat'); // alamat lengkap
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // pilihan gender
            $table->date('tanggal_lahir'); // tanggal lahir
            $table->string('no_telepon', 20); // nomor telepon pasien
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Rollback migrasi: hapus tabel pasiens
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};