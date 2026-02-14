<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Logika pengecekan
        if (auth()->check() && !in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'AKSES DITOLAK: Khusus Admin atau Super Admin.');
        }

        return $next($request);
    }
}
