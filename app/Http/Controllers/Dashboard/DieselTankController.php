<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Exports\DieselTanksExport;
use App\Imports\DieselTanksImport;
use App\Models\DieselTank;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DieselTankController extends Controller
{
      public function __construct()
    {
        $this->middleware('permission:diesel_tanks.view')->only(['index', 'show']);
        $this->middleware('permission:diesel_tanks.create')->only(['create', 'store']);
        $this->middleware('permission:diesel_tanks.edit')->only(['edit', 'update']);
        $this->middleware('permission:diesel_tanks.delete')->only('destroy');
    }
    /**
     * عرض جميع خزانات الديزل.
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لعرض جميع الخزانات مع المحطات
        $dieselTanks = DieselTank::with('station');

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية النتائج بناءً على المحطات المرتبطة بالبلدات الخاصة بوحدة المستخدم
        if (!empty($selectedUnitId)) {
            $dieselTanks->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // إذا كان هناك نص بحث في الحقل الموحد
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            $dieselTanks = $dieselTanks->where(function ($query) use ($searchTerm) {
                $query->where('tank_name', 'like', '%' . $searchTerm . '%') // البحث عن اسم الخزان
                      ->orWhereHas('station', function ($q) use ($searchTerm) {
                          $q->where('station_name', 'like', '%' . $searchTerm . '%'); // أو البحث عن اسم المحطة
                      });
            });
        }

        // إرجاع النتائج مع التصفية والصفحات
        $dieselTanks = $dieselTanks->paginate(50000);

        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('dashboard.diesel_tanks.index', compact('dieselTanks', 'units', 'selectedUnitId'));
    }


    public function export()
    {
        return Excel::download(new DieselTanksExport, 'diesel_tanks.xlsx');
    }

    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    // استيراد البيانات من الملف
    Excel::import(new DieselTanksImport, $request->file('file'));

    return redirect()->route('dashboard.diesel_tanks.index')->with('success', 'تم استيراد البيانات بنجاح');
    }

    /**
     * عرض نموذج إنشاء خزان ديزل جديد.
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
        return view('dashboard.diesel_tanks.create', compact('stations'));
    }


    /**
     * تخزين خزان ديزل جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string|max:255',

            'general_notes' => 'nullable|string',
        ]);

        DieselTank::create($request->all());

        return redirect()->route('dashboard.diesel_tanks.index')->with('success', 'تمت إضافة خزان الديزل بنجاح.');
    }

    /**
     * عرض تفاصيل خزان ديزل معين.
     */
    public function show(DieselTank $dieselTank)
    {
        return view('dashboard.diesel_tanks.show', compact('dieselTank'));
    }

    /**
     * عرض نموذج تعديل بيانات خزان ديزل.
     */
    public function edit(DieselTank $dieselTank)
    {
        $stations = Station::all(); // جلب جميع المحطات
        return view('dashboard.diesel_tanks.edit', compact('dieselTank', 'stations'));
    }

    /**
     * تحديث بيانات خزان ديزل.
     */
    public function update(Request $request, DieselTank $dieselTank)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string|max:255',
            'general_notes' => 'nullable|string',
        ]);

        $dieselTank->update($request->all());

        return redirect()->route('dashboard.diesel_tanks.index')->with('success', 'تم تحديث بيانات خزان الديزل بنجاح.');
    }

    /**
     * حذف خزان ديزل معين.
     */
    public function destroy(DieselTank $dieselTank)
    {
        $dieselTank->delete();

        return redirect()->route('dashboard.diesel_tanks.index')->with('success', 'تم حذف خزان الديزل بنجاح.');
    }
}
