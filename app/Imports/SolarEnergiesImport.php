<?php

namespace App\Imports;

use App\Models\SolarEnergy;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SolarEnergiesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد بيانات الطاقة الشمسية
        foreach ($rows as $row) {
            SolarEnergy::create([
                'station_id' => $row[0], // تأكد من أن station_id في العمود الصحيح
                'panel_size' => $row[1], // قياس اللوح
                'panel_count' => $row[2], // عدد الألواح
                'manufacturer' => $row[3], // الجهة المنشئة
                'base_type' => $row[4], // نوع القاعدة
                'technical_condition' => $row[5], // الحالة الفنية
                'wells_supplied_count' => $row[6], // عدد الآبار المغذاة
                'general_notes' => $row[7] ?? null, // ملاحظات
                'latitude' => $row[8] ?? null, // موقع الطاقة الشمسية (latitude)
                'longitude' => $row[9] ?? null, // موقع الطاقة الشمسية (longitude)
            ]);
        }
    }

    /**
     * قواعد التحقق من البيانات
     */
    public function rules(): array
    {
        return [
            '*.station_id' => 'required|exists:stations,id', // التأكد من أن station_id موجود في جدول stations
            '*.panel_size' => 'required|numeric', // التأكد من قياس اللوح هو عدد صحيح
            '*.panel_count' => 'required|integer', // التأكد من أن عدد الألواح هو عدد صحيح
            '*.manufacturer' => 'required|string', // التأكد من أن الجهة المنشئة هي نص
            '*.base_type' => 'required|string', // التأكد من أن نوع القاعدة هو نص
            '*.technical_condition' => 'required|string', // التأكد من أن الحالة الفنية هي نص
            '*.wells_supplied_count' => 'required|integer', // التأكد من أن عدد الآبار هو عدد صحيح
            '*.latitude' => 'nullable|numeric', // التأكد من أن latitude هو عدد صحيح أو عشري
            '*.longitude' => 'nullable|numeric', // التأكد من أن longitude هو عدد صحيح أو عشري
        ];
    }
}
