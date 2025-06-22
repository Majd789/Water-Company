<?php

namespace App\Imports;

use App\Models\GroundTank;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class GroundTanksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الخزانات الأرضية
        foreach ($rows as $row) {
            GroundTank::create([
                'station_id' => $row[0], // كود المحطة
                'tank_name' => $row[1], // اسم الخزان
                'building_entity' => $row[2], // الكيان المسؤول عن البناء
                'construction_type' => $row[3], // نوع البناء (قديم أو جديد)
                'capacity' => $row[4], // السعة
                'readiness_percentage' => $row[5], // نسبة الجاهزية
                'feeding_station' => $row[6], // محطة التغذية
                'town_supply' => $row[7], // إمدادات البلدة
                'pipe_diameter_inside' => $row[8], // قطر الأنبوب الداخلي
                'pipe_diameter_outside' => $row[9], // قطر الأنبوب الخارجي
                'latitude' => $row[10] ?? null, // خط العرض
                'longitude' => $row[11] ?? null, // خط الطول
                'altitude' => $row[12] ?? null, // الارتفاع
                'precision' => $row[13] ?? null, // الدقة
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
        '*.feeding_station' => 'required|string', // التحقق من محطة التغذية
        '*.pipe_diameter_inside' => 'required|numeric', // التحقق من قطر الأنبوب الداخلي
        '*.pipe_diameter_outside' => 'required|numeric', // التحقق من قطر الأنبوب الخارجي
    ];
}

}
