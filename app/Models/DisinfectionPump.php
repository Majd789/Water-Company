<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DisinfectionPump extends Model
{
   use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'DisinfectionPump'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('DisinfectionPump');
    }

    protected $fillable = [
        'station_id', 'has_disinfection_pump', 'disinfection_pump_status', 'pump_brand_model', 
        'pump_flow_rate', 'operating_pressure', 'technical_condition', 'notes'
    ];

    // العلاقة مع المحطة
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
