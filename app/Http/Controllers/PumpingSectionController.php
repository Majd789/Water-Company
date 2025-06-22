<?php

namespace App\Http\Controllers;

use App\Exports\PumpingSectorsExport;
use App\Imports\PumpingSectorsImport;
use App\Models\PumpingSector;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PumpingSectionController extends Controller
{
    /**
     * عرض قائمة الأقسام
     */
    public function index(Request $request)
    {   
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;
        
        // استعلام لجلب قطاع الضخ مع المحطات
        $query = PumpingSector::query();
    
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
    
        // جلب البيانات بعد التصفية
        $PumpingSectors = $query->with(['station', 'station.town'])->paginate(10000); // استخدام الترقيم
    
        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('pumping-sectors.index', compact('PumpingSectors', 'units', 'selectedUnitId'));
    }
    
    public function export()
    {
        return Excel::download(new PumpingSectorsExport, 'pumping_sectors.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new PumpingSectorsImport, $request->file('file'));

        return redirect()->route('pumping-sectors.index')->with('success', 'تم استيراد قطاعات الضخ بنجاح.');
    }
    /**
     * عرض نموذج إنشاء قسم جديد
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
            // إذا لم تكن هناك وحدة، جلب جميع المحطات والبلدات
            $stations = \App\Models\Station::all();
            $towns = \App\Models\Town::all();
        }
    
        // إرسال المحطات والبلدات إلى العرض
        return view('pumping-sectors.create', compact('stations', 'towns'));
    }
    
    /**
     * تخزين قسم جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'sector_name' => 'required|string|max:255',
            'town_id' => 'required|exists:towns,id',
            'notes' => 'nullable|string',
        ]);

        PumpingSector::create($request->all());

        return redirect()->route('pumping-sectors.index')->with('success', 'تمت إضافة القسم بنجاح.');
    }

    /**
     * عرض تفاصيل قسم معين
     */
    public function show(PumpingSector $PumpingSector)
    {
        return view('pumping-sectors.show', compact('PumpingSector'));
    }

    /**
     * عرض نموذج تعديل قسم
     */
    public function edit(PumpingSector $PumpingSector)
    {
        $stations = Station::all();
        $towns = Town::all();
        return view('pumping-sectors.edit', compact('PumpingSector', 'stations', 'towns'));
    }

    /**
     * تحديث بيانات القسم
     */
    public function update(Request $request, PumpingSector $PumpingSector)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'sector_name' => 'required|string|max:255',
            'town_id' => 'required|exists:towns,id',
            'notes' => 'nullable|string',
        ]);

        $PumpingSector->update($request->all());

        return redirect()->route('pumping-sectors.index')->with('success', 'تم تحديث القسم بنجاح.');
    }

    /**
     * حذف قسم معين
     */
    public function destroy(PumpingSector $PumpingSector)
    {
        $PumpingSector->delete();

        return redirect()->route('pumping-sectors.index')->with('success', 'تم حذف القسم بنجاح.');
    }
}
