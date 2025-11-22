<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Organization;
use App\Models\ProjectType;
use App\Models\ProjectMainStatus;
use App\Models\ProjectGeneralStatus;
use App\Models\HandoverStatus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class ProjectsImport implements ToCollection
{
    private $organizations;
    private $projectTypes;
    private $mainStatuses;
    private $generalStatuses;
    private $handoverStatuses;

    public function __construct()
    {
        // تحميل البيانات المساعدة لربط الأسماء بالـ ID
        $this->organizations = Organization::all()->pluck('id', 'name')->toArray();
        $this->projectTypes = ProjectType::all()->pluck('id', 'name')->toArray();
        $this->mainStatuses = ProjectMainStatus::all()->pluck('id', 'name')->toArray();
        $this->generalStatuses = ProjectGeneralStatus::all()->pluck('id', 'name')->toArray();
        $this->handoverStatuses = HandoverStatus::all()->pluck('id', 'name')->toArray();
    }

    public function collection(Collection $rows)
    {
        // إزالة الصف الأول (العناوين)
        $rows->shift();

        foreach ($rows as $row)
        {
            // تخطي الصفوف الفارغة تماماً
            if ($row->filter()->isEmpty()) {
                continue;
            }

            // التحقق من أن كود المشروع موجود
            if (empty($row[0])) {
                continue;
            }

            // البحث عن الـ ID المطابق للأسماء
            $organization_id = $this->organizations[trim($row[4])] ?? null;
            $project_type_id = $this->projectTypes[trim($row[2])] ?? null;
            $main_status_id = $this->mainStatuses[trim($row[3])] ?? null;
            $general_status_id = $this->generalStatuses[trim($row[19])] ?? null;
            $handover_status_id = $this->handoverStatuses[trim($row[18])] ?? null;

            Project::create([
                'project_code'         => $row[0],
                'name'                 => $row[1],
                'project_type_id'      => $project_type_id,
                'main_status_id'       => $main_status_id,
                'organization_id'      => $organization_id,
                'donor_name'           => $row[5],
                'supervisor_name'      => $row[6],
                'supervisor_phone'     => $row[7],
                'total_value'          => $this->cleanNumeric($row[8]),
                'contract_date'        => $this->transformDate($row[9]),
                'total_duration_days'  => $this->cleanNumeric($row[10]),
                'start_date'           => $this->transformDate($row[11]),
                'end_date'             => $this->transformDate($row[12]),
                'hac_issue_number'     => $row[13],
                'hac_issue_date'       => $this->transformDate($row[14]),
                'hac_received_date'    => $this->transformDate($row[15]),
                'approval_number'      => $row[16],
                'approval_date'        => $this->transformDate($row[17]),
                'handover_status_id'   => $handover_status_id,
                'general_status_id'    => $general_status_id,
                'notes'                => $row[20],
            ]);
        }
    }

    private function cleanNumeric($value)
    {
        if (empty($value) || $value === 'لا يوجد') return null;
        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return is_numeric($cleaned) ? $cleaned : null;
    }

    private function transformDate($value)
    {
        if (empty($value) || $value === 'لا يوجد' || $value === 'لايوجد') {
            return null;
        }
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        } catch (\ErrorException $e) {
            try {
                 return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $ex) {
                return null;
            }
        }
    }
}
