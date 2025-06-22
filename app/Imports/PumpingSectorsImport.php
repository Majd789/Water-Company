<?php

namespace App\Imports;

use App\Models\PumpingSector;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class PumpingSectorsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد قطاعات الضخ
        foreach ($rows as $row) {
            PumpingSector::create([
                'station_id' => $row[0], // كود المحطة
                'sector_name' => $row[1], // اسم القطاع
                'town_id' => $row[2], // كود البلدة
                'notes' => $row[3] ?? null, // ملاحظات
            ]);
        }
    }

    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.sector_name' => 'required|string', // التحقق من وجود اسم القطاع
        '*.town_id' => 'required|exists:towns,id', // التحقق من وجود `town_id` في جدول البلدة
    ];
}

}
