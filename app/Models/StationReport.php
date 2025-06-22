<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;

class StationReport extends Model
{
    use HasFactory ,Importable;

    protected $table = 'station_reports'; // اسم الجدول في قاعدة البيانات

    protected $fillable = [
        'start',
        'end',
        'date',
        'إسم المُشغل المناوب في المنهل',
        'وحدة المياه',
        'البلدة',
        'المحطات',
        'station_code',
        'الوضع التشغيلي',
        'سبب التوقف',
        'operator_entity',
        'operator_company',
        'operating_wells_count',
        'well_1_hours',
        'well_2_hours',
        'well_3_hours',
        'well_4_hours',
        'well_5_hours',
        'well_6_hours',
        'well_7_hours',
        'total_well_hours',
        'has_horizontal_pump',
        'horizontal_pump_hours',
        'station_operation_method',
        'target_sector',
        'has_disinfection',
        'no_disinfection_reason',
        'energy_source',
        'solar_electricity_hours',
        'solar_generator_hours',
        'solar_only_hours',
        'electricity_hours',
        'electricity_consumption_kwh',
        'electric_meter_before',
        'electric_meter_after',
        'generator_hours',
        'diesel_consumption',
        'oil_replacement',
        'oil_quantity',
        'water_pumped_m3',
        'total_diesel_stock',
        'diesel_received',
        'new_diesel_quantity',
        'diesel_provider',
        'station_modification',
        'modification_location',
        'modification_details',
        'transfer_destination',
        'electric_meter_charged',
        'charged_electricity_kwh',
        'operator_notes',
    ];

    // ساعات تشغيل كل بئر
    protected $casts = [
        'has_horizontal_pump' => 'boolean',
        'has_disinfection' => 'boolean',
        'oil_replacement' => 'boolean',
        'diesel_received' => 'boolean',
        'station_modification' => 'boolean',
        'electric_meter_charged' => 'boolean',
    ];
}
