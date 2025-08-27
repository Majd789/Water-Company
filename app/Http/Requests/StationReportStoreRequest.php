<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StationReportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['nullable', 'exists:units,id'],
            'station_id' => ['nullable', 'exists:stations,id'],
            'operator_id' => ['nullable', 'exists:users,id'],
            'report_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string'],
            'operating_entity' => ['nullable', 'string'],
            'operating_entity_name' => ['nullable', 'string', 'max:255'],
            'stop_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'number_well' => ['nullable', 'integer', 'min:0'],
            'well1_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well2_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well3_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well4_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well5_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well6_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'well7_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'operating_hours' => ['nullable', 'numeric', 'min:0'],
            'is_horizontal_pump' => ['nullable', 'boolean'],
            'horizontal_pump_operating_hours' => ['nullable', 'numeric', 'min:0'],
            'pumping_sector_id' => ['nullable', 'exists:pumping_sectors,id'],
            'is_sterile' => ['nullable', 'boolean'],
            'water_pumped_m3' => ['nullable', 'numeric', 'min:0'],
            'power_source' => ['nullable', 'string'],
            'solar_hours' => ['nullable', 'numeric', 'min:0'],
            'grid_hours' => ['nullable', 'numeric', 'min:0'],
            'generator_hours' => ['nullable', 'numeric', 'min:0'],
            'solar_grid_hours' => ['nullable', 'numeric', 'min:0'],
            'solar_generator_hours' => ['nullable', 'numeric', 'min:0'],
            'grid_power_kwh' => ['nullable', 'numeric', 'min:0'],
            'diesel_consumed_liters' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}


