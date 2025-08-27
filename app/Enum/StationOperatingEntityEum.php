<?php

namespace App\Enum;

enum StationOperatingEntityEum: string
{
    case COMPANY = 'company'; // شركة
    case WATER_COMPANY = 'water_company'; // 'المؤسسة العامة لمياه الشرب'
    case NGO = 'ngo'; // منظمة غير حكومية
    case SHARED = 'shared'; // تشاركي
    case PRIVATE = 'private'; // شخصية
    case OTHER = 'other'; // أخرى

    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::COMPANY => 'شركة',
            self::WATER_COMPANY => 'المؤسسة العامة لمياه الشرب',
            self::NGO => 'منظمة غير حكومية',
            self::SHARED => 'تشاركي',
            self::PRIVATE => 'شخصي',
            self::OTHER => 'أخرى',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::COMPANY => 'success',
            self::WATER_COMPANY => 'danger',
            self::NGO => 'warning',
            self::SHARED => 'info',
            self::PRIVATE => 'warning',
            self::OTHER => 'secondary',
        };
    }
}


