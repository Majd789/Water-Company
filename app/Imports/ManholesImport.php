<?php

namespace App\Imports;

use App\Models\Manhole;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ManholesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد المنهولات
        foreach ($rows as $row) {
            Manhole::create([
                'station_id' => $row[0], // كود المحطة
                'unit_id' => $row[1], // كود الوحدة
                'town_id' => $row[2], // كود البلدة
                'manhole_name' => $row[3], // اسم المنهل
                'status' => $row[4], // هل يعمل أو متوقف
                'stop_reason' => $row[5] ?? null, // سبب التوقف
                'has_flow_meter' => $row[6] ?? false, // هل يوجد عداد غزارة
                'chassis_number' => $row[7] ?? null, // رقم الشاسيه
                'meter_diameter' => $row[8] ?? null, // قطر العداد
                'meter_status' => $row[9] ?? null, // هل يعمل أو متوقف العداد
                'meter_operation_method_in_meter' => $row[10] ?? null, // طريقة عمل العداد
                'has_storage_tank' => $row[11] ?? false, // هل يوجد خزان تجميعي
                'tank_capacity' => $row[12] ?? null, // سعة الخزان
                'general_notes' => $row[13] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.unit_id' => 'required|exists:units,id', // التحقق من وجود `unit_id` في جدول الوحدات
        '*.town_id' => 'required|exists:towns,id', // التحقق من وجود `town_id` في جدول البلدة
        '*.manhole_name' => 'required|string', // التحقق من اسم المنهل
        '*.status' => 'required|in:يعمل,متوقف', // التحقق من حالة المنهل
        '*.has_flow_meter' => 'nullable|boolean', // التحقق من وجود عداد الغزارة
        '*.has_storage_tank' => 'nullable|boolean', // التحقق من وجود خزان تجميعي
    ];
}

}
