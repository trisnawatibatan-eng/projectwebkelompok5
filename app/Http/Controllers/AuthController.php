<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // PENTING: Import Facade Auth

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('login', ['title' => 'Login']);
    }

    // Proses login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => 'required|email', // Asumsi username adalah email
            'password' => 'required',
        ]);

        // PENTING: Gunakan fitur otentikasi database Laravel
        // Kita mencoba login menggunakan email dan password yang diberikan
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            
            // Re-generate session untuk mencegah session fixation attacks
            $request->session()->regenerate();
            
            // Ambil user yang baru login
            $user = Auth::user();

            // Simpan data user ke session (opsional, jika Anda menggunakan fitur checksession custom)
            session(['user_id' => $user->id, 'user_role' => $user->role]);

            // Redirect berdasarkan peran (opsional, tapi disarankan)
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', '✅ Selamat datang Admin!');
            }
            // Pengguna lain akan diarahkan oleh middleware CheckRole
            
            return redirect()->route('dashboard')->with('success', '✅ Login berhasil!');
        }

        // Jika otentikasi gagal
        return back()->with('error', '❌ Email atau password salah!')->withInput($request->except('password'));
    }

    // Logout
    public function logout(Request $request)
    {
        // Logout dari Auth Facade
        Auth::logout();

        // Hapus semua data session dan regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Hapus session custom Anda (jika digunakan)
        session()->forget('user_id');
        session()->forget('user_role');

        return redirect()->route('login')->with('success', '✅ Anda berhasil logout.');
    }
}