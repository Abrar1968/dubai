<?php

namespace App\Enums;

enum PackageType: string
{
    case HAJJ = 'hajj';
    case UMRAH = 'umrah';
    case TOUR = 'tour';

    public function label(): string
    {
        return match ($this) {
            self::HAJJ => 'Hajj',
            self::UMRAH => 'Umrah',
            self::TOUR => 'Tour',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HAJJ => 'amber',
            self::UMRAH => 'emerald',
            self::TOUR => 'blue',
        };
    }
}
