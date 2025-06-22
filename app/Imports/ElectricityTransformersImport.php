<?php

namespace App\Imports;

use App\Models\ElectricityTransformer;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ElectricityTransformersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد محولات الكهرباء
        foreach ($rows as $row) {
            ElectricityTransformer::create([
                'station_id' => $row[0], // كود المحطة
                'operational_status' => $row[1], // الوضع التشغيلي للمحولة
                'transformer_capacity' => $row[2], // استطاعة المحولة
                'distance_from_station' => $row[3], // بعد المحولة عن المحطة
                'is_station_transformer' => $row[4] ?? false, // هل المحولة خاصة بالمحطة
                'talk_about_station_transformer' => $row[5] ?? null, // تحدث سردا
                'is_capacity_sufficient' => $row[6] ?? true, // هل الاستطاعة كافية
                'how_mush_capacity_need' => $row[7], // كم الاستطاعة المحتاجة
                'notes' => $row[8] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.transformer_capacity' => 'required|numeric', // التحقق من وجود استطاعة المحولة
        '*.how_mush_capacity_need' => 'required|numeric', // التحقق من وجود الاستطاعة المحتاجة
    ];
}

}