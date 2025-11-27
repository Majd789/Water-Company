<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ProjectSummarySheet implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        // =========================================================================
        // 1. بناء الاستعلام الأساسي (Base Query)
        // =========================================================================
        $query = Project::query();

        // تطبيق الفلاتر القادمة من الكونترولر
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

        // =========================================================================
        // 2. تجميع الإحصائيات العامة (Global Statistics) - لكل السنوات
        // =========================================================================

        $globalStats = [
            'overview' => [
                'total_count' => (clone $query)->count(),
                'total_value' => (clone $query)->sum('total_value'),
                'avg_value'   => (clone $query)->avg('total_value'),
                'max_value'   => (clone $query)->max('total_value'),
                'avg_duration'=> (clone $query)->avg('total_duration_days'),
            ],
            // توزيع حسب نوع المشروع
            'by_type' => (clone $query)
                ->select('project_type_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->with('projectType')
                ->groupBy('project_type_id')
                ->orderByDesc('total_value')
                ->get(),
            // توزيع حسب الحالة الرئيسية (موافقة، رفض، إلخ)
            'by_main_status' => (clone $query)
                ->select('main_status_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->with('mainStatus')
                ->groupBy('main_status_id')
                ->get(),
            // توزيع حسب الحالة العامة (منفذ، قيد التنفيذ...)
            'by_general_status' => (clone $query)
                ->select('general_status_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->with('generalStatus')
                ->groupBy('general_status_id')
                ->get(),
             // توزيع حسب حالة التسليم
            'by_handover_status' => (clone $query)
                ->select('handover_status_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->with('handoverStatus')
                ->groupBy('handover_status_id')
                ->get(),
            // أهم المنظمات (الأكثر تمويلاً)
            'top_organizations' => (clone $query)
                ->select('organization_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->with('organization')
                ->groupBy('organization_id')
                ->orderByDesc('total_value')
                ->limit(20) // جلب أهم 20
                ->get(),
            // أهم المانحين
            'top_donors' => (clone $query)
                ->select('donor_name', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->whereNotNull('donor_name')
                ->groupBy('donor_name')
                ->orderByDesc('total_value')
                ->limit(20)
                ->get(),
             // أكثر المشرفين نشاطاً
            'top_supervisors' => (clone $query)
                ->select('supervisor_name', DB::raw('count(*) as count, sum(total_value) as total_value'))
                ->whereNotNull('supervisor_name')
                ->groupBy('supervisor_name')
                ->orderByDesc('count')
                ->limit(15)
                ->get(),
        ];

        // =========================================================================
        // 3. تحليل السنوات (Years Breakdown)
        // =========================================================================

        // جلب قائمة السنوات المتوفرة في قاعدة البيانات (من الأحدث للأقدم)
        $years = (clone $query)
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $yearlyStatistics = [];

        foreach ($years as $year) {
            // إنشاء استعلام خاص لهذه السنة فقط
            $yearQuery = (clone $query)->whereYear('start_date', $year);

            $yearlyStatistics[$year] = [
                // نظرة عامة للسنة
                'overview' => [
                    'count' => (clone $yearQuery)->count(),
                    'value' => (clone $yearQuery)->sum('total_value'),
                    'avg_duration' => (clone $yearQuery)->avg('total_duration_days'),
                ],

                // التوزيع الشهري خلال السنة (يناير، فبراير...)
                'monthly_trend' => (clone $yearQuery)
                    ->selectRaw('MONTH(start_date) as month, count(*) as count, sum(total_value) as total_value')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function($item) {
                        // تحويل رقم الشهر لاسم
                        $item->month_name = Carbon::create()->month($item->month)->locale('ar')->monthName;
                        return $item;
                    }),

                // المنظمات في هذه السنة
                'organizations' => (clone $yearQuery)
                    ->select('organization_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                    ->with('organization')
                    ->groupBy('organization_id')
                    ->orderByDesc('total_value')
                    ->get(),

                // المانحين في هذه السنة
                'donors' => (clone $yearQuery)
                    ->select('donor_name', DB::raw('count(*) as count, sum(total_value) as total_value'))
                    ->whereNotNull('donor_name')
                    ->groupBy('donor_name')
                    ->orderByDesc('total_value')
                    ->get(),

                // توزيع الأنواع في هذه السنة
                'types' => (clone $yearQuery)
                    ->select('project_type_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                    ->with('projectType')
                    ->groupBy('project_type_id')
                    ->orderByDesc('count')
                    ->get(),

                // الحالة العامة لهذه السنة (كم مشروع انتهى، كم مشروع مستمر)
                'general_status' => (clone $yearQuery)
                    ->select('general_status_id', DB::raw('count(*) as count, sum(total_value) as total_value'))
                    ->with('generalStatus')
                    ->groupBy('general_status_id')
                    ->get(),
            ];
        }

        // =========================================================================
        // 4. إرجاع البيانات للـ View
        // =========================================================================
        return view('dashboard.projects.exports.summary', [
            'globalStats' => $globalStats,
            'yearlyStatistics' => $yearlyStatistics,
        ]);
    }

    /**
     * عنوان ورقة العمل في ملف الإكسل
     */
    public function title(): string
    {
        return 'لوحة القيادة والتحليل';
    }

    /**
     * إعدادات التنسيق (اتجاه الصفحة)
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        return [];
    }
}
