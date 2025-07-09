<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\ElectricityHoursExport;
use App\Imports\ElectricityHoursImport;
use App\Models\ElectricityHour;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ElectricityHourController extends Controller
{
    
      public function __construct()
    {
        $this->middleware('permission:electricity_hours.view')->only(['index', 'show']);
        $this->middleware('permission:electricity_hours.create')->only(['create', 'store']);
        $this->middleware('permission:electricity_hours.edit')->only(['edit', 'update']);
        $this->middleware('permission:electricity_hours.delete')->only('destroy');
    }
    /**
     * عرض جميع ساعات الكهرباء
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لجلب ساعات الكهرباء مع المحطات
        $query = ElectricityHour::query();

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية المحطات بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // إضافة البحث إذا كان هناك نص في الطلب
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // تصفية البيانات بناءً على اسم المحطة أو كود المحطة
            $query->whereHas('station', function ($stationQuery) use ($searchTerm) {
                $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                             ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
            });
        }

        // جلب البيانات بعد التصفية مع الترقيم
        $electricityHours = $query->with('station')->paginate(10000);

        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('dashboard.electricity-hours.index', compact('electricityHours', 'units', 'selectedUnitId'));
    }



    public function export()
    {
        return Excel::download(new ElectricityHoursExport, 'electricity_hours.xlsx');
    }
    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new ElectricityHoursImport, $request->file('file'));

        return redirect()->route('dashboard.electricity-hours.index')->with('success', 'تم استيراد ساعات الكهرباء بنجاح.');
    }
    /**
     * عرض نموذج إنشاء ساعة كهرباء جديدة
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
        }

        // إرسال المحطات إلى العرض
        return view('dashboard.electricity-hours.create', compact('stations'));
    }


    /**
     * تخزين ساعة كهرباء جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'electricity_hours' => 'required|integer|min:0',
            'electricity_hour_number' => 'required|string|max:255',
            'meter_type' => 'required|string|max:255',
            'operating_entity' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        ElectricityHour::create($request->all());

        return redirect()->route('dashboard.electricity-hours.index')->with('success', 'تمت إضافة ساعة الكهرباء بنجاح.');
    }

    /**
     * عرض تفاصيل ساعة كهرباء معينة
     */
    public function show(ElectricityHour $electricityHour)
    {
        return view('dashboard.electricity-hours.show', compact('electricityHour'));
    }

    /**
     * عرض نموذج تعديل ساعة كهرباء
     */
    public function edit(ElectricityHour $electricityHour)
    {
        $stations = Station::all();
        return view('dashboard.electricity-hours.edit', compact('electricityHour', 'stations'));
    }

    /**
     * تحديث بيانات ساعة كهرباء
     */
    public function update(Request $request, ElectricityHour $electricityHour)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'electricity_hours' => 'required|integer|min:0',
            'electricity_hour_number' => 'required|string|max:255',
            'meter_type' => 'required|string|max:255',
            'operating_entity' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $electricityHour->update($request->all());

        return redirect()->route('dashboard.electricity-hours.index')->with('success', 'تم تحديث ساعة الكهرباء بنجاح.');
    }

    /**
     * حذف ساعة كهرباء معينة
     */
    public function destroy(ElectricityHour $electricityHour)
    {
        $electricityHour->delete();

        return redirect()->route('dashboard.electricity-hours.index')->with('success', 'تم حذف ساعة الكهرباء بنجاح.');
    }
}
