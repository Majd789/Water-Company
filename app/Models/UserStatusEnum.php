<?php

namespace App\Models;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case PENDING = 'pending';

    public function getLabel(): string
    {
        return match($this) {
            self::ACTIVE => 'نشط',
            self::INACTIVE => 'غير نشط',
            self::SUSPENDED => 'موقوف',
            self::PENDING => 'قيد الانتظار',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::ACTIVE => 'success', // أخضر
            self::INACTIVE => 'secondary', // رمادي
            self::SUSPENDED => 'danger', // أحمر
            self::PENDING => 'warning', // أصفر
        };
    }
}
