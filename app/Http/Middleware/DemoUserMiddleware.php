<?php

namespace App\Http\Middleware;

use App\Models\Kategori;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DemoUserMiddleware
{
    /**
     * Handle an incoming request.
     * // ponytail: Share currentUser & demo switch options saat user terotentikasi.
     * // Upgrade path: Matikan allDemoUsers saat aplikasi naik ke production.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (!Auth::user()->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'nik' => 'Akun Anda tidak memiliki hak akses (role) di sistem Logsheet.',
                ]);
            }

            View::share('currentUser', Auth::user());
        }

        View::share('navCategories', Kategori::all());

        return $next($request);
    }
}
