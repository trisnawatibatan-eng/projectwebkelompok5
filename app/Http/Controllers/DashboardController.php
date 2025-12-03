<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien; // Pastikan Anda menggunakan Model Pasien Anda
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Total Semua Pasien
        $totalPasien = Pasien::count();

        // 2. Ambil Jumlah Pasien Laki-laki
        // Mencari 'L' ATAU 'LAKI-LAKI' (mengabaikan case)
        $lakiLaki = Pasien::where(function ($query) {
            $query->where('jenis_kelamin', 'L')
                  ->orWhere(DB::raw('LOWER(jenis_kelamin)'), 'laki-laki');
        })->count();

        // 3. Ambil Jumlah Pasien Perempuan
        // Mencari 'P' ATAU 'PEREMPUAN' (mengabaikan case)
        $perempuan = Pasien::where(function ($query) {
            $query->where('jenis_kelamin', 'P')
                  ->orWhere(DB::raw('LOWER(jenis_kelamin)'), 'perempuan');
        })->count();

        // Kirim data ke view dashboard
        return view('dashboard', [
            'totalPasien' => $totalPasien,
            'lakiLaki' => $lakiLaki,
            'perempuan' => $perempuan,
        ]);
    }
}
