<?php
namespace App\Imports;

use App\Models\PrivateWell;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class PrivateWellsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الآبار الخاصة
        foreach ($rows as $row) {
            PrivateWell::create([
                'station_id' => $row[0], // كود المحطة
                'well_name' => $row[1], // اسم البئر
                'well_count' => $row[2], // عدد الآبار
                'distance_from_nearest_well' => $row[3], // بعده عن أقرب بئر
                'well_type' => $row[4], // نوع عمل البئر
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.well_name' => 'required|string', // التحقق من وجود اسم البئر
        '*.well_count' => 'required|integer', // التحقق من عدد الآبار
        '*.well_type' => 'required|string', // التحقق من نوع عمل البئر
    ];
}

}
