<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' setelah kolom 'email'
            // Menggunakan enum sangat disarankan untuk Role-Based Access Control (RBAC)
            $table->enum('role', ['admin', 'dokter', 'perawat', 'pendaftaran', 'kasir', 'user'])
                  ->default('user')
                  ->after('email'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom 'role' jika rollback migrasi dilakukan
            $table->dropColumn('role');
        });
    }
};