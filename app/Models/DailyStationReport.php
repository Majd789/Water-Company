<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyStationReport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daily_station_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_date',
        'report_time',
        'operator_id',
        'unit_id',
        'town_id',
        'station_id',
        'station_code_snapshot',
        'daily_operational_status',
        'daily_stop_reason',
        'daily_operator_entity',
        'daily_operator_entity_name',
        'active_wells_during_pumping_count',
        'well_1_operating_hours',
        'well_2_operating_hours',
        'well_3_operating_hours',
        'well_4_operating_hours',
        'well_5_operating_hours',
        'well_6_operating_hours',
        'well_7_operating_hours',
        'total_station_pumping_hours',
        'has_horizontal_pump',
        'horizontal_pump_operating_hours',
        'station_operation_method_notes',
        'pumping_sector_id',
        'daily_has_disinfection',
        'daily_no_disinfection_reason',
        'daily_energy_source',
        'hours_electric_solar_blend',
        'hours_generator_solar_blend',
        'hours_on_solar',
        'hours_on_electricity',
        'hours_on_generator',
        'electricity_consumed_kwh',
        'electric_meter_reading_start',
        'electric_meter_reading_end',
        'diesel_consumed_liters_during_operation',
        'generator_oil_changed',
        'oil_added_to_generator_liters',
        'water_pumped_to_network_m3',
        'diesel_in_station_total_liters',
        'new_diesel_shipment_received',
        'new_diesel_shipment_quantity_liters',
        'diesel_shipment_supplier',
        'station_equipment_modified_today',
        'equipment_modification_location_type',
        'equipment_modification_description_reason',
        'equipment_transferred_to_entity',
        'electricity_meter_recharged_today',
        'electricity_recharged_amount_kwh',
        'shift_operator_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
        'report_time' => 'datetime:H:i:s', // أو 'string' إذا كنت تفضل التعامل معه كنص
        'active_wells_during_pumping_count' => 'integer',
        'well_1_operating_hours' => 'decimal:2',
        'well_2_operating_hours' => 'decimal:2',
        'well_3_operating_hours' => 'decimal:2',
        'well_4_operating_hours' => 'decimal:2',
        'well_5_operating_hours' => 'decimal:2',
        'well_6_operating_hours' => 'decimal:2',
        'well_7_operating_hours' => 'decimal:2',
        'total_station_pumping_hours' => 'decimal:2',
        'has_horizontal_pump' => 'boolean',
        'horizontal_pump_operating_hours' => 'decimal:2',
        'daily_has_disinfection' => 'boolean',
        'hours_electric_solar_blend' => 'decimal:2',
        'hours_generator_solar_blend' => 'decimal:2',
        'hours_on_solar' => 'decimal:2',
        'hours_on_electricity' => 'decimal:2',
        'hours_on_generator' => 'decimal:2',
        'electricity_consumed_kwh' => 'decimal:2',
        'diesel_consumed_liters_during_operation' => 'decimal:2',
        'generator_oil_changed' => 'boolean',
        'oil_added_to_generator_liters' => 'decimal:2',
        'water_pumped_to_network_m3' => 'decimal:2',
        'diesel_in_station_total_liters' => 'decimal:2',
        'new_diesel_shipment_received' => 'boolean',
        'new_diesel_shipment_quantity_liters' => 'decimal:2',
        'station_equipment_modified_today' => 'boolean',
        'electricity_meter_recharged_today' => 'boolean',
        'electricity_recharged_amount_kwh' => 'decimal:2',
    ];

    /**
     * Get the user (operator) who created the report.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Get the water unit associated with this report.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the town associated with this report.
     */
    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class, 'town_id');
    }

    /**
     * Get the station associated with this report.
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    /**
     * Get the pumping sector associated with this report.
     */
    public function pumpingSector(): BelongsTo
    {
        // تأكد من أن اسم الموديل 'PumpingSector' صحيح وموجود في App\Models
        return $this->belongsTo(PumpingSector::class, 'pumping_sector_id');
    }
}