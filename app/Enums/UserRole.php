<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };
    }

    public function isAdminLevel(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }
}
