<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HorizontalPump extends Model
{
   use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'HorizontalPump'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('HorizontalPump');
    }


    protected $fillable = [
        'station_id', 'pump_status', 'pump_name', 
        'pump_capacity_hp', 'pump_flow_rate_m3h', 'pump_head', 
        'pump_brand_model', 'technical_condition', 'energy_source', 'notes'
    ];

    // العلاقة مع المحطة
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
