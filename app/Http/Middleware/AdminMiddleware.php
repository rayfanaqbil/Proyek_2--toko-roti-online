<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna memiliki peran admin
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        // Jika bukan admin, arahkan ke halaman tertentu
        return redirect('/');
    }
}
