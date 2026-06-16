<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsHost
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'host') {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk host.');
        }

        if (!auth()->user()->host) {
            abort(403, 'Profil host tidak ditemukan.');
        }

        return $next($request);
    }
}
