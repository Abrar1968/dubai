<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Only allow regular users (not admins or super admins) to access user dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has the USER role
        if (auth()->user()->role !== UserRole::USER) {
            // If admin/super_admin tries to access user dashboard, redirect to admin panel
            if (auth()->user()->isAdminLevel()) {
                return redirect()->route('admin.dashboard');
            }

            // Otherwise redirect to home
            return redirect()->route('home');
        }

        return $next($request);
    }
}
