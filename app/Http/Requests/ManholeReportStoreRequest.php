<?php

namespace App\Http\Requests;
use App\Enum\UserLevel;
use App\Enum\StationOperationStatus;
use App\Enum\StationOperatingEntityEum;
use App\Enum\EnergyResource;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ManholeReportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return Auth::user()->can('manhole_reports.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_id' => ['nullable', 'exists:units,id'],
            'station_id' => ['nullable', 'exists:stations,id'],
            'manhole_id' => ['nullable', 'exists:manholes,id'],
            'operator_id' => ['nullable', 'exists:users,id'],
            'report_date' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(StationOperationStatus::class)],
            'stop_reason' => ['nullable', 'string', 'required_if:status,stopped'],
            'has_flow_meter' => ['nullable', 'boolean'],
            'flow_meter_counter_number_before' => ['nullable', 'numeric', 'min:0'],
            'flow_meter_counter_number_after' => ['nullable', 'numeric', 'min:0'],
            'water_flow_m3' => ['nullable', 'numeric', 'min:0'],
            'water_m3_price' => ['nullable', 'numeric', 'min:0'],
            'total_water_price' => ['nullable', 'numeric', 'min:0'],
            'has_water_refill_for_tankers' => ['nullable', 'boolean'],
            'water_refill_for_tankers_m3' => ['nullable', 'numeric', 'min:0'],
            'has_free_water_distribution' => ['nullable', 'boolean'],
            'free_water_distribution_m3' => ['nullable', 'numeric', 'min:0'],
            'book_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
