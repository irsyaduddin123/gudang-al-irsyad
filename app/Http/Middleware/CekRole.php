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
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::guard('web')->user(); // ini akan ambil dari penggunas

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }

    
}
