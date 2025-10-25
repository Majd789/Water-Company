<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SafetyProfile;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
// use App\Exports\SafetyProfilesExport;
// use App\Imports\SafetyProfilesImport;
use Maatwebsite\Excel\Facades\Excel;

class SafetyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:safety_profiles.view')->only('index');
        $this->middleware('permission:safety_profiles.edit')->only(['edit', 'update']);
        $this->middleware('permission:safety_profiles.delete')->only('destroy');
        $this->middleware('permission:safety_profiles.export')->only('export');
        $this->middleware('permission:safety_profiles.import')->only('import');
    }

    /**
     * عرض جميع ملفات السلامة للمحطات مع الفلترة والبحث.
     */
    public function index(Request $request)
    {
        $userUnitId = auth()->user()->unit_id;
        $units = Unit::all();

        // الاستعلام يبدأ من المحطات، لأنها الأساس
       $stations = Station::with(['town', 'safetyProfile']);

        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // فلترة حسب الوحدة الإدارية
        if (!empty($selectedUnitId)) {
            $stations->whereHas('town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // فلترة حسب البحث
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $stations->where('station_name', 'like', '%' . $searchTerm . '%');
        }

        $stations = $stations->paginate(25);

        return view('dashboard.safety_profiles.index', compact('stations', 'units', 'selectedUnitId'));
    }

    /**
     * عرض نموذج تعديل/إنشاء ملف السلامة لمحطة معينة.
     */
    public function edit(Station $station)
    {
        // ابحث عن ملف السلامة، أو أنشئ كائن جديد فارغ إذا لم يكن موجوداً
        $safetyProfile = $station->safetyProfile ?? new SafetyProfile();

        return view('dashboard.safety_profiles.edit', compact('station', 'safetyProfile'));
    }

    /**
     * تحديث أو إنشاء ملف السلامة لمحطة معينة.
     */
    public function update(Request $request, Station $station)
    {
        $validatedData = $request->validate([
            'has_ppe' => 'sometimes|boolean',
            'ppe_types' => 'nullable|string',
            'ppe_training_provided' => 'sometimes|boolean',
            'has_fire_extinguishers' => 'sometimes|boolean',
            'has_evacuation_plan' => 'sometimes|boolean',
            'chemical_storage_safe' => 'sometimes|boolean',
            'has_warning_signs' => 'sometimes|boolean',
            'has_first_aid_kit' => 'sometimes|boolean',
            'first_aid_training_provided' => 'sometimes|boolean',
            'emergency_numbers_visible' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        // Laravel يتعامل تلقائياً مع checkboxes التي لم يتم تحديدها (لا يرسلها)
        // لذا، يجب أن نعالجها يدوياً لضبط قيمتها إلى false
        $dataToUpdate = [
            'has_ppe' => $request->has('has_ppe'),
            'ppe_training_provided' => $request->has('ppe_training_provided'),
            'has_fire_extinguishers' => $request->has('has_fire_extinguishers'),
            'has_evacuation_plan' => $request->has('has_evacuation_plan'),
            'chemical_storage_safe' => $request->has('chemical_storage_safe'),
            'has_warning_signs' => $request->has('has_warning_signs'),
            'has_first_aid_kit' => $request->has('has_first_aid_kit'),
            'first_aid_training_provided' => $request->has('first_aid_training_provided'),
            'emergency_numbers_visible' => $request->has('emergency_numbers_visible'),
            'ppe_types' => $request->ppe_types,
            'notes' => $request->notes,
        ];


        // استخدام updateOrCreate لتحديث أو إنشاء ملف السلامة للمحطة
        $station->safetyProfile()->updateOrCreate(
            ['station_id' => $station->id], // الشرط
            $dataToUpdate // البيانات
        );

        return redirect()->route('dashboard.safety-profiles.index')->with('success', 'تم تحديث ملف السلامة للمحطة بنجاح.');
    }

    /**
     * حذف ملف السلامة لمحطة معينة.
     */
    public function destroy(SafetyProfile $safetyProfile)
    {
        $safetyProfile->delete();
        return redirect()->route('dashboard.safety-profiles.index')->with('success', 'تم حذف ملف السلامة بنجاح.');
    }

    // الدوال التالية غير مستخدمة بشكل مباشر ولكنها موجودة للتوحيد
    public function create() { return redirect()->route('dashboard.safety-profiles.index'); }
    public function store(Request $request) { return redirect()->route('dashboard.safety-profiles.index'); }
        public function show(Station $station) // <-- التعديل هنا: نستقبل Station
        {
            // تحميل علاقة safetyProfile بشكل صريح
            $station->load('safetyProfile');

            // إذا لم يكن للمحطة ملف سلامة، يمكننا إما عرض صفحة فارغة أو تحويله لصفحة الإنشاء
            if (!$station->safetyProfile) {
                return redirect()->route('dashboard.safety-profiles.edit', $station->id)
                                ->with('info', 'هذه المحطة لا تملك ملف سلامة بعد. يمكنك إضافته الآن.');
            }

            return view('dashboard.safety_profiles.show', [
                'station' => $station,
                'safetyProfile' => $station->safetyProfile
            ]);
        }
    public function export()
    {
        // return Excel::download(new SafetyProfilesExport, 'safety_profiles.xlsx');
        return back()->with('info', 'ميزة التصدير قيد التطوير.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        // Excel::import(new SafetyProfilesImport, $request->file('file'));
        // return redirect()->route('dashboard.safety_profiles.index')->with('success', 'تم استيراد البيانات بنجاح');
        return back()->with('info', 'ميزة الاستيراد قيد التطوير.');
    }
}
