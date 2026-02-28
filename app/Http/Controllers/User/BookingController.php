<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request): Response
    {
        $user = auth()->user();
        $status = $request->get('status');

        $query = $user->bookings()->with('package')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->get();

        // Get counts
        $counts = [
            'all' => $user->bookings()->count(),
            'pending' => $user->bookings()->where('status', 'pending')->count(),
            'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            'completed' => $user->bookings()->where('status', 'completed')->count(),
            'cancelled' => $user->bookings()->where('status', 'cancelled')->count(),
        ];

        return Inertia::render('user/Bookings', [
            'bookings' => $bookings,
            'counts' => $counts,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(int $id): Response
    {
        $user = auth()->user();
        $booking = $user->bookings()
            ->with(['package', 'travelers', 'statusLogs'])
            ->findOrFail($id);

        // Transform booking data for Vue
        $bookingData = $booking->toArray();

        // Transform status_logs to use 'status' instead of 'to_status'
        if (isset($bookingData['statusLogs'])) {
            $bookingData['status_logs'] = collect($bookingData['statusLogs'])->map(function ($log) {
                return [
                    'id' => $log['id'],
                    'status' => $log['to_status'] ?? 'unknown',
                    'notes' => $log['notes'] ?? null,
                    'created_at' => $log['created_at'],
                ];
            })->toArray();
            unset($bookingData['statusLogs']);
        }

        return Inertia::render('user/BookingShow', [
            'booking' => $bookingData,
        ]);
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'traveler_count' => 'required|integer|min:1|max:20',
            'departure_date' => 'required|date|after:today',
            'special_requests' => 'nullable|string|max:1000',
            'travelers' => 'required|array|min:1',
            'travelers.*.name' => 'required|string|max:255',
            'travelers.*.passport_number' => 'nullable|string|max:50',
            'travelers.*.date_of_birth' => 'nullable|date|before:today',
            'travelers.*.nationality' => 'nullable|string|max:100',
            'travelers.*.gender' => 'nullable|in:male,female',
        ]);

        $user = auth()->user();
        $package = \App\Models\Package::findOrFail($validated['package_id']);

        // Calculate total amount using discounted price if available
        $effectivePrice = ($package->discounted_price && $package->discounted_price < $package->price)
            ? $package->discounted_price
            : $package->price;
        $totalAmount = $effectivePrice * count($validated['travelers']);

        $bookingData = [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'travel_date' => $validated['departure_date'],
            'special_requests' => $validated['special_requests'] ?? null,
            'contact_name' => $user->name,
            'contact_email' => $user->email,
            'contact_phone' => $user->phone ?? null,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'status' => \App\Enums\BookingStatus::PENDING,
        ];

        $booking = $this->bookingService->create($bookingData, $validated['travelers']);

        return redirect()
            ->route('user.bookings.show', $booking->id)
            ->with('success', 'Your booking has been submitted successfully! We will contact you shortly.');
    }
}
