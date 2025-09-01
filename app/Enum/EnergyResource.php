<?php

namespace App\Enum;

enum EnergyResource: string
{
    case ELECTRICITY = 'electricity'; // كهرباء
    case SOLAR = 'solar'; // طاقة شمسية
    case GENERATOR = 'generator'; // مولدة

    // حالات الدمج الممكنة
    case ELECTRICITY_SOLAR = 'electricity_solar'; // كهرباء + طاقة شمسية
    case ELECTRICITY_GENERATOR = 'electricity_generator'; // كهرباء + مولدة
    case SOLAR_GENERATOR = 'solar_generator'; // طاقة شمسية + مولدة
    case ALL_SOURCES = 'all_sources'; // جميع المصادر معاً


    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::ELECTRICITY => 'كهرباء',
            self::SOLAR => 'طاقة شمسية',
            self::GENERATOR => 'مولدة',
            self::ELECTRICITY_SOLAR => 'كهرباء + طاقة شمسية',
            self::ELECTRICITY_GENERATOR => 'كهرباء + مولدة',
            self::SOLAR_GENERATOR => 'طاقة شمسية + مولدة',
            self::ALL_SOURCES => 'جميع المصادر معاً',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::ELECTRICITY => 'primary',
            self::SOLAR => 'warning',
            self::GENERATOR => 'info',
            self::ELECTRICITY_SOLAR => 'primary',
            self::ELECTRICITY_GENERATOR => 'primary',
            self::SOLAR_GENERATOR => 'warning',
            self::ALL_SOURCES => 'success',
        };
    }
}
