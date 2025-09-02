<?php

namespace App\Enum;

enum UserLevel: string
{
    case ADMIN = 'admin';
    case STATION_ADMIN = 'station_admin';

    case UNIT_ADMIN = 'unit_admin';

    case STATION_OPERATOR = 'station_operator';
    case USER = 'user';

    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::ADMIN => 'مدير',
            self::STATION_ADMIN => 'مدير محطة',
            self::STATION_OPERATOR => 'مشغل محطة',
            self::USER => 'مستخدم ',
            self::UNIT_ADMIN => 'مدير وحدة',

        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::ADMIN => 'success',
            self::STATION_ADMIN => 'primary',
            self::STATION_OPERATOR => 'primary',
            self::USER => 'primary',
            self::UNIT_ADMIN => 'primary',
        };
    }

}


