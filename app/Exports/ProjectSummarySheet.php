<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB; // نحتاج DB للعمليات الحسابية المعقدة
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
        // 1. الاستعلام الأساسي مع الفلاتر
        $query = Project::query();

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

        // --- الإجماليات العامة ---
        $totalCount = (clone $query)->count();
        $totalValue = (clone $query)->sum('total_value');
        $avgDuration = (clone $query)->avg('total_duration_days'); // متوسط مدة المشاريع

        // --- 1. إحصائيات المنظمات (العدد + القيمة المالية) ---
        $byOrg = (clone $query)
            ->select('organization_id')
            ->selectRaw('count(*) as count, sum(total_value) as total_value')
            ->with('organization')
            ->groupBy('organization_id')
            ->orderByDesc('total_value') // ترتيب حسب القيمة المالية الأعلى
            ->get();

        // --- 2. إحصائيات الجهات المانحة (العدد + القيمة المالية) ---
        $byDonor = (clone $query)
            ->select('donor_name')
            ->whereNotNull('donor_name')
            ->where('donor_name', '!=', '')
            ->selectRaw('count(*) as count, sum(total_value) as total_value')
            ->groupBy('donor_name')
            ->orderByDesc('count') // ترتيب حسب الأكثر تمويلاً
            ->get();

        // --- 3. إحصائيات نوع المشروع (مع القيمة المالية) ---
        $byType = (clone $query)
            ->select('project_type_id')
            ->selectRaw('count(*) as count, sum(total_value) as total_value')
            ->with('projectType')
            ->groupBy('project_type_id')
            ->get();

        // --- 4. إحصائيات الحالة العامة (General Status) ---
        $byGenStatus = (clone $query)
            ->select('general_status_id')
            ->selectRaw('count(*) as count, sum(total_value) as total_value')
            ->with('generalStatus')
            ->groupBy('general_status_id')
            ->get();

        // --- 5. إحصائيات حالة التسليم ---
        $byHandoverStatus = (clone $query)
            ->select('handover_status_id')
            ->selectRaw('count(*) as count, sum(total_value) as total_value')
            ->with('handoverStatus')
            ->groupBy('handover_status_id')
            ->get();

        // --- 6. إحصائيات المشرفين (أكثر المشرفين نشاطاً) ---
        $bySupervisor = (clone $query)
            ->select('supervisor_name')
            ->whereNotNull('supervisor_name')
            ->selectRaw('count(*) as count, sum(total_value) as total_managed_value')
            ->groupBy('supervisor_name')
            ->orderByDesc('count')
            ->limit(15) // أهم 15 مشرف
            ->get();

        // --- 7. التوزيع الزمني (حسب سنة البدء) ---
        $byYear = (clone $query)
            ->selectRaw('YEAR(start_date) as year, count(*) as count')
            ->whereNotNull('start_date')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        return view('dashboard.projects.exports.summary', [
            'totalCount' => $totalCount,
            'totalValue' => $totalValue,
            'avgDuration' => $avgDuration,
            'byOrg' => $byOrg,
            'byDonor' => $byDonor,
            'byType' => $byType,
            'byGenStatus' => $byGenStatus,
            'byHandoverStatus' => $byHandoverStatus,
            'bySupervisor' => $bySupervisor,
            'byYear' => $byYear,
        ]);
    }

    public function title(): string
    {
        return 'الإحصائيات الشاملة';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        // تنسيق الأرقام لعمود المبالغ (افتراضياً سنجعل الأعمدة C و D بتنسيق العملة في أماكن تواجدها)
        // هذا مجرد تنسيق عام، التنسيق الدقيق يتم عبر ترتيب الجداول
        return [];
    }
}
