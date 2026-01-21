<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Package;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookingService
{
    /**
     * Get all bookings.
     */
    public function list(?BookingStatus $status = null): Collection
    {
        $query = Booking::with(['user', 'package', 'travelers'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->status($status);
        }

        return $query->get();
    }

    /**
     * Get paginated bookings.
     */
    public function paginate(int $perPage = 15, ?BookingStatus $status = null): LengthAwarePaginator
    {
        $query = Booking::with(['user', 'package', 'travelers'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->status($status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get a booking by ID.
     */
    public function getById(int $id): Booking
    {
        return Booking::with(['user', 'package', 'travelers', 'statusLogs.changedBy'])
            ->findOrFail($id);
    }

    /**
     * Get a booking by booking number.
     */
    public function getByBookingNumber(string $bookingNumber): Booking
    {
        return Booking::with(['user', 'package', 'travelers'])
            ->where('booking_number', $bookingNumber)
            ->firstOrFail();
    }

    /**
     * Get bookings for a user.
     */
    public function getForUser(User $user): Collection
    {
        return $user->bookings()
            ->with(['package', 'travelers'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new booking.
     */
    public function create(array $data, array $travelers = []): Booking
    {
        $data['travelers_count'] = count($travelers) ?: ($data['travelers_count'] ?? 1);

        // Remove travelers from data if passed there
        unset($data['travelers']);

        $booking = Booking::create($data);

        // Add travelers
        foreach ($travelers as $index => $traveler) {
            $booking->travelers()->create([
                'name' => $traveler['name'] ?? '',
                'passport_number' => $traveler['passport_number'] ?? null,
                'date_of_birth' => $traveler['date_of_birth'] ?? null,
                'nationality' => $traveler['nationality'] ?? null,
                'gender' => $traveler['gender'] ?? null,
                'is_primary' => $index === 0,
            ]);
        }

        // Increment package booking counter
        if ($booking->package_id) {
            Package::where('id', $booking->package_id)->increment('current_bookings');
        }

        return $booking->load('travelers');
    }

    /**
     * Update a booking.
     */
    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh();
    }

    /**
     * Update booking status with logging.
     */
    public function updateStatus(
        Booking $booking,
        BookingStatus $newStatus,
        ?string $notes = null,
        ?int $changedBy = null
    ): Booking {
        $oldStatus = $booking->status;

        $booking->update(['status' => $newStatus]);

        // Handle status-specific updates
        if ($newStatus === BookingStatus::CONFIRMED && !$booking->confirmed_at) {
            $booking->update(['confirmed_at' => now()]);
        }

        if ($newStatus === BookingStatus::CANCELLED && !$booking->cancelled_at) {
            $booking->update([
                'cancelled_at' => now(),
                'cancellation_reason' => $notes,
            ]);

            // Decrement package booking counter
            if ($booking->package_id) {
                Package::where('id', $booking->package_id)
                    ->where('current_bookings', '>', 0)
                    ->decrement('current_bookings');
            }
        }

        // Log the status change
        BookingStatusLog::logChange($booking, $oldStatus, $newStatus, $notes, $changedBy);

        return $booking->fresh();
    }

    /**
     * Delete a booking.
     */
    public function delete(Booking $booking): bool
    {
        return $booking->delete();
    }

    /**
     * Get booking statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Booking::count(),
            'pending' => Booking::status(BookingStatus::PENDING)->count(),
            'confirmed' => Booking::status(BookingStatus::CONFIRMED)->count(),
            'processing' => Booking::status(BookingStatus::PROCESSING)->count(),
            'completed' => Booking::status(BookingStatus::COMPLETED)->count(),
            'cancelled' => Booking::status(BookingStatus::CANCELLED)->count(),
            'total_revenue' => Booking::whereIn('status', [
                BookingStatus::CONFIRMED,
                BookingStatus::PROCESSING,
                BookingStatus::COMPLETED,
            ])->sum('total_amount'),
        ];
    }

    /**
     * Get recent bookings.
     */
    public function getRecent(int $limit = 5): Collection
    {
        return Booking::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
