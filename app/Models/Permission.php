<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'group',
        'display_name',
        'description'
    ];

    public function getDisplayNameAttribute($value)
    {
        return $value ?? ucfirst(str_replace(['-', '_','.'], ' ', $this->name));
    }

}
