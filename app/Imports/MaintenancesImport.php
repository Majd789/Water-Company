<?php

namespace App\Imports;

use App\Models\Maintenance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class MaintenancesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة العناوين من أول صف
        $rows->shift();

        foreach ($rows as $row) {
            Maintenance::create([
                'station_id' => $row[0], // تأكد أن هذا يطابق ID المحطة
                'maintenance_type_id' => $row[1], // معرف نوع الصيانة
                'total_quantity' => $row[2], // العدد الإجمالي للقطع
                'execution_sites' => $row[3], // مواقع التنفيذ
                'total_cost' => $row[4], // الكلفة الإجمالية
                'maintenance_date' => $this->convertDate($row[5] ?? null), // تحويل التاريخ
                'maintenance_details' => $row[6] ?? null, // تفاصيل الصيانة
                'contractor_name' => $row[7] ?? null, // اسم المقاول
                'technician_name' => $row[8] ?? null, // اسم الفني
                'status' => $row[9] ?? 'غير محدد', // حالة الصيانة
            ]);
        }
    }

    private function convertDate($date)
    {
        if (!$date) {
            return null; // إذا كان التاريخ غير موجود، لا تقم بتخزين أي شيء
        }

        try {
            // التحقق مما إذا كان التاريخ رقمًا تسلسليًا من Excel
            if (is_numeric($date) && $date > 30000) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
            }

            // التحقق مما إذا كان النص بتنسيق DD/MM/YYYY
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // إذا فشل التحويل، لا تقم بتخزين قيمة غير صحيحة
        }
    }
}
