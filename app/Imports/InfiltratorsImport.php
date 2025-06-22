<?php

namespace App\Imports;

use App\Models\Infiltrator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class InfiltratorsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الإنفلترات
        foreach ($rows as $row) {
            Infiltrator::create([
                'station_id' => $row[0], // كود المحطة
                'infiltrator_capacity' => $row[1], // استطاعة الانفلتر
                'readiness_status' => $row[2], // جاهزية الانفلتر
                'infiltrator_type' => $row[3], // نوع الانفلتر
                'notes' => $row[4] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.infiltrator_capacity' => 'required|numeric', // التحقق من استطاعة الانفلتر
        '*.readiness_status' => 'required|numeric|min:0|max:100', // التحقق من جاهزية الانفلتر (نسبة مئوية)
        '*.infiltrator_type' => 'required|string', // التحقق من نوع الانفلتر
    ];
}

}
