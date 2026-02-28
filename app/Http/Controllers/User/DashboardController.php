<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Display the user dashboard.
     */
    public function index(): Response
    {
        $user = auth()->user();

        // Get user's booking stats
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
        ];

        // Get recent bookings
        $recentBookings = $user->bookings()
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('user/Dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'user' => $user,
        ]);
    }
}
