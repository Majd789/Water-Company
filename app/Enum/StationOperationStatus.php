<?php

namespace App\Enum;

enum StationOperationStatus: string
{
    case WORKING = 'working';
    case STOPPED = 'stopped';



    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) {
            self::WORKING => 'يعمل',
            self::STOPPED => 'متوقف',

        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::WORKING => 'success',
            self::STOPPED => 'danger',
        };
    }
}


