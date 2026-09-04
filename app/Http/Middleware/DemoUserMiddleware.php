<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DemoUserMiddleware
{
    /**
     * Handle an incoming request.
     * // ponytail: Auth di-bypass sementara menggunakan session demo_user_id untuk mempermudah testing semua role.
     * // Upgrade path: Ganti dengan auth portal SSO internal sesuai PRD §3.
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = session('demo_user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user) {
            $user = User::where('role', 'teknisi')->first() ?? User::first();
            if ($user) {
                session(['demo_user_id' => $user->id]);
            }
        }

        if ($user) {
            Auth::setUser($user);
            View::share('currentUser', $user);
            View::share('allDemoUsers', User::all());
        }

        View::share('navCategories', \App\Models\Kategori::all());

        return $next($request);
    }
}
