<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read Carbon|null $passport_expiry
 * @property-read Carbon|null $date_of_birth
 */
class BookingTraveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'full_name',
        'passport_number',
        'passport_expiry',
        'date_of_birth',
        'nationality',
        'gender',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'passport_expiry' => 'datetime',
            'date_of_birth' => 'datetime',
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the booking this traveler belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope to filter primary travelers.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Get age of traveler.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * Check if passport is valid (not expired).
     */
    public function getIsPassportValidAttribute(): bool
    {
        if (!$this->passport_expiry) {
            return true; // Assume valid if not set
        }

        return $this->passport_expiry->isFuture();
    }

    /**
     * Check if passport expires within given months.
     */
    public function passportExpiresWithin(int $months): bool
    {
        if (!$this->passport_expiry) {
            return false;
        }

        return $this->passport_expiry->lte(now()->addMonths($months));
    }
}
