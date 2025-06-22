<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ElectricityTransformer extends Model
{
   use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'ElectricityTransformer'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('ElectricityTransformer');
    }

    // اسم الجدول في قاعدة البيانات (اختياري إذا كان الاسم يتبع قاعدة الجمع)
    protected $table = 'electricity_transformers';

    // الأعمدة القابلة للتعبئة
    protected $fillable = [
        'station_id',
        'operational_status',
        'transformer_capacity',
        'distance_from_station',
        'is_station_transformer',
        'talk_about_station_transformer',
        'is_capacity_sufficient',
        'how_mush_capacity_need',
        'notes',
    ];
    

    /**
     * العلاقة مع جدول المحطات (stations)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * التحقق مما إذا كانت المحولة قيد العمل
     * 
     * @return bool
     */
    public function isOperational()
    {
        return $this->operational_status === 'عاملة';
    }

    /**
     * التحقق مما إذا كانت المحولة خاصة بالمحطة
     * 
     * @return bool
     */
    public function isStationTransformer()
    {
        return $this->is_station_transformer;
    }
}
