<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManholeReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,


            'manhole_id' => $this->manhole_id,
            'manhole_name' => $this->whenLoaded('manhole', fn() => $this->manhole->name),
            'station_id' => $this->station_id,
            'station_name' => $this->whenLoaded('station', fn() => $this->station->station_name),
            'unit_id' => $this->unit_id,
            'unit_name' => $this->whenLoaded('unit', fn() => $this->unit->name),
            'operator_id' => $this->operator_id,
            'operator_name' => $this->whenLoaded('operator', fn() => $this->operator->name),
            'report_date' => $this->report_date,
            'status' => $this->status,
            'stop_reason' => $this->stop_reason,
            'has_flow_meter' => $this->has_flow_meter,
            'flow_meter_counter_number_before' => $this->flow_meter_counter_number_before,
            'flow_meter_counter_number_after' => $this->flow_meter_counter_number_after,
            'water_flow_m3' => $this->water_flow_m3,
            'water_m3_price' => $this->water_m3_price,
            'total_water_price' => $this->total_water_price,
            'has_water_refill_for_tankers' => $this->has_water_refill_for_tankers,
            'water_refill_for_tankers_m3' => $this->water_refill_for_tankers_m3,
            'has_free_water_distribution' => $this->has_free_water_distribution,
            'free_water_distribution_m3' => $this->free_water_distribution_m3,
            'book_number' => $this->book_number,
            'notes' => $this->notes,
            'timestamps' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}
