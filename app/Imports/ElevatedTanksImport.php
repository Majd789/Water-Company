<?php

namespace App\Imports;

use App\Models\ElevatedTank;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ElevatedTanksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الخزانات المرتفعة
        foreach ($rows as $row) {
            ElevatedTank::create([
                'station_id' => $row[0], // كود المحطة
                'tank_name' => $row[1], // اسم الخزان
                'building_entity' => $row[2], // الجهة المنشئة
                'construction_date' => $row[3], // تاريخ البناء (جديد أو قديم)
                'capacity' => $row[4], // سعة الخزان
                'readiness_percentage' => $row[5], // نسبة الجاهزية
                'height' => $row[6], // ارتفاع الخزان
                'tank_shape' => $row[7], // شكل الخزان
                'feeding_station' => $row[8], // المحطة التي تعبئه
                'town_supply' => $row[9], // البلدة التي تشرب منه
                'in_pipe_diameter' => $row[10], // قطر البوري (داخل)
                'out_pipe_diameter' => $row[11], // قطر البوري (خارج)
                'latitude' => $row[12] ?? null, // خط العرض
                'longitude' => $row[13] ?? null, // خط الطول
                'altitude' => $row[14] ?? null, // الارتفاع
                'precision' => $row[15] ?? null, // دقة الموقع
                'notes' => $row[16] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.tank_name' => 'required|string', // التحقق من وجود اسم الخزان
        '*.capacity' => 'required|numeric', // التحقق من السعة
        '*.readiness_percentage' => 'required|numeric', // التحقق من نسبة الجاهزية
        '*.height' => 'required|numeric', // التحقق من الارتفاع
        '*.feeding_station' => 'required|string', // التحقق من المحطة التي تعبئ الخزان
        '*.town_supply' => 'required|string', // التحقق من البلدة التي تشرب منه
        '*.in_pipe_diameter' => 'required|numeric', // التحقق من قطر البوري الداخلي
        '*.out_pipe_diameter' => 'required|numeric', // التحقق من قطر البوري الخارجي
    ];
}

}
