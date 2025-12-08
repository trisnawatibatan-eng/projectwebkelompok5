<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'pasien_id',
        'no_rm',
        'poli',
        'dokter',
        'tanggal_kunjungan',
        'keluhan_utama',
        'status',
        'pemeriksaan_id',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    // Relasi ke Pemeriksaan
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }
}
