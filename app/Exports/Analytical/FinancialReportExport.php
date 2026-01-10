<?php

namespace App\Exports\Analytical;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters) {
        $this->filters = $filters;
    }

    public function collection()
    {
        // جلب المنظمات مع مشاريعها لحساب المجموع
        $query = Organization::with(['projects' => function($q) {
            if (!empty($this->filters['start_date'])) {
                $q->whereDate('start_date', '>=', $this->filters['start_date']);
            }
            if (!empty($this->filters['end_date'])) {
                $q->whereDate('start_date', '<=', $this->filters['end_date']);
            }
        }]);

        return $query->get()->map(function ($org) {
            // نأخذ فقط المشاريع التي تمت تصفيتها
            $projects = $org->projects;

            $projectCount = $projects->count();
            $totalBudget = $projects->sum('total_value');

            // حساب المتوسط
            $avgBudget = $projectCount > 0 ? $totalBudget / $projectCount : 0;

            return [
                'name' => $org->name,
                'code' => $org->code,
                'projects_count' => $projectCount,
                'total_budget' => $totalBudget, // سيتم تنسيقها في Excel لاحقاً أو هنا
                'avg_project_cost' => $avgBudget,
                'highest_project' => $projects->max('total_value') ?? 0,
                'status' => $projectCount > 0 ? 'نشطة' : 'لا يوجد مشاريع في الفترة',
            ];
        })->sortByDesc('total_budget');
    }

    public function headings(): array
    {
        return [
            'اسم المنظمة',
            'الكود',
            'عدد المشاريع',
            'إجمالي التمويل ($)',
            'متوسط تكلفة المشروع',
            'أعلى مشروع',
            'الحالة'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // تنسيق الأعمدة المالية (D, E, F)
        $sheet->getStyle('D:F')->getNumberFormat()->setFormatCode('#,##0.00');

        return [];
    }
}
