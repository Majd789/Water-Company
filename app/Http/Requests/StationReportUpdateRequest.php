<?php

namespace App\Http\Requests;
use App\Enum\UserLevel; // [إضافة]
use App\Enum\StationOperationStatus; // [إضافة]
use App\Enum\StationOperatingEntityEum; // [إضافة]
use App\Enum\EnergyResource; // [إضافة]
use Illuminate\Validation\Rule; // [إضافة]
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class StationReportUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->can('station_reports.edit');
    }

    public function rules(): array
    {
 return [
            'unit_id' => ['sometimes', 'nullable', 'exists:units,id'],
            'station_id' => ['sometimes', 'nullable', 'exists:stations,id'],
            'operator_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'report_date' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', Rule::enum(StationOperationStatus::class)],
            'operating_entity' => ['sometimes', 'required', Rule::enum(StationOperatingEntityEum::class)],
            'operating_entity_name' => ['sometimes', 'nullable', 'string', 'max:255', 'required_if:operating_entity,shared'],
            'stop_reason' => ['sometimes', 'nullable', 'string', 'required_if:status,stopped'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'number_well' => ['sometimes', 'required', 'integer', 'min:0', 'max:7'],

            'well1_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well2_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well3_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well4_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well5_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well6_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'well7_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],

            'operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'is_horizontal_pump' => ['sometimes', 'nullable', 'boolean'],
            'horizontal_pump_operating_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'pumping_sector_id' => ['sometimes', 'nullable', 'exists:pumping_sectors,id'],
            'is_sterile' => ['sometimes', 'nullable', 'boolean'],
            'water_pumped_m3' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'power_source' => ['sometimes', 'required', Rule::enum(EnergyResource::class)],
            'electricity_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'electricity_power_kwh' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'electricity_Counter_number_before' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'electricity_Counter_number_after' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'solar_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'generator_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'diesel_consumed_liters' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'electricity_solar_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'solar_generator_hours' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'Water_production_m3' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'Total_desil_liters' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'is_diesel_received' => ['sometimes', 'nullable', 'boolean'],
            'quantity_of_diesel_received_liters' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'diesel_source' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_there_an_oil_change' => ['sometimes', 'nullable', 'boolean'],
            'quantity_of_oil_added' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'has_station_been_modified' => ['sometimes', 'nullable', 'boolean'],
            'station_modification_type' => ['sometimes', 'nullable', 'string'],
            'station_modification_notes' => ['sometimes', 'nullable', 'string'],
            'is_the_electricity_meter_charged' => ['sometimes', 'nullable', 'boolean'],
            'quantity_of_electricity_meter_charged_kwh' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ];
    }
}
