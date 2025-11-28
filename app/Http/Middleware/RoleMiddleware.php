<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Cek jika user tidak login atau tidak memiliki role yang sesuai
        if (!$user) {
            // not authenticated — redirect to home
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        // $roles may be passed as multiple arguments or a single comma-separated string
        $allowed = [];
        foreach ($roles as $r) {
            if (str_contains($r, ',')) {
                $allowed = array_merge($allowed, array_map('trim', explode(',', $r)));
            } else {
                $allowed[] = $r;
            }
        }

        // Cek jika role pengguna tidak ada di daftar yang diperbolehkan
        if (!in_array($user->role, $allowed)) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}