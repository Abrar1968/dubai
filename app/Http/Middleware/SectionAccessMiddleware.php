<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SectionAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures the authenticated user has access to the specified section.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $section  The section to check access for (hajj, tour, typing)
     */
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Please login to access this area.');
        }

        // Super admins have access to all sections
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if admin has access to this section
        if (!$user->hasSection($section)) {
            abort(403, "You do not have access to the {$section} section.");
        }

        return $next($request);
    }
}
