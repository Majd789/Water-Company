<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Well extends Model
{
   use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'Well'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('Well');
    }


    protected $fillable = [
        'station_id',
        'town_code',
        'well_name',
        'well_status',
        'stop_reason',
        'distance_from_station',
        'well_type',
        'well_flow',
        'static_depth',
        'dynamic_depth',
        'drilling_depth',
        'well_diameter',
        'pump_installation_depth',
        'pump_capacity',
        'actual_pump_flow',
        'pump_lifting',
        'pump_brand_model',
        'energy_source',
        'well_address',
        'general_notes',
        'well_location',
    ];

    // العلاقة مع المحطة
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}