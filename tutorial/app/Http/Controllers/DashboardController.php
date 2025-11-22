<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cek apakah user sudah login
        if (! $request->session()->get('is_logged_in')) {
            return redirect('/login')->withErrors(['login' => 'Silakan login terlebih dahulu!']);
        }

        // Jika sudah login, tampilkan dashboard
        return view('dashboard');
    }
}