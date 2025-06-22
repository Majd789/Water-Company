<?php

namespace App\Imports;

use App\Models\HorizontalPump;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class HorizontalPumpsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد المضخات الأفقية
        foreach ($rows as $row) {
            HorizontalPump::create([
                'station_id' => $row[0], // كود المحطة
                'pump_status' => $row[1] ?? 'يعمل', // الوضع التشغيلي للمضخة
                'pump_name' => $row[2], // اسم المضخة
                'pump_capacity_hp' => $row[3], // استطاعة المضخة (حصان)
                'pump_flow_rate_m3h' => $row[4], // تدفق المضخة (متر مكعب/ساعة)
                'pump_head' => $row[5], // ارتفاع الضخ
                'pump_brand_model' => $row[6], // ماركة وطراز المضخة
                'technical_condition' => $row[7], // الحالة الفنية
                'energy_source' => $row[8], // مصدر الطاقة
                'notes' => $row[9] ?? null, // ملاحظات
            ]);
        }
    }

    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.pump_name' => 'required|string', // التحقق من وجود اسم المضخة
        '*.pump_capacity_hp' => 'required|numeric', // التحقق من استطاعة المضخة (حصان)
        '*.pump_flow_rate_m3h' => 'required|numeric', // التحقق من تدفق المضخة
        '*.pump_head' => 'required|numeric', // التحقق من ارتفاع الضخ
        '*.pump_brand_model' => 'required|string', // التحقق من ماركة وطراز المضخة
    ];
}

}
