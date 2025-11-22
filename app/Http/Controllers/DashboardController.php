<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Pasien::count();
        $laki = Pasien::where('jenis_kelamin', 'Laki-laki')->count();
        $perempuan = Pasien::where('jenis_kelamin', 'Perempuan')->count();

        return view('dashboard', [
            'title' => 'Dashboard',
            'total' => $total,
            'laki' => $laki,
            'perempuan' => $perempuan,
        ]);
    }
}