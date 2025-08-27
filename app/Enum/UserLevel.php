<?php

namespace App\Enum;

enum UserLevel: string
{
    case ADMIN = 'admin';
    case STATION_ADMIN = 'station_admin';
    case STATION_OPERATOR = 'station_operator';
    case USER = 'user';

    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }
}


