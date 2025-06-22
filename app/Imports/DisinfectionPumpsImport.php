<?php

namespace App\Imports;

use App\Models\DisinfectionPump;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DisinfectionPumpsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد مضخات التعقيم
        foreach ($rows as $row) {
            DisinfectionPump::create([
                'station_id' => $row[0], // كود المحطة
                'disinfection_pump_status' => $row[1] ?? 'يعمل', // الوضع التشغيلي للمضخة
                'pump_brand_model' => $row[2], // ماركة وطراز المضخة
                'pump_flow_rate' => $row[3], // غزارة المضخة (لتر/ساعة)
                'operating_pressure' => $row[4], // ضغط العمل
                'technical_condition' => $row[5], // الحالة الفنية
                'notes' => $row[6] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.pump_brand_model' => 'required|string', // التحقق من وجود ماركة وطراز المضخة
        '*.pump_flow_rate' => 'required|numeric', // التحقق من غزارة المضخة
        '*.operating_pressure' => 'required|numeric', // التحقق من ضغط العمل
        '*.technical_condition' => 'required|string', // التحقق من الحالة الفنية
    ];
}

}
