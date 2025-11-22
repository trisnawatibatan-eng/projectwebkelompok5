<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $username = strtolower(trim($request->username));
        $password = trim($request->password);

        // Login sederhana tanpa database
        if ($username === 'andika' && $password === '12345') {
            session(['user' => 'andika']);
            return redirect()->route('dashboard');
        }

        return back()->with('error', '❌ Username atau password salah!');
    }

    // Logout
    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login')->with('success', '✅ Anda berhasil logout.');
    }
}