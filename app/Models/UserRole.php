<?php

namespace App\Models;

class UserRole
{
    const ADMIN = 'admin';
    const MODERATOR = 'moderator';
    const USER = 'user';

    public static function getRoles(): array
    {
        return [self::ADMIN, self::MODERATOR, self::USER];
    }
}
