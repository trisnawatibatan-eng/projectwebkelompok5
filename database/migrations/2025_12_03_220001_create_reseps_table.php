<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaan_id');
            $table->string('no_resep')->unique();
            $table->text('items')->nullable();
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->timestamps();
            
            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
