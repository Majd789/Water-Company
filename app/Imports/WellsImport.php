<?php

namespace App\Imports;

use App\Models\Well;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class WellsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الآبار
        foreach ($rows as $row) {
            Well::create([
                'station_id' => $row[0], // كود المحطة
                'town_code' => $row[1], // كود البلدة
                'well_name' => $row[2], // اسم البئر
                'well_status' => $row[3] ?? null, // الوضع التشغيلي للبئر
                'stop_reason' => $row[4] ?? null, // سبب التوقف
                'distance_from_station' => $row[5] ?? null, // بعده عن المحطة
                'well_type' => $row[6] ?? null, // نوع البئر (جوفي / سطحي)
                'well_flow' => $row[7] ?? null, // تدفق البئر (متر مكعب / ساعة)
                'static_depth' => $row[8] ?? null, // العمق الستاتيكي
                'dynamic_depth' => $row[9] ?? null, // العمق الديناميكي
                'drilling_depth' => $row[10] ?? null, // العمق الحفر
                'well_diameter' => $row[11] ?? null, // قطر البئر
                'pump_installation_depth' => $row[12] ?? null, // عمق تركيب المضخة
                'pump_capacity' => $row[13] ?? null, // استطاعة المضخة
                'actual_pump_flow' => $row[14] ?? null, // تدفق المضخة الفعلي
                'pump_lifting' => $row[15] ?? null, // رفع المضخة
                'pump_brand_model' => $row[16] ?? null, // ماركة وموديل المضخة
                'energy_source' => $row[17] ?? null, // مصدر الطاقة
                'well_address' => $row[18] ?? null, // عنوان البئر
                'general_notes' => $row[19] ?? null, // ملاحظات عامة
                'well_location' => $row[20] ?? null, // موقع البئر (latitude, longitude, altitude, precision)
            ]);
        }
    }
    public function rules(): array
{
    return [
        '*.station_id' => 'required|exists:stations,id', // التحقق من وجود `station_id` في جدول المحطات
        '*.town_code' => 'required|string',
        '*.well_name' => 'required|string',
        '*.well_status' => 'nullable|in:يعمل,متوقف', // الوضع التشغيلي للبئر
        '*.well_type' => 'nullable|in:جوفي,سطحي', // نوع البئر
    ];
}

}
