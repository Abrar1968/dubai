<?php

namespace App\Enums;

enum PublishStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'yellow',
            self::PUBLISHED => 'green',
            self::ARCHIVED => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil',
            self::PUBLISHED => 'check',
            self::ARCHIVED => 'archive',
        };
    }
}
