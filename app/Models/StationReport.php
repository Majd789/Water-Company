<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\StationOperationStatus;
use App\Enum\StationOperatingEntityEum;
use App\Enum\EnergyResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PumpingSector;
class StationReport extends Model
{
    use HasFactory;

    protected $table = 'stations_reports';

    protected $fillable = [
        'unit_id',
        'station_id',
        'operator_id',
        'report_date',
        'status',
        'operating_entity',
        'operating_entity_name',
        'stop_reason',
        'notes',
        'number_well',
        'well1_operating_hours',
        'well2_operating_hours',
        'well3_operating_hours',
        'well4_operating_hours',
        'well5_operating_hours',
        'well6_operating_hours',
        'well7_operating_hours',
        'operating_hours',
        'is_horizontal_pump',
        'horizontal_pump_operating_hours',
        'pumping_sector_id',
        'is_sterile',
        'energy_resource',
        'water_pumped_m3',
        'power_source',
        'electricity_hours',
        'electricity_power_kwh',
        'electricity_Counter_number_before',
        'electricity_Counter_number_after',
        'solar_hours',
        'generator_hours',
        'diesel_consumed_liters',
        'electricity_solar_hours',
        'solar_generator_hours',
        'Total_desil_liters',
        'is_diesel_received',
        'quantity_of_diesel_received_liters',
        'diesel_source',
        'is_there_an_oil_change',
        'quantity_of_oil_added',
        'has_station_been_modified',
        'station_modification_type',
        'station_modification_notes',
        'is_the_electricity_meter_charged',
        'quantity_of_electricity_meter_charged_kwh',
    ];

    protected $casts = [
        'report_date' => 'date',
        'status' => StationOperationStatus::class,
        'operating_entity' => StationOperatingEntityEum::class,
        'power_source' => EnergyResource::class,
        'is_horizontal_pump' => 'boolean',
        'is_sterile' => 'boolean',
        'is_diesel_received' => 'boolean',
        'has_station_been_modified' => 'boolean',
        'is_the_electricity_meter_charged' => 'boolean',
        'is_there_an_oil_change' => 'boolean',
    ];
        //لعرض ساعات تشغيل الآبار كمصفوفة في الواجهة
          /**
     * Accessor to get all well operating hours as a single array.
     * This allows us to use $report->well_operating_hours in the view.
     *
     * @return Attribute
     */
    protected function wellOperatingHours(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                $this->well1_operating_hours ?? 0,
                $this->well2_operating_hours ?? 0,
                $this->well3_operating_hours ?? 0,
                $this->well4_operating_hours ?? 0,
                $this->well5_operating_hours ?? 0,
                $this->well6_operating_hours ?? 0,
                $this->well7_operating_hours ?? 0,
            ],
        );
    }
    
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    // المشغل
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function pumpingSector(): BelongsTo
    {
        return $this->belongsTo(PumpingSector::class, 'pumping_sector_id');
    }
    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}


