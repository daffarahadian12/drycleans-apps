<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanManageUsers
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->canManageUsers()) {
            abort(403, 'Unauthorized. Only admin and owner can access this resource.');
        }

        return $next($request);
    }
}
