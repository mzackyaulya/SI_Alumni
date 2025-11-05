<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Contoh pemakaian: ->middleware('role:admin') atau ->middleware('role:admin,waka_siswa')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika belum login, lempar ke login (pastikan middleware auth dipasang sebelum role)
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Ambil role user (string) dari kolom users.role
        $userRole = (string) $request->user()->role;

        // Cek apakah role user termasuk salah satu yang diizinkan
        if (!in_array($userRole, $roles, true)) {
            // Jika minta JSON (API), balas JSON; kalau web, 403
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        return $next($request);
        }
}
