<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        $user = auth()->user();

        // Redirect based on user role
        if ($user->role === UserRole::SUPER_ADMIN || $user->role === UserRole::ADMIN) {
            // Admins go to admin panel
            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : redirect()->intended(route('admin.dashboard'));
        }

        // Regular users go to home page (they can access dashboard from header dropdown)
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended('/');
    }
}
