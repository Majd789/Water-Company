<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\Maintenance;
use App\Models\MaintenanceType;
use App\Models\Station;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaintenancesExport;
use App\Imports\MaintenancesImport;
use App\Models\Unit;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:maintenances.view')->only(['index', 'show']);
        $this->middleware('permission:maintenances.create')->only(['create', 'store']);
        $this->middleware('permission:maintenances.edit')->only(['edit', 'update']);
        $this->middleware('permission:maintenances.delete')->only('destroy');
    }
    /**
     * عرض جميع عمليات الصيانة
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لجلب الصيانات مع بيانات المحطات وأنواع الصيانة
        $query = Maintenance::with('station', 'maintenanceType');

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية الصيانات بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // التحقق من وجود مصطلح بحث
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة

            // البحث في اسم المحطة وكود المحطة
            $query->whereHas('station', function ($stationQuery) use ($searchTerm) {
                $stationQuery->where('station_name', 'like', "%$searchTerm%")
                             ->orWhere('station_code', 'like', "%$searchTerm%");
            });
        }

        // جلب البيانات بعد التصفية مع الترقيم
        $maintenances = $query->paginate(10000); // تحديد عدد النتائج لكل صفحة

        // تمرير البيانات إلى العرض
        return view('dashboard.maintenances.index', compact('maintenances', 'units', 'selectedUnitId'));
    }

    /**
     * تصدير بيانات الصيانة إلى ملف Excel
     */
    public function export()
    {
        return Excel::download(new MaintenancesExport, 'dashboard.maintenances.xlsx');
    }

    /**
     * استيراد بيانات الصيانة من ملف Excel
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new MaintenancesImport, $request->file('file'));
        return redirect()->route('dashboard.maintenances.index')->with('success', 'تم استيراد بيانات الصيانة بنجاح.');
    }

    /**
     * عرض نموذج إنشاء صيانة جديدة
     */
    public function create()
    {
        $stations = Station::all();
        $maintenanceTypes = MaintenanceType::all();
        return view('dashboard.maintenances.create', compact('stations', 'maintenanceTypes'));
    }

    /**
     * تخزين بيانات الصيانة الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'total_quantity' => 'required|integer',
            'execution_sites' => 'required|string',
            'total_cost' => 'required|numeric',
            'maintenance_date' => 'required|date',
            'maintenance_details' => 'nullable|string',
            'contractor_name' => 'nullable|string',
            'technician_name' => 'nullable|string',
            'status' => 'required|in:تمت,قيد التنفيذ,فشلت',
        ]);

        Maintenance::create($validated);
        return redirect()->route('dashboard.maintenances.index')->with('success', 'تمت إضافة الصيانة بنجاح.');
    }

    /**
     * عرض تفاصيل عملية صيانة معينة
     */
    public function show(Maintenance $maintenance)
    {
        return view('dashboard.maintenances.show', compact('maintenance'));
    }

    /**
     * عرض نموذج تعديل بيانات الصيانة
     */
    public function edit(Maintenance $maintenance)
    {
        $stations = Station::all();
        $maintenanceTypes = MaintenanceType::all();
        return view('dashboard.maintenances.edit', compact('maintenance', 'stations', 'maintenanceTypes'));
    }

    /**
     * تحديث بيانات الصيانة
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'total_quantity' => 'required|integer',
            'execution_sites' => 'required|string',
            'total_cost' => 'required|numeric',
            'maintenance_date' => 'required|date',
            'maintenance_details' => 'nullable|string',
            'contractor_name' => 'nullable|string',
            'technician_name' => 'nullable|string',
            'status' => 'required|in:تمت,قيد التنفيذ,فشلت',
        ]);

        $maintenance->update($validated);
        return redirect()->route('dashboard.maintenances.index')->with('success', 'تم تحديث بيانات الصيانة بنجاح.');
    }

    /**
     * حذف سجل الصيانة
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('dashboard.maintenances.index')->with('success', 'تم حذف سجل الصيانة بنجاح.');
    }
}
