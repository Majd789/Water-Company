<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;

class Station extends Model implements HasMedia
{
    use HasFactory, LogsActivity , InteractsWithMedia;


    protected static $logAttributes = ['*']; // تتبع كل الحقول
    protected static $logName = 'station'; // الاسم الذي يظهر في سجل النشاطات
    protected static $logOnlyDirty = true; // يسجل فقط التغييرات

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('station');
    }

    protected $fillable = [
        'station_code', 'station_name', 'operational_status', 'stop_reason',
        'energy_source', 'operator_entity', 'operator_name', 'general_notes',
        'town_id', 'water_delivery_method', 'network_readiness_percentage',
        'network_type', 'beneficiary_families_count', 'has_disinfection',
        'disinfection_reason', 'served_locations', 'actual_flow_rate',
        'station_type', 'detailed_address', 'land_area', 'soil_type',
        'building_notes', 'latitude', 'longitude', 'is_verified'
    ];

    public function town() //البلدة
    {
        return $this->belongsTo(Town::class);
    }

    public function wells() // الابار
    {
        return $this->hasMany(Well::class);
    }

    public function generationGroups() // مجموعات التوليد
    {
        return $this->hasMany(GenerationGroup::class);
    }

    public function horizontalPumps() //المضخات الافقية
    {
        return $this->hasMany(HorizontalPump::class);
    }

    public function groundTanks()//الخزانات الارضية
    {
        return $this->hasMany(GroundTank::class);
    }


    public function pumpingSectors(): HasMany //قطاعات لاضخ
    {
        // 'station_id' هو المفتاح الأجنبي في جدول 'pumping_sectors'
        // PumpingSector::class هو الموديل المرتبط
        return $this->hasMany(PumpingSector::class, 'station_id');
    }
    public function solarEnergies() //طاقة الشمسية
    {
    return $this->hasMany(SolarEnergy::class);
    }
    public function filters() //المرشحات
    {
        return $this->hasMany(Filter::class);
    }
    public function manholes() //المحطات المرشحة
    {
        return $this->hasMany(Manhole::class);
    }


    public function infiltrator() //المحولات رافع الجهد
    {
        return $this->hasMany(Infiltrator::class);
    }
    public function dieselTank() //الخزانات الوقود
    {
        return $this->hasMany(DieselTank::class);
    }

    public function disinfectionPump() // مضخات التعقيم
    {
        return $this->hasMany(DisinfectionPump::class);
    }
    public function electricityTransformer() //محولات الكهرباء
    {
        return $this->hasMany(ElectricityTransformer::class);
    }
    public function elevatedTanks() // العاليةى الخزانات المياه
    {
        return $this->hasMany(ElevatedTank::class);
    }

    public function electricityHours() // ساعات الكهرباء
    {
        return $this->hasMany(ElectricityHour::class);
    }
}
