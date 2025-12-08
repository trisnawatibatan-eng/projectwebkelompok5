<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // PENTING: Import Facade Auth
use Symfony\Component\HttpFoundation\Response; // Import Response

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // PENTING: Ganti cek session manual dengan cek otentikasi Laravel
        if (!Auth::check()) {
            // Jika user belum login, redirect ke halaman login
            return redirect()->route('login')
                ->with('error', '❌ Silahkan login terlebih dahulu untuk mengakses halaman.');
        }

        return $next($request);
    }
}