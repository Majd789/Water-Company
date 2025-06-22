<?php

namespace App\Http\Controllers;

use App\Exports\FiltersExport;
use App\Imports\FiltersImport;
use App\Models\Filter;  // استيراد نموذج Filter
use App\Models\Station; // استيراد نموذج Station
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FilterController extends Controller
{
    /**
     * عرض جميع المرشحات
     */
    public function index(Request $request)
    {
        // استرجاع جميع الوحدات
        $units = Unit::all();
    
        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = auth()->user()->unit_id;
    
        // إنشاء استعلام لجلب المرشحات
        $query = Filter::with('station'); // تحميل المحطات مباشرة لتقليل الاستعلامات
    
        // التحقق مما إذا كان المستخدم لديه وحدة مرتبطة أو تم اختيار وحدة من الطلب
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
        if (!empty($selectedUnitId)) {
            // تصفية المرشحات بناءً على الوحدة المحددة
            $query->whereHas('station.town', function ($q) use ($selectedUnitId) {
                $q->where('unit_id', $selectedUnitId); // التحقق من ارتباط البلدة بالوحدة
            });
        }
    
        // تصفية المرشحات بناءً على البلدة المحددة
        if ($request->has('town_id') && $request->town_id != '') {
            $query->whereHas('station', function ($q) use ($request) {
                $q->where('town_id', $request->town_id);
            });
        }
    
        // البحث النصي (اسم المحطة أو كود المحطة)
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة
    
            $query->whereHas('station', function ($q) use ($searchTerm) {
                $q->where('station_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('station_code', 'like', '%' . $searchTerm . '%'); // البحث في كود المحطة
            });
        }
    
        // الحصول على البيانات مع التصفية والصفحات
        $filters = $query->paginate(1000); // استخدام الترقيم لعرض البيانات
    
        // تمرير البيانات إلى العرض
        return view('filters.index', compact('filters', 'units'));
    }
    
    
    
    public function export()
    {
        return Excel::download(new FiltersExport, 'filters.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new FiltersImport, $request->file('file'));

        return redirect()->route('filters.index')->with('success', 'تم استيراد المرشحات بنجاح.');
    }

    /**
     * عرض نموذج إنشاء مرشح جديد
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
        return view('filters.create', compact('stations'));
    }
    
    /**
     * تخزين مرشح جديد
     */
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id', // التحقق من وجود المحطة
            'filter_capacity' => 'required|numeric', // استطاعة المرشح
            'readiness_status' => 'required|numeric', // حالة الجاهزية
            'filter_type' => 'required|string|max:255', // نوع المرشح
        ]);

        // إنشاء المرشح الجديد في قاعدة البيانات
        Filter::create($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('filters.index')->with('success', 'تمت إضافة المرشح بنجاح.');
    }

    /**
     * عرض تفاصيل مرشح معين
     */
    public function show(Filter $filter)
    {
        return view('filters.show', compact('filter'));
    }

    /**
     * عرض نموذج تعديل مرشح
     */
    public function edit(Filter $filter)
    {
        // جلب جميع المحطات
        $stations = Station::all();
        return view('filters.edit', compact('filter', 'stations'));
    }

    /**
     * تحديث بيانات المرشح
     */
    public function update(Request $request, Filter $filter)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id', // التحقق من وجود المحطة
            'filter_capacity' => 'required|numeric', // استطاعة المرشح
            'readiness_status' => 'required|numeric', // حالة الجاهزية
            'filter_type' => 'required|string|max:255', // نوع المرشح
        ]);

        // تحديث بيانات المرشح في قاعدة البيانات
        $filter->update($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('filters.index')->with('success', 'تم تحديث المرشح بنجاح.');
    }

    /**
     * حذف مرشح معين
     */
    public function destroy(Filter $filter)
    {
        // حذف المرشح من قاعدة البيانات
        $filter->delete();

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('filters.index')->with('success', 'تم حذف المرشح بنجاح.');
    }
}
