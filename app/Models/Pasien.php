<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasiens'; // pastikan nama tabel benar

    protected $fillable = [
        'nik',
        'no_rm',
        'nama',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_telepon',
    ];

    // Relasi ke Kunjungan
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'pasien_id');
    }
} 