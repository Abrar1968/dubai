<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'package_id',
        'status',
        'travelers_count',
        'total_amount',
        'paid_amount',
        'payment_method',
        'payment_reference',
        'special_requests',
        'travel_date',
        'contact_name',
        'contact_email',
        'contact_phone',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'travel_date' => 'date',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Boot model to generate booking number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }

    /**
     * Generate unique booking number.
     */
    public static function generateBookingNumber(): string
    {
        $prefix = 'DT';
        $year = date('Y');
        $month = date('m');

        // Find max sequence for this year-month
        $pattern = $prefix . $year . $month . '%';
        $lastBooking = self::withTrashed()
            ->where('booking_number', 'like', $pattern)
            ->orderByRaw('CAST(SUBSTRING(booking_number, -4) AS UNSIGNED) DESC')
            ->first();

        if ($lastBooking) {
            $sequence = ((int) substr($lastBooking->booking_number, -4)) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package for this booking.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the travelers for this booking.
     */
    public function travelers(): HasMany
    {
        return $this->hasMany(BookingTraveler::class);
    }

    /**
     * Get the status logs for this booking.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the primary traveler.
     */
    public function primaryTraveler()
    {
        return $this->travelers()->where('is_primary', true)->first();
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, BookingStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', BookingStatus::PENDING);
    }

    /**
     * Scope to filter confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', BookingStatus::CONFIRMED);
    }

    /**
     * Get remaining balance.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->total_amount - ($this->paid_amount ?? 0));
    }

    /**
     * Check if booking is fully paid.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->remaining_balance == 0;
    }
}
