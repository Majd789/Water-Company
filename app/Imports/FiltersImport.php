<?php
namespace App\Imports;

use App\Models\Filter;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class FiltersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد المرشحات
        foreach ($rows as $row) {
            Filter::create([
                'station_id' => $row[0], // كود المحطة
                'filter_capacity' => $row[1], // استطاعة المرشح
                'readiness_status' => $row[2], // جاهزية المرشح
                'filter_type' => $row[3], // نوع المرشح
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.filter_capacity' => 'required|numeric', // التحقق من استطاعة المرشح
        '*.readiness_status' => 'required|numeric|min:0|max:100', // التحقق من جاهزية المرشح (نسبة مئوية)
        '*.filter_type' => 'required|string', // التحقق من نوع المرشح
    ];
}

}
