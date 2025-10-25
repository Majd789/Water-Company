<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
class Infiltrator extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'Infiltrator'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('Infiltrator');
    }


    protected $fillable = [
        'station_id',
        'infiltrator_capacity',
        'readiness_status',
        'infiltrator_type',
        'notes',
    ];

    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
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
