<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon; // ضروري للتعامل مع التواريخ وحساب المدة

class ProjectsExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        // تجهيز الاستعلام مع تحميل العلاقات لتحسين الأداء
        $query = Project::with([
            'organization',
            'projectType',
            'mainStatus',
            'generalStatus',
            'handoverStatus'
        ]);

        // تطبيق نفس فلتر الصلاحيات الموجود في العرض (index)
        // إذا كان المستخدم يتبع وحدة معينة، نعرض فقط المشاريع المرتبطة بأنشطة تلك الوحدة
        if ($userUnitId) {
            $query->whereHas('activities', function ($q) use ($userUnitId) {
                $q->where('unit_id', $userUnitId);
            });
        }

        return $query->get()->map(function ($project) {

            // --- منطق حساب المدة (المدة بالأيام) ---
            $duration = $project->total_duration_days; // نأخذ القيمة المخزنة أولاً

            // إذا كانت القيمة فارغة، نقوم بحسابها برمجياً
            if (empty($duration) && $project->start_date && $project->end_date) {
                try {
                    $start = Carbon::parse($project->start_date);
                    $end = Carbon::parse($project->end_date);
                    // حساب الفرق بالأيام
                    $duration = $end->diffInDays($start);
                } catch (\Exception $e) {
                    $duration = 0;
                }
            }

            return [
                'id' => $project->id,
                'project_code' => $project->project_code,
                'name' => $project->name,
                'organization' => $project->organization->name ?? 'غير محدد',
                'donor_name' => $project->donor_name,
                'project_type' => $project->projectType->name ?? 'غير محدد',
                'supervisor_name' => $project->supervisor_name,
                'supervisor_phone' => $project->supervisor_phone,

                // الحالات
                'main_status' => $project->mainStatus->name ?? '-',
                'general_status' => $project->generalStatus->name ?? '-',
                'handover_status' => $project->handoverStatus->name ?? '-',

                // القيم المالية
                'total_value' => $project->total_value ? number_format((float)$project->total_value, 2) : '0.00',
                'currency' => $project->currency,

                // التواريخ والمدد
                'contract_date' => $project->contract_date,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'total_duration_days' => $duration, // القيمة المحسوبة أو المخزنة

                // البيانات الرسمية والملاحظات
                'hac_issue_number' => $project->hac_issue_number,
                'approval_number' => $project->approval_number,
                'notes' => $project->notes,

                'created_at' => $project->created_at->format('Y-m-d'),
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
            'تاريخ الإدخال',
        ];
    }

    public function title(): string
    {
        return 'قائمة المشاريع';
    }

    // تنسيق الصف الأول (العناوين) ليكون بالخط العريض
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
