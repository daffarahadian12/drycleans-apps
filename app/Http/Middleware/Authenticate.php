<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Selalu redirect ke login page ketika tidak authenticated
        return $request->expectsJson() ? null : '/login'; // Pastikan ini '/login'
    }
}
