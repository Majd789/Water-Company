<?php

namespace App\Observers;

use App\Models\Project;
use Carbon\Carbon;

class ProjectObserver
{
    /**
     * Handle the Project "creating" event.
     *
     * This method is called automatically right before a new Project is saved to the database.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function creating(Project $project): void
    {
        // الخطوة 1: تحقق مما إذا كان المستخدم قد أدخل الكود يدوياً. إذا كان كذلك، لا تفعل شيئاً.
        if (!empty($project->project_code)) {
            return;
        }

        // الخطوة 2: تحميل علاقة المنظمة للتأكد من أننا نستطيع الوصول إلى كودها.
        // قد لا تكون هذه الخطوة ضرورية إذا كان كود المنظمة متاحاً دائماً، لكنها آمنة.
        $project->load('organization');

        // الخطوة 3: تجميع أجزاء الكود
        $organizationCode = $project->organization->code ?? 'PRJ'; // استخدم 'PRJ' كقيمة افتراضية إذا لم تكن المنظمة موجودة
        $year = Carbon::now()->format('y'); // '25' لعام 2025

        // استخدام جدول الأشهر الذي أنشأناه للحصول على الكود الصحيح
        $monthCode = \App\Models\Month::where('month_number', Carbon::now()->month)->first()->code ?? strtoupper(Carbon::now()->format('M'));

        $prefix = "{$organizationCode}-{$year}-{$monthCode}";

        // الخطوة 4: البحث عن آخر مشروع بنفس البادئة لتحديد الرقم التسلسلي التالي
        $latestProject = Project::where('project_code', 'LIKE', $prefix . '-%')
                                ->orderBy('id', 'desc') // استخدام أحدث ID لضمان الدقة
                                ->first();

        $sequence = 1; // الرقم التسلسلي يبدأ من 1
        if ($latestProject) {
            // استخراج الرقم التسلسلي الأخير من الكود (آخر 3 أحرف)
            $lastSequence = (int) substr($latestProject->project_code, -3);
            $sequence = $lastSequence + 1;
        }

        // الخطوة 5: بناء الكود النهائي وتعيينه للموديل قبل الحفظ
        // str_pad تضمن أن الرقم دائمًا 3 خانات (مثل 001, 012, 123)
        $project->project_code = $prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
