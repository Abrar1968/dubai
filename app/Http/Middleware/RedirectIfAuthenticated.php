<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * For public login/register pages, only redirect if the user is a regular user.
     * Admins and super_admins should NOT be considered "authenticated" for public auth pages.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Only redirect regular users (role='user') away from login/register
                // Admins and super_admins can still access public login page
                if ($user->role === UserRole::USER) {
                    return redirect('/');
                }

                // For admin/super_admin, don't redirect - let them see the page
                // They have their own login at /admin/login
            }
        }

        return $next($request);
    }
}
