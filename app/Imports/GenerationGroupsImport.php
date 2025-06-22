<?php

namespace App\Imports;

use App\Models\GenerationGroup;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class GenerationGroupsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد مجموعات التوليد
        foreach ($rows as $row) {
            GenerationGroup::create([
                'station_id' => $row[0], // كود المحطة
                'operational_status' => $row[1] ?? 'عاملة', // الوضع التشغيلي
                'generator_name' => $row[2], // اسم المولدة
                'generation_capacity' => $row[3], // استطاعة التوليد (KVA)
                'actual_operating_capacity' => $row[4], // استطاعة العمل الفعلية
                'generation_group_readiness_percentage' => $row[5] ?? null, // نسبة الجاهزية
                'fuel_consumption' => $row[6], // استهلاك الوقود (لتر/ساعة)
                'oil_usage_duration' => $row[7], // مدة استخدام الزيت
                'oil_quantity_for_replacement' => $row[8], // كمية الزيت في التبديل
                'notes' => $row[9] ?? null, // ملاحظات
                'stop_reason' => $row[10] ?? null, // سبب التوقف
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.generator_name' => 'required|string', // التحقق من وجود اسم المولدة
        '*.generation_capacity' => 'required|numeric', // التحقق من الاستطاعة
        '*.fuel_consumption' => 'required|numeric', // التحقق من استهلاك الوقود
        '*.oil_usage_duration' => 'required|integer', // التحقق من مدة استخدام الزيت
    ];
}

}
