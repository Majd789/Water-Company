<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GroundTank extends Model
{
  use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'GroundTank'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('GroundTank');
    }

    protected $fillable = [
        'station_id',
        'tank_name',
        'building_entity',
        'construction_type',  // إضافة نوع البناء
        'capacity',
        'readiness_percentage',
        'feeding_station',
        'town_supply',
        'pipe_diameter_inside',
        'pipe_diameter_outside',
        'latitude',
        'longitude',
        'altitude',
        'precision',
    ];
    
    
    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
    
}
