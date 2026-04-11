<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EmployeesImport implements ToCollection
{
    public $errors = []; // مصفوفة لتخزين الأخطاء

   public function collection(Collection $rows)
{
    // فحص إذا كان الملف فارغاً تماماً
    if ($rows->isEmpty()) {
        $this->errors[] = "الملف المرفق لا يحتوي على أي بيانات.";
        return;
    }

    $rows->shift();

    foreach ($rows as $index => $row) {
        // تجاهل الصفوف الفارغة تماماً
        if (!isset($row[0]) || empty($row[0])) continue;

        try {
            // تحويل القيم لضمان عدم وجود أخطاء في النوع (Type Hinting)
            $fullName = trim((string)$row[0]);
            $code = trim((string)$row[1]);
            $unitId = (int)$row[2];
            $days = isset($row[3]) ? (int)$row[3] : 30;

            $unitExists = Unit::find($unitId);
            if (!$unitExists) {
                $this->errors[] = "السطر " . ($index + 2) . ": رقم الوحدة ({$unitId}) غير موجود.";
                continue;
            }

            Employee::updateOrCreate(
                ['employee_code' => $code],
                [
                    'full_name'          => $fullName,
                    'unit_id'            => $unitId,
                    'total_allowed_days' => $days,
                    'remaining_days'     => $days,
                    'is_active'          => true,
                ]
            );
        } catch (\Exception $e) {
            $this->errors[] = "السطر " . ($index + 2) . ": خطأ في البيانات (" . $e->getMessage() . ")";
            Log::error($e->getMessage());
        }
    }
}
}
