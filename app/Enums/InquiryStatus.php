<?php

namespace App\Enums;

enum InquiryStatus: string
{
    case NEW = 'new';
    case READ = 'read';
    case RESPONDED = 'responded';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::READ => 'Read',
            self::RESPONDED => 'Responded',
            self::CLOSED => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'blue',
            self::READ => 'yellow',
            self::RESPONDED => 'green',
            self::CLOSED => 'gray',
        };
    }
}
