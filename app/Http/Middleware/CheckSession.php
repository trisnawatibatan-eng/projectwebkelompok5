<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        // Cek session manual yang kamu buat di AuthController
        if (!session()->has('user')) {
            return redirect()->route('login')
                   ->with('error', 'Silahkan login terlebih dahulu!');
        }

        return $next($request);
    }
}