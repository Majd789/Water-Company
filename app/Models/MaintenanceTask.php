<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class MaintenanceTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'technician_name',
        'maintenance_date',
        'unit_id',
        'location',
        'fault_description',
        'fault_cause',
        'maintenance_actions',
        'is_fixed',
        'reason_not_fixed',
        'notes',
    ];

     public function unit()
    {
        return $this->belongsTo(Unit::class);
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
