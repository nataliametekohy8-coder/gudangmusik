<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user memiliki role yang diizinkan.
     *
     * Pemakaian: middleware('role:admin') atau middleware('role:admin,staff').
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login → arahkan ke halaman login.
        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Role user tidak termasuk yang diizinkan → tolak dengan 403.
        $userRole = auth()->user()->role;

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
