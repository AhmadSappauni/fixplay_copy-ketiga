<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Pecah "boss,karyawan" menjadi ["boss", "karyawan"]
        $allowedRoles = collect($roles)
            ->flatMap(fn($roleString) => explode(',', $roleString))
            ->map(fn($r) => strtolower(trim($r)))
            ->toArray();

        $userRole = strtolower(trim($user->role));

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        return $next($request);
    }
}
