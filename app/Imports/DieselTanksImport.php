<?php


namespace App\Imports;

use App\Models\DieselTank;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DieselTanksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد بيانات خزانات الديزل
        foreach ($rows as $row) {
            DieselTank::create([
                'station_id' => $row[0], // تأكد من أن station_id في العمود الصحيح
                'tank_name' => $row[1], // اسم الخزان
                'tank_capacity' => $row[2], // سعة الخزان
                'readiness_percentage' => $row[3], // نسبة الجاهزية
                'type' => $row[4], // نوع الخزان
                'general_notes' => $row[5] ?? null, // ملاحظات
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
            '*.tank_name' => 'required|string', // التأكد من أن اسم الخزان هو نص
            '*.tank_capacity' => 'required|numeric', // التأكد من أن سعة الخزان هي عدد
            '*.readiness_percentage' => 'required|numeric|between:0,100', // التأكد من أن نسبة الجاهزية بين 0 و 100
            '*.type' => 'required|string', // التأكد من أن نوع الخزان هو نص
        ];
    }
}
