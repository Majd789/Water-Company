<?php

namespace App\Imports;

use App\Models\ElectricityHour;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ElectricityHoursImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد ساعات الكهرباء
        foreach ($rows as $row) {
            ElectricityHour::create([
                'station_id' => $row[0], // كود المحطة
                'electricity_hours' => $row[1], // عدد ساعات الكهرباء
                'electricity_hour_number' => $row[2], // رقم ساعة الكهرباء
                'meter_type' => $row[3], // نوع العداد
                'operating_entity' => $row[4], // الجهة المشغلة
                'notes' => $row[5] ?? null, // ملاحظات
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.electricity_hour_number' => 'required|string', // التحقق من وجود رقم ساعة الكهرباء
        '*.meter_type' => 'required|string', // التحقق من نوع العداد
    ];
}

}
