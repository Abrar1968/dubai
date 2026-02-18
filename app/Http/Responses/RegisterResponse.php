<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Symfony\Component\HttpFoundation\Response;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        // Log out the user that Fortify automatically logged in
        Auth::logout();

        // Invalidate the session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login with success message
        return $request->wantsJson()
            ? new JsonResponse(['message' => 'Registration successful. Please login to continue.'], 201)
            : redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }
}
