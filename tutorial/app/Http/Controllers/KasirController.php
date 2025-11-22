<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function pembayaran(Request $request)
    {
        // Cek apakah user sudah login
        if (! $request->session()->get('is_logged_in')) {
            return redirect('/login')->withErrors(['login' => 'Silakan login terlebih dahulu!']);
        }

        // Jika sudah login, tampilkan halaman pembayaran
        return view('kasir.pembayaran');
    }
}