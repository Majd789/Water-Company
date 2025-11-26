<?php

namespace App\Observers;

use App\Models\Project;
use Carbon\Carbon;

class ProjectObserver
{
    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project): void
    {
        // 1. إذا تم إدخال الكود يدوياً، لا تفعل شيئاً
        if (!empty($project->project_code)) {
            return;
        }

        // 2. تحميل علاقة المنظمة
        $project->load('organization');
        $organizationCode = $project->organization->code ?? 'PRJ';

        // 3. تحديد التاريخ المعتمد (تاريخ البدء هو الأساس، وإلا التاريخ الحالي)
        $dateBasis = $project->start_date ? Carbon::parse($project->start_date) : Carbon::now();

        $year = $dateBasis->format('y'); // السنة (مثلاً 25)

        // --- تعديل هنا: استخدام قائمة الأشهر المخصصة ---
        $customMonths = [
            1 => 'JAN',
            2 => 'FBR', // مخصص
            3 => 'MAR',
            4 => 'ABR', // مخصص
            5 => 'MAY',
            6 => 'YUN', // مخصص
            7 => 'YUL', // مخصص
            8 => 'AGS', // مخصص
            9 => 'SBT', // مخصص
            10 => 'AKT', // مخصص
            11 => 'NUF', // مخصص
            12 => 'DIS'  // مخصص
        ];

        // الحصول على كود الشهر من المصفوفة بناءً على رقم الشهر
        // (استخدمنا المصفوفة مباشرة لضمان ظهور FBR وليس FEB)
        $monthCode = $customMonths[$dateBasis->month] ?? 'JAN';

        // تكوين البادئة (Prefix)
        $prefix = "{$organizationCode}-{$year}-{$monthCode}";

        // 4. البحث عن آخر مشروع بنفس البادئة لتحديد الرقم التسلسلي التالي
        $latestProject = Project::where('project_code', 'LIKE', $prefix . '-%')
                                ->orderByRaw('LENGTH(project_code) DESC') // لضمان الترتيب الصحيح (9 قبل 10)
                                ->orderBy('id', 'desc')
                                ->first();

        $sequence = 1;
        if ($latestProject) {
            // استخراج الرقم التسلسلي الأخير من الكود
            $parts = explode('-', $latestProject->project_code);
            $lastNumber = end($parts);

            if (is_numeric($lastNumber)) {
                $sequence = (int) $lastNumber + 1;
            }
        }

        // 5. بناء الكود النهائي (مثال: GOL-25-FBR-001)
        $project->project_code = $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Handle the Project "saving" event.
     * (اختياري: لحساب مدة المشروع تلقائياً كما طلبت سابقاً)
     */
    public function saving(Project $project): void
    {
        if ($project->start_date && $project->end_date) {
            $start = Carbon::parse($project->start_date);
            $end = Carbon::parse($project->end_date);
            $project->total_duration_days = $end->diffInDays($start);
        }
    }
}
