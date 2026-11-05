<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Pemakaian di route: CheckRole::class . ':manager' atau ':manager,staff'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Tanpa sesi login
        if (! $user) {
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Login, tetapi role tidak sesuai: sesi diakhiri lalu kembali ke login
        if (! in_array($user->role, $roles, true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('warning', 'Anda tidak memiliki hak akses ke halaman tersebut. Silakan login dengan akun yang sesuai.');
        }

        return $next($request);
    }
}
