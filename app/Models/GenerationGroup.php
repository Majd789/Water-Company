<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GenerationGroup extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'GenerationGroup'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('GenerationGroup');
    }

    // اسم الجدول في قاعدة البيانات (اختياري إذا كان الاسم يتبع قاعدة الجمع)
    protected $table = 'generation_groups';

    // الأعمدة القابلة للتعبئة
    protected $fillable = [
        'station_id',
        'operational_status',
        'generator_name',
        'generation_capacity',
        'actual_operating_capacity',
        'generation_group_readiness_percentage',
        'fuel_consumption',
        'oil_usage_duration',
        'oil_quantity_for_replacement',
        'notes',
        'stop_reason',
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
     * التحقق من إذا كانت المجموعة قيد التشغيل
     * 
     * @return bool
     */
    public function isWorking()
    {
        return $this->operational_status === 'عاملة';
    }

    /**
     * حساب نسبة كفاءة المجموعة بناءً على استطاعة العمل الفعلية واستطاعة التوليد
     * 
     * @return float|null
     */
    public function calculateEfficiency()
    {
        if ($this->generation_capacity > 0) {
            return ($this->actual_operating_capacity / $this->generation_capacity) * 100;
        }

        return null;
    }
}
