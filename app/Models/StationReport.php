<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'water_pumped_m3',
        'power_source',
        'solar_hours',
        'grid_hours',
        'generator_hours',
        'solar_grid_hours',
        'solar_generator_hours',
        'grid_power_kwh',
        'diesel_consumed_liters',
    ];
}


