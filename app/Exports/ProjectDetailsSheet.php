<?php

namespace App\Exports;

use App\Models\Project;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectDetailsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        // 1. الاستعلام مع تحميل العلاقات
        $query = Project::with([
            'organization',
            'projectType',
            'mainStatus',
            'generalStatus',
            'handoverStatus'
        ]);

        // 2. تطبيق الفلاتر (نفس المنطق السابق)
        if (!empty($this->filters['unit_id'])) {
            $query->whereHas('activities', function ($q) {
                $q->where('unit_id', $this->filters['unit_id']);
            });
        }
        if (!empty($this->filters['organization_id'])) {
            $query->where('organization_id', $this->filters['organization_id']);
        }
        if (!empty($this->filters['search'])) {
            $searchTerm = trim($this->filters['search']);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('project_code', 'like', "%{$searchTerm}%");
            });
        }

        // 3. جلب البيانات وتشكيل الصفوف
        return $query->get()->map(function ($project) {

            // حساب المدة الزمنية
            $duration = $project->total_duration_days;
            if (empty($duration) && $project->start_date && $project->end_date) {
                try {
                    $start = Carbon::parse($project->start_date);
                    $end = Carbon::parse($project->end_date);
                    $duration = $end->diffInDays($start);
                } catch (\Exception $e) {
                    $duration = 0;
                }
            }

            // إرجاع الصف بترتيب الأعمدة المطلوب
            return [
                'ID' => $project->id,
                'Code' => $project->project_code,
                'Name' => $project->name,
                'Org' => $project->organization->name ?? '',
                'Donor' => $project->donor_name,
                'Type' => $project->projectType->name ?? '',
                'Supervisor' => $project->supervisor_name,
                'Phone' => $project->supervisor_phone,
                'MainStatus' => $project->mainStatus->name ?? '',
                'GenStatus' => $project->generalStatus->name ?? '',
                'Handover' => $project->handoverStatus->name ?? '',
                'Value' => $project->total_value, // سيتم تنسيقه كرقم لاحقاً
                'Currency' => $project->currency,
                'ContractDate' => $project->contract_date,
                'StartDate' => $project->start_date,
                'EndDate' => $project->end_date,
                'Duration' => $duration,
                'HAC_Num' => $project->hac_issue_number,
                'Approval_Num' => $project->approval_number,
                'Notes' => $project->notes,
                'CreatedAt' => $project->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'كود المشروع',
            'اسم المشروع',
            'المنظمة',
            'الجهة المانحة',
            'نوع المشروع',
            'اسم المشرف',
            'هاتف المشرف',
            'الحالة الرئيسية',
            'الحالة العامة',
            'حالة التسليم',
            'القيمة الإجمالية',
            'العملة',
            'تاريخ العقد',
            'تاريخ البداية',
            'تاريخ النهاية',
            'المدة (أيام)',
            'رقم HAC',
            'رقم الموافقة',
            'ملاحظات',
            'تاريخ الإدخال'
        ];
    }

    public function title(): string
    {
        return 'قائمة المشاريع التفصيلية';
    }

    public function styles(Worksheet $sheet)
    {
        // اتجاه اليمين لليسار
        $sheet->setRightToLeft(true);

        // تنسيق صف العناوين (Bold + لون خلفية)
        $sheet->getStyle('A1:U1')->getFont()->setBold(true);
        $sheet->getStyle('A1:U1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFEEEEEE');

        // تنسيق عمود القيمة المالية (العمود L هو رقم 12) لإظهار الفواصل
        // L هو الحرف المقابل للقيمة الإجمالية في المصفوفة أعلاه
        $sheet->getStyle('L2:L' . ($sheet->getHighestRow()))
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        return [];
    }
}
