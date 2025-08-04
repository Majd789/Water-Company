<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

use Spatie\Activitylog\LogOptions;

class Station extends Model
{
    use HasFactory, LogsActivity;

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

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function wells()
    {
        return $this->hasMany(Well::class);
    }

    public function generationGroups()
    {
        return $this->hasMany(GenerationGroup::class);
    }

    public function horizontalPumps()
    {
        return $this->hasMany(HorizontalPump::class);
    }

    public function groundTanks()
    {
        return $this->hasMany(GroundTank::class);
    }

    public function waterWells()
    {
        return $this->hasMany(WaterWell2::class, 'station_code', 'station_code');
    }
    public function pumpingSectors(): HasMany
    {
        // 'station_id' هو المفتاح الأجنبي في جدول 'pumping_sectors'
        // PumpingSector::class هو الموديل المرتبط
        return $this->hasMany(PumpingSector::class, 'station_id');
    }
    public function solarEnergies()
    {
    return $this->hasMany(SolarEnergy::class);
    }
    public function projectActivities() { return $this->hasMany(ProjectActivity::class); }

}
