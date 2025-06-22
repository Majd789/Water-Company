<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ElevatedTank extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'ElevatedTank'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('ElevatedTank');
    }

    protected $fillable = [
        'station_id',
        'tank_name',
        'building_entity',
        'construction_date',
        'capacity',
        'readiness_percentage',
        'height',
        'tank_shape',
        'feeding_station',
        'town_supply',
        'in_pipe_diameter',
        'out_pipe_diameter',
        'latitude',
        'longitude',
        'altitude',
        'precision',
        'notes',
    ];
    

    /**
     * العلاقة مع المحطة
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
