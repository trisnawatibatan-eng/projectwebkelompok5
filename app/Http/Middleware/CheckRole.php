<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // 2. Periksa apakah peran pengguna ada di dalam daftar peran yang diizinkan ($roles)
        // Fungsi in_array akan memeriksa role user ('pendaftaran', 'dokter', dll.)
        // apakah termasuk dalam array $roles yang dilewatkan dari route.
        if (!in_array($user->role, $roles)) {
            
            // PERBAIKAN: Arahkan ke route dashboard yang sudah terdefinisi jika akses ditolak
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Peran Anda (' . $user->role . ') tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        // 3. Lanjutkan request jika peran diizinkan
        return $next($request);
    }
}