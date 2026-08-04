<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role_user !== 'Super Admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Hanya Super Admin yang diizinkan.'], 403);
            }
            return redirect()->route('admin.rooms.details')->with('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh Super Admin.');
        }

        return $next($request);
    }
}
