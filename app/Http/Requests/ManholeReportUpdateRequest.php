<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ManholeReportUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // سيتم التحقق من الصلاحيات التفصيلية في المتحكم
        // هنا نتأكد فقط من أن المستخدم مسجل دخوله
        return Auth::check();
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
            'unit_id' => 'sometimes|required|exists:units,id',
            'station_id' => 'sometimes|required|exists:stations,id',
            'manhole_id' => 'sometimes|required|exists:manholes,id',
            'status' => 'sometimes|required|string|in:working,stopped,maintenance',
            'stop_reason' => 'nullable|string|max:1000',
            'has_flow_meter' => 'sometimes|required|boolean',
            'flow_meter_counter_number_before' => 'nullable|numeric|gte:0',
            'flow_meter_counter_number_after' => 'nullable|numeric|gt:flow_meter_counter_number_before',
            'water_flow_m3' => 'nullable|numeric|gte:0',
            'water_m3_price' => 'nullable|numeric|gte:0',
            'total_water_price' => 'nullable|numeric|gte:0',
            'has_water_refill_for_tankers' => 'sometimes|required|boolean',
            'water_refill_for_tankers_m3' => 'nullable|numeric|gte:0',
            'has_free_water_distribution' => 'sometimes|required|boolean',
            'free_water_distribution_m3' => 'nullable|numeric|gte:0',
            'book_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}