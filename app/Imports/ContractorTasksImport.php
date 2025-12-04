<?php

namespace App\Imports;

use App\Models\ContractorTask;
use App\Models\ProjectContractor;
use App\Models\ProjectActivity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class ContractorTasksImport implements ToModel, WithStartRow
{
    private $contractorsCache = [];
    private $activitiesCache = [];

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // 1. البيانات الأساسية
        $taskCode = trim($row[0] ?? '');       // كود المهمة
        $contractCode = trim($row[1] ?? '');   // كود العقد (قد يكون فارغاً)
        $activityCode = trim($row[2] ?? '');   // كود النشاط

        // التعديل هنا: لم نعد نشترط وجود contractCode
        if (empty($taskCode) || empty($activityCode)) {
            return null;
        }

        // 2. جلب معرف عقد المقاول (إذا وجد كود عقد)
        $projectContractorId = null;
        if (!empty($contractCode)) {
            $projectContractorId = $this->getProjectContractorId($contractCode);
            // ملاحظة: إذا كان هناك كود مقاول ولكنه غير موجود في السيستم، هل نتجاهل المهمة أم ندخلها بدون مقاول؟
            // هنا سندخلها بدون مقاول إذا لم يتم العثور على العقد
        }

        // 3. جلب معرف النشاط
        $projectActivityId = $this->getProjectActivityId($activityCode);
        if (!$projectActivityId) {
            return null; // لا يمكن إدخال مهمة بدون نشاط رئيسي
        }

        // 4. معالجة التكلفة
        $rawCost = $row[11] ?? 0;
        $cost = 0;
        if (is_numeric($rawCost)) {
            $cost = $rawCost;
        } elseif (is_string($rawCost)) {
            $cleanCost = str_replace(['$', ',', ' '], '', $rawCost);
            $cost = is_numeric($cleanCost) ? $cleanCost : 0;
        }

        // 5. معالجة الكمية
        $rawQty = $row[9] ?? 0;
        $countQty = $row[8] ?? 1;

        $quantity = 0;
        $extraDescription = '';

        if (is_numeric($rawQty)) {
            $quantity = $rawQty;
        } else {
            $quantity = is_numeric($countQty) ? $countQty : 0;
            $extraDescription = " - مواصفات الكمية: " . $rawQty;
        }

        // 6. الوصف
        $description = trim($row[7] ?? '');
        if (!empty($extraDescription)) {
            $description .= $extraDescription;
        }

        // 7. الملاحظات
        $notes = trim($row[12] ?? '');
        $isDiscrepant = !empty($notes);

        // 8. الحفظ
        return ContractorTask::updateOrCreate(
            ['task_code' => $taskCode],
            [
                'project_contractor_id' => $projectContractorId, // سيقبل null الآن
                'project_activity_id'   => $projectActivityId,
                'description'           => $description,
                'quantity'              => $quantity,
                'unit_measure'          => trim($row[10] ?? ''),
                'cost'                  => $cost,
                'notes'                 => $notes,
                'is_discrepant'         => $isDiscrepant,
                'discrepancy_notes'     => $isDiscrepant ? $notes : null,
            ]
        );
    }

    private function getProjectContractorId($code)
    {
        if (isset($this->contractorsCache[$code])) {
            return $this->contractorsCache[$code];
        }

        $contract = ProjectContractor::where('contract_code', $code)->first();
        if ($contract) {
            $this->contractorsCache[$code] = $contract->id;
            return $contract->id;
        }
        return null;
    }

    private function getProjectActivityId($code)
    {
        if (isset($this->activitiesCache[$code])) {
            return $this->activitiesCache[$code];
        }

        $activity = ProjectActivity::where('activity_code', $code)->first();
        if ($activity) {
            $this->activitiesCache[$code] = $activity->id;
            return $activity->id;
        }
        return null;
    }
}
