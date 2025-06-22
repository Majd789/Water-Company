<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Maintenance extends Model
{
   use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'Maintenance'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('Maintenance');
    }


    protected $fillable = [
        'station_id',
        'maintenance_type_id',
        'total_quantity',
        'execution_sites',
        'total_cost',
        'maintenance_date',
        'maintenance_details',
        'contractor_name',
        'technician_name',
        'status'
    ];

    // علاقة بالمحطة
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // علاقة بنوع الصيانة
    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }
}
