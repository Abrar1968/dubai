<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingStatusLog extends Model
{
    use HasFactory;

    /**
     * Disable timestamps since this table only has created_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'from_status',
        'to_status',
        'notes',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'from_status' => BookingStatus::class,
            'to_status' => BookingStatus::class,
        ];
    }

    /**
     * Get the booking this log belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made this change.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Create a status log entry.
     */
    public static function logChange(
        Booking $booking,
        BookingStatus $fromStatus,
        BookingStatus $toStatus,
        ?string $notes = null,
        ?int $changedBy = null
    ): self {
        return self::create([
            'booking_id' => $booking->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
            'changed_by' => $changedBy ?? auth()->id(),
        ]);
    }
}
