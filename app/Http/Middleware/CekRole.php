<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    $user = Auth::guard('web')->user();

    if (!$user) {
        abort(403, 'Unauthorized');
    }

    if (!in_array($user->role, $roles)) {
        abort(403, 'Belum terdaftar');
    }

    return $next($request);
}

    
}
