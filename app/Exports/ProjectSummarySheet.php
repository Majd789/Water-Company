<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectSummarySheet implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        // 1. بناء الاستعلام الأساسي مع الفلاتر
        $query = Project::query();

        // تصفية حسب الوحدة (User Unit)
        if (!empty($this->filters['unit_id'])) {
            $query->whereHas('activities', function ($q) {
                $q->where('unit_id', $this->filters['unit_id']);
            });
        }

        // تصفية حسب المنظمة
        if (!empty($this->filters['organization_id'])) {
            $query->where('organization_id', $this->filters['organization_id']);
        }

        // تصفية البحث العام
        if (!empty($this->filters['search'])) {
            $searchTerm = trim($this->filters['search']);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('project_code', 'like', "%{$searchTerm}%");
            });
        }

        // 2. جلب الإحصائيات (Counts)

        // إحصائية حسب نوع المشروع
        $byType = (clone $query)
            ->select('project_type_id')
            ->selectRaw('count(*) as count')
            ->with('projectType')
            ->groupBy('project_type_id')
            ->get();

        // إحصائية حسب الحالة الرئيسية
        $byMainStatus = (clone $query)
            ->select('main_status_id')
            ->selectRaw('count(*) as count')
            ->with('mainStatus')
            ->groupBy('main_status_id')
            ->get();

        // إحصائية حسب حالة التسليم
        $byHandoverStatus = (clone $query)
            ->select('handover_status_id')
            ->selectRaw('count(*) as count')
            ->with('handoverStatus')
            ->groupBy('handover_status_id')
            ->get();

        // الإجماليات
        $totalCount = (clone $query)->count();
        $totalValue = (clone $query)->sum('total_value');

        // إرسال البيانات إلى ملف العرض (Blade)
        return view('dashboard.projects.exports.summary', [
            'byType' => $byType,
            'byMainStatus' => $byMainStatus,
            'byHandoverStatus' => $byHandoverStatus,
            'totalCount' => $totalCount,
            'totalValue' => $totalValue,
        ]);
    }

    public function title(): string
    {
        return 'ملخص الإحصائيات';
    }

    public function styles(Worksheet $sheet)
    {
        // جعل اتجاه الورقة من اليمين لليسار
        $sheet->setRightToLeft(true);
        return [];
    }
}
