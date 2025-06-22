<?php

namespace App\Http\Controllers;

use App\Exports\PrivateWellsExport;
use App\Imports\PrivateWellsImport;
use App\Models\PrivateWell;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PrivateWellController extends Controller
{
    /**
     * عرض قائمة الآبار.
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;
    
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // استعلام لجلب الآبار الخاصة بالقطاع
        $query = PrivateWell::with('station'); // إضافة المحطات مباشرة
    
        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
        // تصفية الآبار بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }
    
        // التحقق إذا كان يوجد قيمة في الطلب للبحث
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة
    
            // البحث في اسم المحطة وكود المحطة واسم البئر
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('station', function ($q) use ($searchTerm) {
                    $q->where('station_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('station_code', 'like', '%' . $searchTerm . '%'); // البحث في كود المحطة
                })
                ->orWhere('well_name', 'like', '%' . $searchTerm . '%'); // البحث في اسم البئر
            });
        }
    
        // الحصول على البيانات مع المحطات
        $wells = $query->paginate(10000); // استخدام الترقيم لعرض البيانات
    
        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('private-wells.index', compact('wells', 'units', 'selectedUnitId'));
    }
    
    
    public function export()
    {
        return Excel::download(new PrivateWellsExport, 'private_wells.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new PrivateWellsImport, $request->file('file'));

        return redirect()->route('private-wells.index')->with('success', 'تم استيراد الآبار الخاصة بنجاح.');
    }

    /**
     * عرض صفحة إنشاء بئر جديد.
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
        return view('private-wells.create', compact('stations'));
    }
    
    /**
     * حفظ بئر جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'well_name' => 'required|string|max:255',
            'well_count' => 'required|integer|min:1',
            'distance_from_nearest_well' => 'required|numeric|min:0',
            'well_type' =>'required|string|max:255',
        ]);

        PrivateWell::create($validated);

        return redirect()->route('private-wells.index')->with('success', 'تمت إضافة البئر بنجاح.');
    }

    /**
     * عرض تفاصيل بئر معين.
     */
    public function show(PrivateWell $privateWell)
    {
        return view('private-wells.show', compact('privateWell'));
    }

    /**
     * عرض صفحة تعديل بئر.
     */
    public function edit(PrivateWell $privateWell)
    {
        $stations = Station::all();
        return view('private-wells.edit', compact('privateWell', 'stations'));
    }

    /**
     * تحديث بيانات بئر في قاعدة البيانات.
     */
    public function update(Request $request, PrivateWell $privateWell)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'well_name' => 'required|string|max:255',
            'well_count' => 'required|integer|min:1',
            'distance_from_nearest_well' => 'required|numeric|min:0',
            'well_type' => 'required|string|max:255',
        ]);

        $privateWell->update($validated);

        return redirect()->route('private-wells.index')->with('success', 'تم تحديث بيانات البئر بنجاح.');
    }

    /**
     * حذف بئر من قاعدة البيانات.
     */
    public function destroy(PrivateWell $privateWell)
    {
        $privateWell->delete();
        return redirect()->route('private-wells.index')->with('success', 'تم حذف البئر بنجاح.');
    }
}
