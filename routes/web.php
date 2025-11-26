<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Livewire\ChatBot;
use Illuminate\Support\Facades\Route;
// استيراد الكلاسات اللازمة للكود المؤقت
use App\Models\Project;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/chatbot', ChatBot::class);

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

// =========================================================================
//  مسار مؤقت لإصلاح أكواد المشاريع القديمة حسب الأشهر المخصصة
//  تعليمات الاستخدام:
//  1. احفظ الملف.
//  2. افتح الرابط التالي في المتصفح: /update-project-codes-custom
//  3. بعد ظهور رسالة النجاح، ارجع واحذف هذا الجزء من الملف.
// =========================================================================

Route::get('/update-project-codes-custom', function () {

    // 1. جلب المشاريع وترتيبها زمنياً (الأقدم أولاً) لضمان تسلسل الأرقام بشكل منطقي (001 للأقدم)
    $projects = Project::with('organization')
        ->orderBy('start_date', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    $counters = []; // مصفوفة لتتبع الرقم التسلسلي لكل مجموعة (بادئة)
    $updatedCount = 0;

    // 2. مصفوفة الأشهر المخصصة (مطابقة لما وضعناه في Observer)
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

    foreach ($projects as $project) {
        // تجاوز المشاريع التي لا تملك منظمة لتجنب الأخطاء
        if (!$project->organization) continue;

        // تحديد التاريخ المعتمد (تاريخ البداية هو الأساس، وإلا تاريخ الإنشاء)
        $dateBasis = $project->start_date ? Carbon::parse($project->start_date) : $project->created_at;

        // تجهيز أجزاء الكود
        $orgCode = $project->organization->code ?? 'PRJ';
        $year = $dateBasis->format('y'); // السنة (مثلاً 25)

        // الحصول على كود الشهر المخصص من المصفوفة
        $monthCode = $customMonths[$dateBasis->month] ?? 'JAN';

        // تكوين البادئة: مثال GOL-25-FBR
        $prefix = "{$orgCode}-{$year}-{$monthCode}";

        // حساب الرقم التسلسلي لهذه المجموعة المحددة
        if (!isset($counters[$prefix])) {
            $counters[$prefix] = 1;
        } else {
            $counters[$prefix]++;
        }

        // تنسيق الرقم ليكون 3 خانات (001, 002...)
        $sequence = str_pad($counters[$prefix], 3, '0', STR_PAD_LEFT);

        // الكود النهائي الجديد
        $newCode = "{$prefix}-{$sequence}";

        // التحديث المباشر في قاعدة البيانات
        // نستخدم update هنا لتجاوز الـ Observer وضمان حفظ الكود كما حسبناه بالضبط
        Project::where('id', $project->id)->update(['project_code' => $newCode]);

        $updatedCount++;
    }

    return "<div style='font-family: sans-serif; text-align: center; padding: 50px; direction: rtl;'>
                <h1 style='color: green;'>تمت العملية بنجاح! ✅</h1>
                <p>تم تحديث وإعادة تسلسل أكواد <b>{$updatedCount}</b> مشروع.</p>
                <p>تم استخدام اختصارات الأشهر المخصصة (FBR, ABR, YUN...).</p>
                <hr>
                <p style='color: red; font-weight: bold;'>يرجى الآن حذف الكود المضاف في أسفل ملف routes/web.php</p>
            </div>";
});
