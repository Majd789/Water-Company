<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationReportResource extends JsonResource
{
    /**
     * تحويل المورد إلى مصفوفة.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // --- البيانات الأساسية للتقرير ---
            'id' => $this->id,
            'report_date' => $this->report_date->toDateString(),
            'status' => $this->status,
            'operating_entity' => $this->operating_entity,
            'operating_entity_name' => $this->operating_entity_name,

            // --- معلومات المحطة والوحدة والمشغل ---
            'station_info' => [
                'station_id' => $this->station_id,
                'station_name' => $this->whenLoaded('station', fn() => $this->station->station_name),
                'unit_id' => $this->unit_id,
                'unit_name' => $this->whenLoaded('unit', fn() => $this->unit->name),
                'operator_id' => $this->operator_id,
                'operator_name' => $this->whenLoaded('operator', fn() => $this->operator->name),
            ],

            // --- تفاصيل التشغيل والضخ ---
            'operation' => [
                'operating_hours' => (float) $this->operating_hours,
                'water_pumped_m3' => (float) $this->water_pumped_m3,
                'water_production_m3' => (float) $this->Water_production_m3,
                'is_sterile' => (bool) $this->is_sterile,
                'pumping_sector_id' => $this->pumping_sector_id,
            ],

            // --- تفاصيل الآبار والمضخة الأفقية ---
            'pumps_and_wells' => [
                'number_of_wells' => (int) $this->number_well,
                'wells_operating_hours' => [
                    'well_1' => (float) $this->well1_operating_hours,
                    'well_2' => (float) $this->well2_operating_hours,
                    'well_3' => (float) $this->well3_operating_hours,
                    'well_4' => (float) $this->well4_operating_hours,
                    'well_5' => (float) $this->well5_operating_hours,
                    'well_6' => (float) $this->well6_operating_hours,
                    'well_7' => (float) $this->well7_operating_hours,
                ],
                'has_horizontal_pump' => (bool) $this->is_horizontal_pump,
                'horizontal_pump_operating_hours' => (float) $this->horizontal_pump_operating_hours,
            ],

            // --- تفاصيل الطاقة ---
            'energy' => [
                'power_source' => $this->power_source,
                'energy_resource' => $this->energy_resource,
                'hours' => [
                    'electricity' => (float) $this->electricity_hours,
                    'solar' => (float) $this->solar_hours,
                    'generator' => (float) $this->generator_hours,
                ],
                'combined_hours' => [
                    'electricity_solar' => (float) $this->electricity_solar_hours,
                    'solar_generator' => (float) $this->solar_generator_hours,
                ],
                'electricity_consumption_kwh' => (float) $this->electricity_power_kwh,
                'electricity_meter' => [
                    'before' => (float) $this->electricity_Counter_number_before,
                    'after' => (float) $this->electricity_Counter_number_after,
                ],
            ],

            // --- تفاصيل الديزل ---
            'diesel' => [
                'total_diesel_liters' => (float) $this->Total_desil_liters,
                'consumed_diesel_liters' => (float) $this->diesel_consumed_liters,
                'was_diesel_received' => (bool) $this->is_diesel_received,
                'received_quantity_liters' => (float) $this->quantity_of_diesel_received_liters,
                'diesel_source' => $this->diesel_source,
            ],

            // --- تفاصيل التعديلات وشحن العداد ---
            'modifications_and_charging' => [
                'was_station_modified' => (bool) $this->has_station_been_modified,
                'modification_type' => $this->station_modification_type,
                'modification_notes' => $this->station_modification_notes,
                'was_meter_charged' => (bool) $this->is_the_electricity_meter_charged,
                'charged_quantity_kwh' => (float) $this->quantity_of_electricity_meter_charged_kwh,
            ],

            // --- الملاحظات ---
            'notes' => [
                'stop_reason' => $this->stop_reason,
                'general_notes' => $this->notes,
                'updated_by'=>$this->updated_by,
                'is_checked'=>$this->is_checked,
                'checked_by'=>$this->checked_by,
            ],

            // --- التوقيت ---
            'timestamps' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}

