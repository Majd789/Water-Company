<?php

namespace App\Exports\Analytical;

use App\Models\Town;
// سنستخدم Town ونقوم بتجميعها حسب الوحدة إذا لم يكن هناك موديل Unit مباشر،
// أو استخدم App\Models\Unit إذا كان موجوداً. هنا سأكتب الكود ليعمل بذكاء.
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeographicalReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filters;
    public function __construct($filters) { $this->filters = $filters; }

    public function collection()
    {
        // سنجلب القرى مع وحداتها وأنشطتها
        $towns = Town::with(['unit', 'projectActivities'])->get();

        // تجميع القرى حسب الوحدة الإدارية
        $grouped = $towns->groupBy(function($town) {
            return $town->unit->unit_name ?? 'غير محدد';
        });

        $data = [];

        foreach ($grouped as $unitName => $townsInUnit) {
            $allActivities = $townsInUnit->pluck('projectActivities')->flatten();

            // تطبيق فلتر التاريخ
            if (!empty($this->filters['start_date'])) {
                $allActivities = $allActivities->where('created_at', '>=', $this->filters['start_date']);
            }
            if (!empty($this->filters['end_date'])) {
                $allActivities = $allActivities->where('created_at', '<=', $this->filters['end_date']);
            }

            $data[] = [
                'unit_name' => $unitName,
                'towns_count' => $townsInUnit->count(),
                'activities_count' => $allActivities->count(),
                'total_cost' => $allActivities->sum('cost'),
                'executed' => $allActivities->where('status', 'منفذ')->count(),
                'pending' => $allActivities->where('status', '!=', 'منفذ')->count(),
            ];
        }

        return collect($data)->sortByDesc('activities_count');
    }

    public function headings(): array
    {
        return ['اسم الوحدة الإدارية', 'عدد القرى', 'إجمالي الأنشطة', 'إجمالي التكلفة ($)', 'منفذ', 'قيد التنفيذ/أخرى'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('#,##0.00');
        return [];
    }
}
