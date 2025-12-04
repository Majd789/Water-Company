<?php

namespace App\Imports;

use App\Models\ProjectContractor;
use App\Models\Project;
use App\Models\Contractor;
use App\Models\ContractorStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectContractorsImport implements ToModel, WithStartRow
{
    private $projectsCache = [];
    private $contractorsCache = [];

    public function startRow(): int
    {
        return 2; // تخطي صف العناوين
    }

    public function model(array $row)
    {
        // 1. البيانات الأساسية (مفاتيح الربط)
        $contractCode = trim($row[0] ?? ''); // العمود 0: كود العقد (المفتاح الفريد)
        $projectCode  = trim($row[5] ?? ''); // العمود 5: كود المشروع

        // تجاهل الأسطر الفارغة
        if (empty($contractCode) || empty($projectCode)) {
            return null;
        }

        // جلب معرف المشروع
        $projectId = $this->getProjectId($projectCode);
        if (!$projectId) return null; // لا يمكن الاستيراد بدون مشروع صحيح

        // 2. معالجة المقاول
        $contractorName = trim($row[7] ?? '');
        $contractorId = $this->getContractorOrCreate($contractorName);

        // 3. معالجة القيمة والعملة
        $rawValue = $row[10] ?? '';

        // اكتشاف العملة
        $currency = 'USD'; // الافتراضي
        if (Str::contains($rawValue, ['€', 'EUR', 'Euro'])) {
            $currency = 'EUR';
        } elseif (Str::contains($rawValue, ['TRY', 'TL', 'ليرة'])) {
            $currency = 'TRY';
        }

        // تنظيف الرقم
        $cleanValue = str_replace(['$', '€', 'EUR', 'USD', ',', ' ', '£'], '', $rawValue);
        $value = is_numeric($cleanValue) ? floatval($cleanValue) : 0;

        // 4. معالجة التواريخ
        $contractDate = $this->parseDate($row[9] ?? null);
        $startDate    = $this->parseDate($row[13] ?? null);
        $endDate      = $this->parseDate($row[14] ?? null);
        $approvalDate = $this->parseDate($row[17] ?? null);
        $actualStart  = $this->parseDate($row[18] ?? null);
        $actualEnd    = $this->parseDate($row[19] ?? null);

        // 5. الحالة
        $contractStatusTxt = trim($row[15] ?? ''); // حالة العقد (موافقة)
        $statusName = trim($row[20] ?? '');        // حالة التنفيذ
        $statusId = $this->getStatusId($statusName);

        // الموافقة
        $approvalNum = trim($row[16] ?? '');

        // 6. الحفظ أو التعديل (updateOrCreate)
        // المصفوفة الأولى: شروط البحث (إذا وجدها يعدل)
        // المصفوفة الثانية: القيم التي سيتم حفظها أو تعديلها
        return ProjectContractor::updateOrCreate(
            ['contract_code' => $contractCode], // البحث عن طريق كود العقد
            [
                'project_id'            => $projectId,
                'contractor_id'         => $contractorId,
                'contract_date'         => $contractDate,
                'value'                 => $value,
                'currency'              => $currency,
                'execution_phases'      => intval($row[11] ?? 1),
                'duration_days'         => intval($row[12] ?? 0),
                'start_date'            => $startDate,
                'end_date'              => $endDate,
                'contract_status'       => $contractStatusTxt, // سيتم تحديث حالة العقد
                'org_approval_number'   => $approvalNum,       // سيتم تحديث رقم الموافقة
                'org_approval_date'     => $approvalDate,
                'actual_start_date'     => $actualStart,       // سيتم تحديث التواريخ الفعلية
                'actual_end_date'       => $actualEnd,
                'contractor_status_id'  => $statusId,          // سيتم تحديث حالة التنفيذ
            ]
        );
    }

    // --- الدوال المساعدة ---

    private function getProjectId($code)
    {
        if (isset($this->projectsCache[$code])) return $this->projectsCache[$code];
        $project = Project::where('project_code', $code)->first();
        if ($project) {
            $this->projectsCache[$code] = $project->id;
            return $project->id;
        }
        return null;
    }

    private function getContractorOrCreate($excelName)
    {
        if (empty($excelName)) return null;

        if (isset($this->contractorsCache[$excelName])) return $this->contractorsCache[$excelName];

        $cleanName = $excelName;
        foreach (['ممثلة', 'عنها', 'بإدارة', 'وكيلا', 'بـ'] as $keyword) {
            if (Str::contains($cleanName, $keyword)) {
                $cleanName = Str::before($cleanName, $keyword);
            }
        }
        $cleanName = trim($cleanName);

        $contractor = Contractor::where('name', $excelName)->first();

        if (!$contractor) {
            $contractor = Contractor::where('name', 'LIKE', "%{$cleanName}%")->first();
        }

        if ($contractor) {
            $this->contractorsCache[$excelName] = $contractor->id;
            return $contractor->id;
        }

        // إنشاء جديد إذا لم يوجد
        try {
            $codePrefix = Str::upper(Str::substr(Str::slug($cleanName), 0, 3));
            if (empty($codePrefix)) $codePrefix = 'CON';
            $newCode = $codePrefix . '-' . rand(1000, 9999);

            $newContractor = Contractor::create([
                'name' => $cleanName,
                'code' => $newCode,
            ]);

            $this->contractorsCache[$excelName] = $newContractor->id;
            return $newContractor->id;

        } catch (\Exception $e) {
            return null;
        }
    }

    private function getStatusId($statusName)
    {
        if (empty($statusName)) return 2;
        $status = ContractorStatus::where('name', 'LIKE', "%$statusName%")->first();
        return $status ? $status->id : 2;
    }

    private function parseDate($value)
    {
        if (empty($value) || $value == '_' || $value == '-') return null;

        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
