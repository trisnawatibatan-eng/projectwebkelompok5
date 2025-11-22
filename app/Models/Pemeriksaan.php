<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

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
}