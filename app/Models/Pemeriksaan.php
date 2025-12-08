<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Asumsikan model Kunjungan berada di namespace App\Models
use App\Models\Kunjungan;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'no_rm',
        'nama',
        'keluhan_utama',
        'riwayat_penyakit',
        'suhu',
        'tekanan_darah',
        'nadi',
        'respirasi',
        'diagnosa',
        'terapi',
        'rujukan',
    ];

    /**
     * Relasi ke Kunjungan.
     * Sebuah Pemeriksaan adalah bagian dari (belongsTo) satu Kunjungan.
     * Ini memperbaiki error: Call to undefined relationship [kunjungan]
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
