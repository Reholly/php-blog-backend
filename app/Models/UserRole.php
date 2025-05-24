<?php

namespace App\Models;

enum UserRole: string
{
    const ADMIN = 'admin';
    const MODERATOR = 'moderator';
    const USER = 'user';

    public static function getRoles(): array
    {
        return [self::ADMIN, self::MODERATOR, self::USER];
    }
}
