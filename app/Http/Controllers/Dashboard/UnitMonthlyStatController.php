<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UnitMonthlyStat;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitMonthlyStatController extends Controller
{
    /**
     * قم بتعيين الصلاحيات المطلوبة لكل دالة.
     */
    public function __construct()
    {
        $this->middleware('permission:unit_stats.view')->only(['index', 'show']);
        $this->middleware('permission:unit_stats.create')->only(['create', 'store']);
        $this->middleware('permission:unit_stats.edit_technical')->only(['editTechnical', 'updateTechnical']);
        $this->middleware('permission:unit_stats.edit_subscribers')->only(['editSubscribers', 'updateSubscribers']);
        $this->middleware('permission:unit_stats.delete')->only('destroy');
    }

    /**
     * عرض قائمة بالإحصائيات الشهرية المسجلة.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = UnitMonthlyStat::with('unit')->orderBy('year', 'desc')->orderBy('month', 'desc');

        // إذا كان المستخدم مرتبطًا بوحدة معينة، يعرض فقط إحصائيات وحدته
        if ($user->unit_id) {
            $query->where('unit_id', $user->unit_id);
            $units = Unit::where('id', $user->unit_id)->get(); // قائمة الوحدات تحتوي على وحدة واحدة فقط
        } else {
            $units = Unit::all(); // المستخدم السوبر يمكنه رؤية كل الوحدات
        }

        // تطبيق الفلاتر من واجهة المستخدم
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $stats = $query->paginate(50);

        return view('dashboard.unit_stats.index', compact('stats', 'units'));
    }

    /**
     * عرض نموذج إنشاء إحصائية شهرية جديدة.
     */
    public function create()
    {
        $user = Auth::user();

        // إذا كان المستخدم مرتبطًا بوحدة، يتم تحديدها تلقائيًا
        if ($user->unit_id) {
            $units = Unit::where('id', $user->unit_id)->get();
        } else {
            $units = Unit::all();
        }

        return view('dashboard.unit_stats.create', compact('units'));
    }

    /**
     * تخزين الإحصائية الشهرية الجديدة في قاعدة البيانات.
     * (النسخة المصححة)
     */
    public function store(Request $request)
    {
        // 1. التحقق من الحقول الأساسية أولاً (الوحدة والتاريخ)
        $baseData = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'month' => 'required|integer|min:1|max:12',
        ]);

        // 2. التحقق من عدم وجود سجل مسبق
        $exists = UnitMonthlyStat::where('unit_id', $baseData['unit_id'])
                                 ->where('year', $baseData['year'])
                                 ->where('month', $baseData['month'])
                                 ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['month' => 'يوجد سجل إحصائي لهذه الوحدة في هذا الشهر والسنة بالفعل. يمكنك تعديله من قائمة الإحصائيات.']);
        }

        // 3. التحقق من الحقول الإحصائية بناءً على صلاحيات المستخدم
        $statsData = [];
        $user = auth()->user();

        // إذا كان المستخدم يملك صلاحية إدخال البيانات التقنية، تحقق منها
        if ($user->can('unit_stats.edit_technical')) {
            $technicalRules = $this->getValidationRules(false, 'technical');
            $statsData = array_merge($statsData, $request->validate($technicalRules));
        }

        // إذا كان المستخدم يملك صلاحية إدخال بيانات المشتركين، تحقق منها
        if ($user->can('unit_stats.edit_subscribers')) {
            $subscriberRules = $this->getValidationRules(false, 'subscribers');
            $statsData = array_merge($statsData, $request->validate($subscriberRules));
        }

        // التحقق من الملاحظات بشكل مشترك
        $statsData = array_merge($statsData, $request->validate(['notes' => 'nullable|string']));

        // 4. دمج كل البيانات التي تم التحقق منها وإنشاء السجل
        UnitMonthlyStat::create(array_merge($baseData, $statsData));

        return redirect()->route('dashboard.unit-stats.index')->with('success', 'تم تسجيل الإحصائية الشهرية بنجاح.');
    }

    /**
     * عرض تفاصيل إحصائية شهرية محددة.
     */
    public function show(UnitMonthlyStat $unitMonthlyStat)
    {
        $unitMonthlyStat->load('unit');
        return view('dashboard.unit_stats.show', compact('unitMonthlyStat'));
    }

    // ==========================================================
    // القسم التقني
    // ==========================================================
    public function editTechnical(UnitMonthlyStat $unitMonthlyStat)
    {
        $unitMonthlyStat->load('unit');
        return view('dashboard.unit_stats.edit_technical', compact('unitMonthlyStat'));
    }

    public function updateTechnical(Request $request, UnitMonthlyStat $unitMonthlyStat)
    {
        $validatedData = $request->validate($this->getValidationRules(true, 'technical'));

        $unitMonthlyStat->update($validatedData);

        return redirect()->route('dashboard.unit-stats.index')->with('success', 'تم تحديث البيانات التقنية بنجاح.');
    }

    // ==========================================================
    // قسم المشتركين
    // ==========================================================
    public function editSubscribers(UnitMonthlyStat $unitMonthlyStat)
    {
        $unitMonthlyStat->load('unit');
        return view('dashboard.unit_stats.edit_subscribers', compact('unitMonthlyStat'));
    }

    public function updateSubscribers(Request $request, UnitMonthlyStat $unitMonthlyStat)
    {
        $validatedData = $request->validate($this->getValidationRules(true, 'subscribers'));

        $unitMonthlyStat->update($validatedData);

        return redirect()->route('dashboard.unit-stats.index')->with('success', 'تم تحديث بيانات المشتركين بنجاح.');
    }

    /**
     * حذف إحصائية شهرية من قاعدة البيانات.
     */
    public function destroy(UnitMonthlyStat $unitMonthlyStat)
    {
        $unitMonthlyStat->delete();

        return redirect()->route('dashboard.unit-stats.index')->with('success', 'تم حذف السجل الإحصائي بنجاح.');
    }

    /**
     * دالة مساعدة مرنة للحصول على قواعد التحقق.
     * (النسخة المصححة)
     */
    private function getValidationRules(bool $isRequired = true, string $section = 'all'): array
    {
        $ruleType = $isRequired ? 'required' : 'nullable';

        $rules = [];

        // Rules for Technical Section
        if ($section === 'all' || $section === 'technical') {
            $rules += [
                'produced_water_m3' => [$ruleType, 'numeric', 'min:0'],
                'lost_water_m3' => [$ruleType, 'numeric', 'min:0'],
                'distributed_water_m3' => [$ruleType, 'numeric', 'min:0'],
            ];
        }

        // Rules for Subscribers Section
        if ($section === 'all' || $section === 'subscribers') {
             $rules += [
                'total_subscribers' => [$ruleType, 'integer', 'min:0'],
                'metered_subscribers' => [$ruleType, 'integer', 'min:0'],
                'flat_rate_subscribers' => [$ruleType, 'integer', 'min:0'],
                'active_subscribers' => [$ruleType, 'integer', 'min:0'],
                'departed_subscribers' => [$ruleType, 'integer', 'min:0'],
                'canceled_subscribers' => [$ruleType, 'integer', 'min:0'],
                'disconnected_subscribers' => [$ruleType, 'integer', 'min:0'],
                'housing_project_subscribers' => [$ruleType, 'integer', 'min:0'],
                'housing_project_defaulters' => [$ruleType, 'integer', 'min:0'],
                'gov_building_subscribers' => [$ruleType, 'integer', 'min:0'],
                'gov_building_defaulters' => [$ruleType, 'integer', 'min:0'],
                'owned_property_subscribers' => [$ruleType, 'integer', 'min:0'],
                'owned_property_defaulters' => [$ruleType, 'integer', 'min:0'],
                'rented_property_subscribers' => [$ruleType, 'integer', 'min:0'],
                'rented_property_defaulters' => [$ruleType, 'integer', 'min:0'],
                'domestic_subscription_subscribers' => [$ruleType, 'integer', 'min:0'],
                'domestic_subscription_defaulters' => [$ruleType, 'integer', 'min:0'],
                'commercial_subscription_subscribers' => [$ruleType, 'integer', 'min:0'],
                'commercial_subscription_defaulters' => [$ruleType, 'integer', 'min:0'],
                'total_paid_count' => [$ruleType, 'integer', 'min:0'],
                'total_paid_amount' => [$ruleType, 'numeric', 'min:0'],
                'total_defaulters_count' => [$ruleType, 'integer', 'min:0'],
                'total_defaulters_amount' => [$ruleType, 'numeric', 'min:0'],
                'exempted_count' => [$ruleType, 'integer', 'min:0'],
                'exempted_amount' => [$ruleType, 'numeric', 'min:0'],
            ];
        }

        // Common Rules - We handle notes separately in store/update to avoid duplication
        if ($section === 'all') {
            $rules['notes'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
