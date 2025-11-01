<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\WellLicense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WellLicenseController extends Controller
{
    /**
     * قم بإعداد الصلاحيات لجميع الدوال في المتحكم.
     */
    public function __construct()
    {
        $this->middleware('permission:well_licenses.view')->only(['index', 'show']);
        $this->middleware('permission:well_licenses.create')->only(['create', 'store']);
        $this->middleware('permission:well_licenses.edit')->only(['edit', 'update']);
        $this->middleware('permission:well_licenses.delete')->only('destroy');
    }

    /**
     * عرض قائمة بجميع تراخيص الآبار مع إمكانية البحث والفلترة.
     */
    public function index(Request $request)
    {
        $query = WellLicense::query();

        // تطبيق البحث إذا كان هناك نص في الطلب
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('archive_code', 'like', "%{$searchTerm}%")
                  ->orWhere('property_number', 'like', "%{$searchTerm}%")
                  ->orWhere('applicant_name', 'like', "%{$searchTerm}%");
            });
        }

        // تطبيق الفلترة بناءً على نوع الطلب
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        // جلب البيانات بعد التصفية مع تحميل العلاقة (المحطة) لتجنب N+1 problem
        $wellLicenses = $query->with('station')->latest()->paginate(20);

        // جلب أنواع الطلبات من الموديل لاستخدامها في فلتر الواجهة
        $requestTypes = WellLicense::REQUEST_TYPES;

        return view('dashboard.well-licenses.index', compact('wellLicenses', 'requestTypes'));
    }

    /**
     * عرض نموذج إنشاء ترخيص بئر جديد.
     */
    public function create()
    {
        // جلب جميع المحطات لعرضها في قائمة منسدلة
        $stations = Station::all();
        // جلب أنواع الطلبات من الموديل لعرضها في قائمة منسدلة
        $requestTypes = WellLicense::REQUEST_TYPES;

        return view('dashboard.well-licenses.create', compact('stations', 'requestTypes'));
    }

    /**
     * تخزين ترخيص بئر جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'archive_code' => 'required|string|max:255|unique:well_licenses,archive_code',
            'property_number' => 'required|string|max:255',
            'property_zone' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'request_type' => ['required', 'string', Rule::in(WellLicense::REQUEST_TYPES)],
            'institution_letter_date' => 'nullable|date',
            'directorate_letter_number' => 'nullable|string|max:255',
            'directorate_letter_date' => 'nullable|date',
            'declared_distance_meters' => 'nullable|integer|min:0',
            'station_id' => 'nullable|exists:stations,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'physical_cabinet' => 'nullable|string|max:255',
            'physical_shelf' => 'nullable|string|max:255',
            'physical_file_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'file_url' => 'nullable|string', // أو 'file' إذا كان سيتم رفع ملف
        ]);

        WellLicense::create($validatedData);

        return redirect()->route('dashboard.well-licenses.index')->with('success', 'تمت إضافة الترخيص بنجاح.');
    }

    /**
     * عرض تفاصيل ترخيص بئر معين.
     * استخدام Route Model Binding لجلب السجل تلقائياً.
     */
    public function show(WellLicense $wellLicense)
    {
        return view('dashboard.well-licenses.show', compact('wellLicense'));
    }

    /**
     * عرض نموذج تعديل ترخيص بئر.
     */
    public function edit(WellLicense $wellLicense)
    {
        $stations = Station::all();
        $requestTypes = WellLicense::REQUEST_TYPES;

        return view('dashboard.well-licenses.edit', compact('wellLicense', 'stations', 'requestTypes'));
    }

    /**
     * تحديث بيانات ترخيص البئر في قاعدة البيانات.
     */
    public function update(Request $request, WellLicense $wellLicense)
    {
        $validatedData = $request->validate([
            'archive_code' => ['required', 'string', 'max:255', Rule::unique('well_licenses')->ignore($wellLicense->id)],
            'property_number' => 'required|string|max:255',
            'property_zone' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'request_type' => ['required', 'string', Rule::in(WellLicense::REQUEST_TYPES)],
            'institution_letter_date' => 'nullable|date',
            'directorate_letter_number' => 'nullable|string|max:255',
            'directorate_letter_date' => 'nullable|date',
            'declared_distance_meters' => 'nullable|integer|min:0',
            'station_id' => 'nullable|exists:stations,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'physical_cabinet' => 'nullable|string|max:255',
            'physical_shelf' => 'nullable|string|max:255',
            'physical_file_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'file_url' => 'nullable|string',
        ]);

        $wellLicense->update($validatedData);

        return redirect()->route('dashboard.well-licenses.index')->with('success', 'تم تحديث الترخيص بنجاح.');
    }

    /**
     * حذف ترخيص بئر من قاعدة البيانات.
     */
    public function destroy(WellLicense $wellLicense)
    {
        $wellLicense->delete();

        return redirect()->route('dashboard.well-licenses.index')->with('success', 'تم حذف الترخيص بنجاح.');
    }
}
