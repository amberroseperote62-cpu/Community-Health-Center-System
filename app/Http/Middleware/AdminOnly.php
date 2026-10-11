<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to restrict access to admin-only routes.
 * Staff users cannot perform delete operations.
 */
class AdminOnly
{
    /**
     * Handle an incoming request.
     * If the user is not an admin, abort with 403 Forbidden.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
