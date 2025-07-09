<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\ElevatedTanksExport;
use App\Imports\ElevatedTanksImport;
use App\Models\ElevatedTank;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ElevatedTankController extends Controller
{
    
      public function __construct()
    {
        $this->middleware('permission:elevated_tanks.view')->only(['index', 'show']);
        $this->middleware('permission:elevated_tanks.create')->only(['create', 'store']);
        $this->middleware('permission:elevated_tanks.edit')->only(['edit', 'update']);
        $this->middleware('permission:elevated_tanks.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استرجاع وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = auth()->user()->unit_id;

        // إنشاء استعلام لجلب الخزانات المرتفعة
        $query = ElevatedTank::with('station');

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        if (!empty($selectedUnitId)) {
            // تصفية المحطات بناءً على الوحدة المختارة
            $query->whereHas('station.town', function ($q) use ($selectedUnitId) {
                $q->where('unit_id', $selectedUnitId);
            });
        }

        // تصفية الخزانات بناءً على البلدة المختارة
        if ($request->filled('town_id')) {
            $query->whereHas('station', function ($q) use ($request) {
                $q->where('town_id', $request->town_id);
            });
        }

        // البحث باستخدام نص يشمل جميع الحقول ذات الصلة
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);

            $query->where(function ($q) use ($searchTerm) {
                $q->where('tank_name', 'like', '%' . $searchTerm . '%') // البحث باسم الخزان المرتفع
                  ->orWhereHas('station', function ($stationQuery) use ($searchTerm) {
                      $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // جلب البيانات مع التصفية والصفحات
        $elevatedTanks = $query->paginate(10000);

        // تمرير البيانات إلى العرض وتمرير الوحدات للفلترة
        return view('dashboard.elevated-tanks.index', compact('elevatedTanks', 'units'));
    }


        public function export()
    {
        return Excel::download(new ElevatedTanksExport, 'elevated_tanks.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new ElevatedTanksImport, $request->file('file'));

        return redirect()->route('dashboard.elevated-tanks.index')->with('success', 'تم استيراد الخزانات المرتفعة بنجاح.');
    }

    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;

        // إذا كانت هناك وحدة، جلب المحطات عبر البلدات المرتبطة بالوحدة
        if ($unit) {
            // جلب المحطات بناءً على البلدات المرتبطة بالوحدة
            $stations = \App\Models\Station::whereIn('town_id', $unit->towns->pluck('id'))->get();
        } else {
            // إذا لم تكن هناك وحدة، جلب جميع المحطات
            $stations = \App\Models\Station::all();
        }

        // إرسال المحطات إلى العرض
        return view('dashboard.elevated-tanks.create', compact('stations'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'building_entity' => 'required|string|max:255',
            'construction_date' => 'required|in:جديد,قديم', // تحقق من الاختيار بين جديد أو قديم
            'capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'height' => 'required|numeric|min:0',
            'tank_shape' => 'required|in:دائري,مربع',
            'feeding_station' => 'required|string|max:255',
            'town_supply' => 'required|string|max:255',
            'in_pipe_diameter' => 'required|numeric|min:0',
            'out_pipe_diameter' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'precision' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);


        // تخزين الخزان الجديد في قاعدة البيانات
        ElevatedTank::create($request->all());

        return redirect()->route('dashboard.elevated-tanks.index')->with('success', 'تم إضافة الخزان بنجاح');
    }

    public function show(ElevatedTank $elevatedTank)
    {
        // عرض تفاصيل الخزان
        return view('dashboard.elevated-tanks.show', compact('elevatedTank'));
    }

    public function edit(ElevatedTank $elevatedTank)
    {
        // عرض صفحة تعديل الخزان مع المحطات المتاحة
        $stations = Station::all();
        return view('dashboard.elevated-tanks.edit', compact('elevatedTank', 'stations'));
    }

    public function update(Request $request, ElevatedTank $elevatedTank)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'building_entity' => 'required|string|max:255',
            'construction_date' => 'required|in:جديد,قديم', // تحقق من الاختيار بين جديد أو قديم
            'capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'height' => 'required|numeric|min:0',
           'tank_shape' => 'required|in:دائري,مربع',
            'feeding_station' => 'required|string|max:255',
            'town_supply' => 'required|string|max:255',
           'in_pipe_diameter' => 'required|numeric|min:0',
            'out_pipe_diameter' => 'required|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'precision' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);


        // تحديث الخزان في قاعدة البيانات
        $elevatedTank->update($request->all());

        return redirect()->route('dashboard.elevated-tanks.index')->with('success', 'تم تحديث الخزان بنجاح');
    }

    public function destroy(ElevatedTank $elevatedTank)
    {
        // حذف الخزان من قاعدة البيانات
        $elevatedTank->delete();

        return redirect()->route('dashboard.elevated-tanks.index')->with('success', 'تم حذف الخزان بنجاح');
    }
}
