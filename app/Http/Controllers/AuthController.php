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
        // 1. Validasi input (username/email dan password)
        $credentials = $request->validate([
            'username' => 'required|string', // Terima username sebagai string (bisa email atau username)
            'password' => 'required',
        ]);

        // 2. Coba login menggunakan email field (karena database users hanya punya email, bukan username)
        // Jika pengguna mengirim email address, gunakan langsung; jika bukan, asumsikan sebagai email juga

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            
            // Re-generate session untuk mencegah session fixation attacks
            $request->session()->regenerate();
            
            // Ambil user yang baru login
            $user = Auth::user();


            // Simpan data user ke session (PENTING: CheckSession middleware mengecek session('user'))
            session(['user' => $user, 'user_id' => $user->id, 'user_role' => $user->role]);
<<<<<<< HEAD

=======
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4

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

        session()->forget(['user', 'user_id', 'user_role']);
<<<<<<< HEAD

=======
>>>>>>> f868db48cec9d34adf8065fb4d9df4824cbf45e4

        return redirect()->route('login')->with('success', '✅ Anda berhasil logout.');
    }
}