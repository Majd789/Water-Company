<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enum\StationOperationStatus;
use Illuminate\Validation\Rule;
class ManholeReportUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return Auth::user()->can('manhole_reports.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // نفس قواعد الإنشاء تقريباً، لكن بدون "report_date"
        // نستخدم 'sometimes' للسماح بإرسال الحقول المراد تحديثها فقط (مناسب لـ PATCH)
        return [
          'manhole_id' => ['sometimes', 'required', 'exists:manholes,id'],
            'status' => ['sometimes', 'required', Rule::enum(StationOperationStatus::class)],
            
            'stop_reason' => ['sometimes', 'nullable', 'string', 'required_if:status,stopped'],
            'notes' => ['sometimes', 'nullable', 'string'],

            // --- قسم عداد الغزارة ---
            'has_flow_meter' => ['sometimes', 'required', 'boolean'],
            'flow_meter_counter_number_before' => ['sometimes', 'required_if:has_flow_meter,true', 'nullable', 'numeric', 'gte:0'],
            'flow_meter_counter_number_after' => ['sometimes', 'required_if:has_flow_meter,true', 'nullable', 'numeric', 'gte:0'],
            'water_flow_m3' => ['sometimes', 'required_if:status,working', 'nullable', 'numeric', 'gte:0'],
            'water_m3_price' => ['sometimes', 'required_if:status,working', 'nullable', 'numeric', 'gte:0'],
            'total_water_price' => ['sometimes', 'required_if:status,working', 'nullable', 'numeric', 'gte:0'],

            // --- قسم توزيع المياه ---
            'has_water_refill_for_tankers' => ['sometimes', 'required', 'boolean'],
            'water_refill_for_tankers_m3' => ['sometimes', 'required_if:has_water_refill_for_tankers,true', 'nullable', 'numeric', 'gte:0'],
            
            'has_free_water_distribution' => ['sometimes', 'required', 'boolean'],
            'free_water_distribution_m3' => ['sometimes', 'required_if:has_free_water_distribution,true', 'nullable', 'numeric', 'gte:0'],
            'book_number' => ['sometimes', 'required_if:has_free_water_distribution,true', 'nullable', 'string', 'max:255'],
        ];
    }
}