<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\ManholesExport;
use App\Imports\ManholesImport;
use App\Models\Manhole;
use App\Models\Station;
use App\Models\Unit;
use App\Models\Town;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ManholeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manholes.view')->only(['index', 'show']);
        $this->middleware('permission:manholes.create')->only(['create', 'store']);
        $this->middleware('permission:manholes.edit')->only(['edit', 'update']);
        $this->middleware('permission:manholes.delete')->only('destroy');
    }
    /**
     * عرض جميع المنهلات
     */
    public function index(Request $request)
{
    // الحصول على الوحدة المرتبطة بالمستخدم الحالي
    $userUnitId = auth()->user()->unit_id;

    // استرجاع جميع الوحدات لاستخدامها في الفلترة
    $units = Unit::all();

    // استعلام لجلب المنهلات المرتبطة بالمحطات
    $query = Manhole::query();

    // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
    $selectedUnitId = $request->unit_id ?? $userUnitId;

    // تصفية المنهلات بناءً على الوحدة المختارة
    if (!empty($selectedUnitId)) {
        $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
            $townQuery->where('unit_id', $selectedUnitId);
        });
    }

    // التحقق إذا كان يوجد قيمة في الطلب للبحث
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;

        // البحث في اسم المحطة وكود المحطة واسم المنهل
        $query->where(function ($q) use ($searchTerm) {
            $q->whereHas('station', function ($q) use ($searchTerm) {
                $q->where('station_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('station_code', 'like', '%' . $searchTerm . '%'); // البحث في كود المحطة
            })
            ->orWhere('manhole_name', 'like', '%' . $searchTerm . '%'); // البحث في اسم المنهل
        });
    }

    // جلب البيانات مع المحطات، الوحدات، والبلدات
    $manholes = $query->with(['station', 'unit', 'town'])->paginate(10000); // استخدام الترقيم لعرض البيانات

    // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
    return view('dashboard.manholes.index', compact('manholes', 'units', 'selectedUnitId'));
}


    public function export()
    {
        return Excel::download(new ManholesExport, 'manholes.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new ManholesImport, $request->file('file'));

        return redirect()->route('dashboard.manholes.index')->with('success', 'تم استيراد المنهولات بنجاح.');
    }

    /**
     * عرض نموذج إنشاء منهل جديد
     */
    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;

        // إذا كانت هناك وحدة، جلب المحطات عبر البلدات المرتبطة بالوحدة
        if ($unit) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = $unit->towns;

            // جلب المحطات بناءً على البلدات المرتبطة بالوحدة
            $stations = \App\Models\Station::whereIn('town_id', $towns->pluck('id'))->get();
        } else {
            // إذا لم تكن هناك وحدة، جلب جميع المحطات
            $stations = \App\Models\Station::all();
            $towns = Town::all(); // إذا لم يكن هناك وحدة، عرض جميع البلدات
        }

        // إرسال المحطات، الوحدات، والبلدات إلى العرض
        return view('dashboard.manholes.create', compact('stations', 'unit', 'towns'));
    }



    /**
     * تخزين منهل جديد
     */
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'unit_id' => 'required|exists:units,id',
            'town_id' => 'required|exists:towns,id',
            'manhole_name' => 'required|string|max:255',
            'status' => 'required|in:يعمل,متوقف',
            'stop_reason' => 'nullable|string',
            'has_flow_meter' => 'required|boolean',
            'chassis_number' => 'nullable|string|max:255',
            'meter_diameter' => 'nullable|numeric',
            'meter_status' => 'nullable|in:يعمل,متوقف',
            'meter_operation_method_in_meter' => 'nullable|string|max:255',
            'has_storage_tank' => 'required|boolean',
            'tank_capacity' => 'nullable|numeric',
            'general_notes' => 'nullable|string',
        ]);

        // إنشاء المنهل الجديد في قاعدة البيانات
        Manhole::create($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('dashboard.manholes.index')->with('success', 'تمت إضافة المنهل بنجاح.');
    }

        /**
     * عرض تفاصيل منهل معين
     */
    public function show(Manhole $manhole)
    {
        return view('dashboard.manholes.show', compact('manhole'));
    }

    /**
     * عرض نموذج تعديل منهل
     */
    public function edit(Manhole $manhole)
    {
        // جلب جميع المحطات، الوحدات، والبلدات
        $stations = Station::all();
        $units = Unit::all();
        $towns = Town::all();
        return view('dashboard.manholes.edit', compact('manhole', 'stations', 'units', 'towns'));
    }

    /**
     * تحديث بيانات المنهل
     */
    public function update(Request $request, Manhole $manhole)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'unit_id' => 'required|exists:units,id',
            'town_id' => 'required|exists:towns,id',
            'manhole_name' => 'required|string|max:255',
            'status' => 'required|in:يعمل,متوقف',
            'stop_reason' => 'nullable|string',
            'has_flow_meter' => 'required|boolean',
            'chassis_number' => 'nullable|string|max:255',
            'meter_diameter' => 'nullable|numeric',
            'meter_status' => 'nullable|in:يعمل,متوقف',
            'meter_operation_method_in_meter' => 'nullable|string|max:255',
            'has_storage_tank' => 'required|boolean',
            'tank_capacity' => 'nullable|numeric',
            'general_notes' => 'nullable|string',
        ]);

        // تحديث بيانات المنهل في قاعدة البيانات
        $manhole->update($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('dashboard.manholes.index')->with('success', 'تم تحديث المنهل بنجاح.');
    }

    /**
     * حذف منهل معين
     */
    public function destroy(Manhole $manhole)
    {
        // حذف المنهل من قاعدة البيانات
        $manhole->delete();

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('dashboard.manholes.index')->with('success', 'تم حذف المنهل بنجاح.');
    }
}
