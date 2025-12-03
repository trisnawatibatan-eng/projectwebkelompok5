<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // PENTING: Import Facade Auth

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login', ['title' => 'Login']);
    }

    // Proses login
    public function login(Request $request)
    {
        // 1. Validasi input: Memastikan inputan adalah email yang valid
        $credentials = $request->validate([
            'username' => 'required|email', // Memaksa inputan harus berupa email yang valid
            'password' => 'required|string',
        ]);
        
        // Mencoba login menggunakan kolom 'email' (karena divalidasi sebagai email)
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            
            // PENTING: Re-generate session untuk keamanan
            $request->session()->regenerate();
            
            $user = Auth::user();

            // >>> Logic session custom session(['user_id' => ...]) telah dihapus.
            // >>> Kita hanya bergantung pada Auth::check() untuk verifikasi sesi.

            // Redirect berdasarkan peran (disarankan)
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', '✅ Selamat datang Admin!');
            }
            
            // Default redirect untuk semua pengguna yang berhasil login
            return redirect()->route('dashboard')->with('success', '✅ Login berhasil!');
        }

        // Jika otentikasi gagal
        return back()->with('error', '❌ Email atau password salah!')->withInput($request->except('password'));
    }

    // Logout
    public function logout(Request $request)
    {
        // 1. Logout dari Auth Facade
        Auth::logout();

        // 2. Hapus semua data session dan regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // 3. Hapus session custom lama (jika ada)
        session()->forget('user'); // Hapus sisa session lama
        session()->forget('user_id');
        session()->forget('user_role');

        return redirect()->route('login')->with('success', '✅ Anda berhasil logout.');
    }
}