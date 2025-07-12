<?php

namespace App\Imports;

use App\Models\MaintenanceTask;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class MaintenanceTasksImport implements ToModel, WithValidation, WithStartRow, SkipsOnError
{
    use SkipsErrors;

    private $unitsCache = [];

    public function model(array $row)
    {
        // اسم الوحدة من ملف الإكسل (العمود الخامس، index 4)
        $unitNameFromExcel = trim($row[4]);

        // إذا كان الحقل فارغاً، تجاهل الصف
        if (empty($unitNameFromExcel)) {
            return null;
        }
        
        // ---- منطق البحث الذكي والمحسّن عن الوحدة ----
        $unit = null;
        if (isset($this->unitsCache[$unitNameFromExcel])) {
            $unit = $this->unitsCache[$unitNameFromExcel];
        } else {
            // 1. إزالة كلمة "وحدة " أو "وحدة مياه " من بداية النص للحصول على الاسم الأساسي
            $baseName = str_replace(['وحدة مياه ', 'وحدة '], '', $unitNameFromExcel);
            
            // 2. البحث باستخدام LIKE عن أي اسم وحدة ينتهي بالاسم الأساسي
            // مثال: سيبحث عن أي شيء LIKE '%حارم' أو LIKE '%الوسطى'
            $unit = Unit::where('unit_name', 'LIKE', '%' . $baseName)->first();

            if ($unit) {
                $this->unitsCache[$unitNameFromExcel] = $unit;
            }
        }
        
        // إذا لم يتم العثور على الوحدة بعد البحث الذكي، تجاهل الصف
        if (!$unit) {
            return null;
        }

        // --- باقي الكود كما هو ---
        return new MaintenanceTask([
            'technician_name'   => $row[2],
            'maintenance_date'  => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]),
            'unit_id'           => $unit->id,
            'location'          => $row[5],
            'fault_description' => $row[7],
            'fault_cause'       => $row[6],
            'maintenance_actions' => $row[8],
            'is_fixed'          => (trim($row[9]) === 'نعم'),
            'reason_not_fixed'  => $row[10],
            'notes'             => $row[11],
        ]);
    }

    public function rules(): array
    {
        return [
            '2' => 'required|string',
            '3' => 'required|numeric',
            '4' => 'required|string',
            '5' => 'required|string',
            '7' => 'required|string',
            '8' => 'required|string',
            '9' => 'required|in:نعم,لا',
        ];
    }
    
    public function startRow(): int
    {
        return 2;
    }
}