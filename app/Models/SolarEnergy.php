<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SolarEnergy extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'SolarEnergy'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('SolarEnergy');
    }


    protected $fillable = [
        'station_id',
        'panel_size',
        'panel_count',
        'manufacturer',
        'base_type',
        'technical_condition',
        'wells_supplied_count',
        'general_notes',
        'latitude',
        'longitude',
    ];

    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
