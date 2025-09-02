<?php

namespace App\Enum;

enum StationOperatingEntityEum: string
{
    case WATER_COMPANY = 'water_company'; // 'المؤسسة العامة لمياه الشرب'
    case SHARED = 'shared'; // تشاركي


    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::WATER_COMPANY => 'المؤسسة العامة لمياه الشرب',
            self::SHARED => 'تشاركي',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::WATER_COMPANY => 'danger',
            self::SHARED => 'info',
        };
    }
}


