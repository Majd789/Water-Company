<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    // السماح بإدخال جميع الأعمدة ما عدا الـ id
    protected $guarded = ['id'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
