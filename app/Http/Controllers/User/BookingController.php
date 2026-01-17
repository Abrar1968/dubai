<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $status = $request->get('status');

        $query = $user->bookings()->with('package')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);

        // Get counts
        $counts = [
            'all' => $user->bookings()->count(),
            'pending' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'completed' => $user->bookings()->where('status', 'completed')->count(),
            'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
        ];

        return view('user.pages.bookings.index', [
            'bookings' => $bookings,
            'counts' => $counts,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(int $id): View
    {
        $user = auth()->user();
        $booking = $user->bookings()
            ->with(['package', 'travelers', 'statusLogs'])
            ->findOrFail($id);

        return view('user.pages.bookings.show', [
            'booking' => $booking,
        ]);
    }
}
