<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures the authenticated user is a super admin.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Please login to access this area.');
        }

        if (!$user->isSuperAdmin()) {
            abort(403, 'This area is restricted to super administrators only.');
        }

        return $next($request);
    }
}
