<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class Governorate extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
    public function metrics(): MorphMany
    {
        return $this->morphMany(Metric::class, 'metricable');
    }
     public function assessments(): MorphMany
    {
        return $this->morphMany(Assessment::class, 'assessmentable');
    }
}
