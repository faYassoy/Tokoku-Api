<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // If user is not logged in or does not have the required role,
            // redirect them or return an error.
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
