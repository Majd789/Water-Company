<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\HorizontalPumpsExport;
use App\Imports\HorizontalPumpsImport;
use Illuminate\Http\Request;
use App\Models\HorizontalPump;
use App\Models\Station;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class HorizontalPumpController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:horizontal_pumps.view')->only(['index', 'show']);
        $this->middleware('permission:horizontal_pumps.create')->only(['create', 'store']);
        $this->middleware('permission:horizontal_pumps.edit')->only(['edit', 'update']);
        $this->middleware('permission:horizontal_pumps.delete')->only('destroy');
       
    }
   /**
     * عرض قائمة المضخات.
     */
    public function index(Request $request)
{
    // استرجاع جميع الوحدات لخيارات الفلترة
    $units = Unit::all();

    // الحصول على وحدة المستخدم الحالية (إن وجدت)
    $userUnitId = auth()->user()->unit_id;

    // إنشاء استعلام لجلب المضخات الأفقية
    $query = HorizontalPump::with('station');

    // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
    $selectedUnitId = $request->unit_id ?? $userUnitId;

    if (!empty($selectedUnitId)) {
        // تصفية المضخات بناءً على الوحدة المختارة
        $query->whereHas('station.town', function ($q) use ($selectedUnitId) {
            $q->where('unit_id', $selectedUnitId);
        });
    }

    // تصفية المضخات بناءً على البلدة المختارة
    if ($request->has('town_id') && $request->town_id != '') {
        $query->whereHas('station', function ($q) use ($request) {
            $q->where('town_id', $request->town_id);
        });
    }

    // البحث باستخدام نص يشمل جميع الحقول ذات الصلة
    if ($request->filled('search')) {
        $searchTerm = trim($request->search);

        $query->where(function ($q) use ($searchTerm) {
            $q->where('pump_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('pump_status', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('station', function ($stationQuery) use ($searchTerm) {
                  $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
              });
        });
    }

    // جلب البيانات مع التصفية والصفحات
    $horizontalPumps = $query->paginate(10000);

    // عرض البيانات في الصفحة
    return view('dashboard.horizontal-pumps.index', compact('horizontalPumps', 'units'));
}



    public function export()
    {
        return Excel::download(new HorizontalPumpsExport, 'horizontal_pumps.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new HorizontalPumpsImport, $request->file('file'));

        return redirect()->route('dashboard.horizontal-pumps.index')->with('success', 'تم استيراد المضخات الأفقية بنجاح.');
    }

    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;

        // إذا كانت هناك وحدة، جلب المحطات عبر البلدات المرتبطة بالوحدة
        if ($unit) {
            $stations = \App\Models\Station::whereIn('town_id', $unit->towns->pluck('id'))->get();
        } else {
            // إذا لم تكن هناك وحدة، جلب جميع المحطات
            $stations = \App\Models\Station::all();
        }

        return view('dashboard.horizontal-pumps.create', compact('stations'));
    }


     private function getAllowedPumpBrands()
    {
        return [
            'HALLER & SCHNEIDER', 'German Made', 'Italian Made', 'Turkish Made', 'STANDART',
            'MEZ', 'CAPRARI', 'GAMAK', 'SEMPA', 'SEVER', 'Czech Made', 'WATT', 'European',
            'SKM', 'GRUNDFOS', 'MAS', 'SIEMENS', 'ROVATTI', 'Spanish Made', 'DEMAK',
            'Iranian Made', 'KLN', 'ELK', 'PENTAX', 'Chinese Made', 'LOWARA', 'JET',
            'FLOWSERVE', 'KSB', 'ATURIA', 'غير معروف'
        ];
    }

    // دالة مساعدة لمصادر الطاقة
    private function getAllowedEnergySources()
    {
        return [
            'لا يوجد', 'كهرباء', 'مولدة', 'طاقة شمسية', 'كهرباء و مولدة',
            'كهرباء و طاقة شمسية', 'مولدة و طاقة شمسية', 'كهرباء و مولدة و طاقة شمسية'
        ];
    }
    /**
     * حفظ مضخة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'pump_status' => 'nullable|in:يعمل,متوقفة',
            'pump_name' => 'nullable|string|max:255',
            'pump_capacity_hp' => 'nullable|numeric|min:0',  // تعديل إلى numeric لدعم القيم العشرية
            'pump_flow_rate_m3h' => 'nullable|numeric|min:0',  // تعديل إلى numeric لدعم القيم العشرية
            'pump_head' => 'nullable|numeric|min:0',  // تعديل إلى numeric لدعم القيم العشرية
           'pump_brand_model' => ['nullable', Rule::in($this->getAllowedPumpBrands())],
            'technical_condition' => 'nullable|string|max:255',
            'energy_source' => ['nullable', Rule::in($this->getAllowedEnergySources())],
            'notes' => 'nullable|string',
        ]);


        HorizontalPump::create($request->all());

        return redirect()->route('dashboard.horizontal-pumps.index')->with('success', 'تم إضافة المضخة بنجاح.');
    }

    /**
     * عرض تفاصيل مضخة محددة.
     */
    public function show(HorizontalPump $horizontalPump)
    {
        return view('dashboard.horizontal-pumps.show', compact('horizontalPump'));
    }

    /**
     * عرض صفحة تعديل مضخة محددة.
     */
    public function edit(HorizontalPump $horizontalPump)
    {
        $stations = Station::all(); // جلب قائمة المحطات
        return view('dashboard.horizontal-pumps.edit', compact('horizontalPump', 'stations'));
    }

    /**
     * تحديث بيانات مضخة موجودة.
     */
    public function update(Request $request, HorizontalPump $horizontalPump)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'pump_status' => 'nullable|in:يعمل,متوقفة',
            'pump_name' => 'nullable|string|max:255',
            'pump_capacity_hp' => 'nullable|numeric|min:0',
            'pump_flow_rate_m3h' => 'nullable|numeric|min:0',
            'pump_head' => 'nullable|numeric|min:0',
            'pump_brand_model' => ['nullable', Rule::in($this->getAllowedPumpBrands())],
            'technical_condition' => 'nullable|string|max:255',
            'energy_source' => ['nullable', Rule::in($this->getAllowedEnergySources())],
            'notes' => 'nullable|string',
        ]);

        $horizontalPump->update($request->all());

        return redirect()->route('dashboard.horizontal-pumps.index')->with('success', 'تم تحديث بيانات المضخة بنجاح.');
    }

    /**
     * حذف مضخة من قاعدة البيانات.
     */
    public function destroy(HorizontalPump $horizontalPump)
    {
        $horizontalPump->delete();

        return redirect()->route('dashboard.horizontal-pumps.index')->with('success', 'تم حذف المضخة بنجاح.');
    }
}
