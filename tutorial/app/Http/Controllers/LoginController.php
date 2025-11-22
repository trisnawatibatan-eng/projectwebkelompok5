<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Menampilkan halaman login
        return view('login');
    }

    public function login(Request $request)
    {
        // Ambil input dari form
        $username = $request->input('username');
        $password = $request->input('password');

        // Validasi login sederhana
        if ($username === 'admin' && $password === 'admin123') {
            // Simpan status login ke session
            $request->session()->put('is_logged_in', true);
            return redirect('/dashboard');
        } else {
            return back()->withErrors(['login' => 'Username atau password salah!']);
        }
    }

    public function logout(Request $request)
    {
        // ✅ Hapus session dan token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman login
        return redirect('/login');
    }
}
