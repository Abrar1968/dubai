<?php

namespace App\Http\Controllers\Admin\Hajj;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status') ? BookingStatus::tryFrom($request->get('status')) : null;
        $bookings = $this->bookingService->paginate(
            perPage: $request->get('per_page', 15),
            status: $status
        );

        return view('admin.pages.bookings.index', [
            'bookings' => $bookings,
            'statuses' => BookingStatus::cases(),
            'currentStatus' => $status,
            'stats' => $this->bookingService->getStats(),
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'package', 'travelers', 'statusLogs.changedBy']);

        return view('admin.pages.bookings.show', [
            'booking' => $booking,
            'statuses' => BookingStatus::cases(),
        ]);
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $newStatus = BookingStatus::from($validated['status']);

        $this->bookingService->updateStatus(
            $booking,
            $newStatus,
            $validated['notes'] ?? null,
            auth()->id()
        );

        return back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Confirm a booking.
     */
    public function confirm(Booking $booking): RedirectResponse
    {
        $this->bookingService->updateStatus(
            $booking,
            BookingStatus::CONFIRMED,
            'Booking confirmed by admin',
            auth()->id()
        );

        return back()->with('success', 'Booking confirmed successfully.');
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $this->bookingService->updateStatus(
            $booking,
            BookingStatus::CANCELLED,
            $validated['reason'] ?? 'Cancelled by admin',
            auth()->id()
        );

        return back()->with('success', 'Booking cancelled.');
    }

    /**
     * Update payment information.
     */
    public function updatePayment(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $this->bookingService->update($booking, $validated);

        // If fully paid, update status log
        if ($validated['paid_amount'] >= $booking->total_amount) {
            $this->bookingService->updateStatus(
                $booking,
                $booking->status,
                'Payment completed: ' . $validated['payment_method'] . ' - ' . $validated['payment_reference'],
                auth()->id()
            );
        }

        return back()->with('success', 'Payment information updated.');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $this->bookingService->delete($booking);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
