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
        // 1. البيانات الأساسية
        $contractCode = trim($row[0] ?? ''); // العمود 0: كود العقد
        $projectCode  = trim($row[5] ?? ''); // العمود 5: كود المشروع

        if (empty($contractCode) || empty($projectCode)) {
            return null;
        }

        $projectId = $this->getProjectId($projectCode);
        if (!$projectId) return null;

        // 2. معالجة المقاول (العمود 7)
        $contractorName = trim($row[7] ?? '');
        $contractorId = $this->getContractorId($contractorName);

        // 3. معالجة القيمة والعملة (العمود 10)
        $rawValue = $row[10] ?? '';

        // اكتشاف العملة من النص (مثلاً: "98,835.00 €")
        $currency = 'USD'; // الافتراضي
        if (Str::contains($rawValue, ['€', 'EUR', 'Euro', 'euro'])) {
            $currency = 'EUR';
        } elseif (Str::contains($rawValue, ['TRY', 'TL'])) {
            $currency = 'TRY';
        }

        // تنظيف الرقم من الرموز والفواصل
        $cleanValue = str_replace(['$', '€', 'EUR', 'USD', ',', ' '], '', $rawValue);
        $value = is_numeric($cleanValue) ? floatval($cleanValue) : 0;

        // 4. معالجة التواريخ (الأعمدة القياسية)
        $contractDate = $this->parseDate($row[9] ?? null);  // تاريخ العقد
        $startDate    = $this->parseDate($row[13] ?? null); // تاريخ البداية المخطط
        $endDate      = $this->parseDate($row[14] ?? null); // تاريخ النهاية المخطط
        $approvalDate = $this->parseDate($row[17] ?? null); // تاريخ الموافقة
        $actualStart  = $this->parseDate($row[18] ?? null); // البدء الفعلي
        $actualEnd    = $this->parseDate($row[19] ?? null); // الانتهاء الفعلي

        // 5. الحالة
        $statusName = trim($row[20] ?? '');
        $statusId = $this->getStatusId($statusName);

        // 6. الحفظ في قاعدة البيانات
        return ProjectContractor::updateOrCreate(
            ['contract_code' => $contractCode],
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
                'contract_status'       => $row[15] ?? null, // حالة العقد (موافقة)
                'org_approval_number'   => $row[16] ?? null,
                'org_approval_date'     => $approvalDate,
                'actual_start_date'     => $actualStart,
                'actual_end_date'       => $actualEnd,
                'contractor_status_id'  => $statusId,
            ]
        );
    }

    // --- دوال مساعدة ---

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

    private function getContractorId($excelName)
    {
        if (empty($excelName)) return null;

        if (isset($this->contractorsCache[$excelName])) return $this->contractorsCache[$excelName];

        $contractor = Contractor::where('name', $excelName)->first();
        if ($contractor) {
            $this->contractorsCache[$excelName] = $contractor->id;
            return $contractor->id;
        }

        // بحث ذكي (تجاهل: ممثلة بـ / عنها ...)
        $searchName = $excelName;
        foreach (['ممثلة', 'عنها', 'بإدارة', 'وكيلا'] as $keyword) {
            if (Str::contains($searchName, $keyword)) {
                $searchName = Str::before($searchName, $keyword);
            }
        }
        $searchName = trim($searchName);

        $contractor = Contractor::where('name', 'LIKE', "%{$searchName}%")->first();
        if ($contractor) {
            $this->contractorsCache[$excelName] = $contractor->id;
            return $contractor->id;
        }

        return null;
    }

    private function getStatusId($statusName)
    {
        // إذا فارغة نعتبرها "قيد التنفيذ" (ID: 2)
        if (empty($statusName)) return 2;

        $status = ContractorStatus::where('name', 'LIKE', "%$statusName%")->first();
        return $status ? $status->id : 2;
    }

    private function parseDate($value)
    {
        if (empty($value) || $value == '_' || $value == '-') return null;

        try {
            // معالجة رقم Excel التسلسلي (Serial)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // معالجة النصوص
            return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
