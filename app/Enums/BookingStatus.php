<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'blue',
            self::PROCESSING => 'indigo',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
            self::REFUNDED => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::CONFIRMED => 'check-circle',
            self::PROCESSING => 'refresh',
            self::COMPLETED => 'badge-check',
            self::CANCELLED => 'x-circle',
            self::REFUNDED => 'receipt-refund',
        };
    }
}
