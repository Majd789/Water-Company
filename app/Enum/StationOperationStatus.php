<?php

namespace App\Enum;

enum StationOperationStatus: string
{
    case WORKING = 'working';
    case STOPPED = 'stopped';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';

    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::WORKING => 'يعمل',
            self::STOPPED => 'متوقف',
            self::PENDING => 'قيد الانتظار',
            self::CANCELLED => 'ملغى',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::WORKING => 'success',
            self::STOPPED => 'danger',
            self::PENDING => 'warning',
            self::CANCELLED => 'secondary',
        };
    }
}


