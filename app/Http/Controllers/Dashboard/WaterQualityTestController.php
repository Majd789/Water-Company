<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WaterQualityTest;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
// ملاحظة: ستحتاج لإنشاء هذه الملفات لاحقاً
// use App\Exports\WaterQualityTestsExport;
// use App\Imports\WaterQualityTestsImport;
use Maatwebsite\Excel\Facades\Excel;

class WaterQualityTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:water_quality_tests.view')->only(['index', 'show']);
        $this->middleware('permission:water_quality_tests.create')->only(['create', 'store']);
        $this->middleware('permission:water_quality_tests.edit')->only(['edit', 'update']);
        $this->middleware('permission:water_quality_tests.delete')->only('destroy');
        // صلاحيات إضافية للتصدير والاستيراد
        $this->middleware('permission:water_quality_tests.export')->only('export');
        $this->middleware('permission:water_quality_tests.import')->only('import');
    }

    /**
     * عرض جميع سجلات جودة المياه مع الفلترة والبحث.
     */
    public function index(Request $request)
    {
        $userUnitId = auth()->user()->unit_id;
        $units = Unit::all();

        // استعلام أساسي مع جلب علاقة المحطة لتجنب N+1 problem
        $waterQualityTests = WaterQualityTest::with('station');

        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // فلترة النتائج بناءً على الوحدة الإدارية للمستخدم أو الفلتر
        if (!empty($selectedUnitId)) {
            $waterQualityTests->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // فلترة بناءً على حقل البحث الموحد
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $waterQualityTests->whereHas('station', function ($q) use ($searchTerm) {
                $q->where('station_name', 'like', '%' . $searchTerm . '%'); // البحث باسم المحطة
            });
        }

        $waterQualityTests = $waterQualityTests->latest('test_date')->paginate(25);

        return view('dashboard.water_quality_tests.index', compact('waterQualityTests', 'units', 'selectedUnitId'));
    }

    /**
     * عرض نموذج إنشاء سجل جودة مياه جديد.
     */
    public function create()
    {
        $unit = auth()->user()->unit;
        $stations = collect(); // مجموعة فارغة كقيمة افتراضية

        // جلب المحطات بناءً على وحدة المستخدم
        if ($unit) {
            $towns = $unit->towns()->pluck('id');
            $stations = Station::whereIn('town_id', $towns)->get();
        } else {
            // إذا كان المستخدم admin وليس له وحدة، جلب كل المحطات
            $stations = Station::all();
        }

        return view('dashboard.water_quality_tests.create', compact('stations'));
    }

    /**
     * تخزين سجل جودة مياه جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'test_date' => 'required|date',
            'turbidity' => 'nullable|string|max:255',
            'ph_level' => 'nullable|string|max:255',
            'microbial_analysis' => 'nullable|string|max:255',
            'complaints' => 'nullable|string',
        ]);

        WaterQualityTest::create($validatedData);

        return redirect()->route('dashboard.water-quality-tests.index')->with('success', 'تمت إضافة سجل جودة المياه بنجاح.');
    }

    /**
     * عرض تفاصيل سجل معين. (قد لا تكون هذه الصفحة ضرورية)
     */
    public function show(WaterQualityTest $waterQualityTest)
    {
        // عادةً ما يتم عرض البيانات في صفحة index، لكن يمكن استخدامها لعرض تفاصيل إضافية
        return view('dashboard.water_quality_tests.show', compact('waterQualityTest'));
    }

    /**
     * عرض نموذج تعديل بيانات سجل جودة المياه.
     */
    public function edit(WaterQualityTest $waterQualityTest)
    {
        $stations = Station::all(); // جلب جميع المحطات لسهولة التعديل إذا لزم الأمر
        return view('dashboard.water_quality_tests.edit', compact('waterQualityTest', 'stations'));
    }

    /**
     * تحديث بيانات سجل جودة مياه معين.
     */
    public function update(Request $request, WaterQualityTest $waterQualityTest)
    {
        $validatedData = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'test_date' => 'required|date',
            'turbidity' => 'nullable|string|max:255',
            'ph_level' => 'nullable|string|max:255',
            'microbial_analysis' => 'nullable|string|max:255',
            'complaints' => 'nullable|string',
        ]);

        $waterQualityTest->update($validatedData);

        return redirect()->route('dashboard.water-quality-tests.index')->with('success', 'تم تحديث سجل جودة المياه بنجاح.');
    }

    /**
     * حذف سجل جودة مياه معين.
     */
    public function destroy(WaterQualityTest $waterQualityTest)
    {
        $waterQualityTest->delete();

        return redirect()->route('dashboard.water-quality-tests.index')->with('success', 'تم حذف سجل جودة المياه بنجاح.');
    }

    /**
     * تصدير البيانات إلى ملف Excel.
     */
    public function export()
    {
        // return Excel::download(new WaterQualityTestsExport, 'water_quality_tests.xlsx');
        return back()->with('info', 'ميزة التصدير قيد التطوير.');
    }

    /**
     * استيراد البيانات من ملف Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);

        // Excel::import(new WaterQualityTestsImport, $request->file('file'));

        // return redirect()->route('dashboard.water_quality_tests.index')->with('success', 'تم استيراد البيانات بنجاح');
        return back()->with('info', 'ميزة الاستيراد قيد التطوير.');
    }
}
