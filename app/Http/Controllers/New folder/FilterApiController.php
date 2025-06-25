<?php

namespace App\Http\Controllers\Api;

use App\Exports\FiltersExport;
use App\Imports\FiltersImport;
use App\Models\Filter;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class FilterApiController extends Controller
{
    /**
     * عرض جميع المرشحات مع الفلترة وإرجاعها بصيغة JSON.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // استرجاع جميع الوحدات
        $units = Unit::all();
    
        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = $user->unit_id;
    
        // إنشاء استعلام لجلب المرشحات مع تحميل المحطات
        $query = Filter::with('station');
    

    
     
        // إذا لم يكن المستخدم admin، تقييد النتائج بمحطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }
    
        // جلب البيانات مع التصفية والترقيم
        $filters = $query->paginate(10000);
    
        return response()->json([
            'filters' => $filters,
            'units'   => $units,
        ]);
    }
    
    /**
     * تصدير المرشحات إلى ملف Excel.
     */
    public function export()
    {
        return Excel::download(new FiltersExport, 'filters.xlsx');
    }
    
    /**
     * استيراد بيانات المرشحات من ملف Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        Excel::import(new FiltersImport, $request->file('file'));
    
        return response()->json(['message' => 'تم استيراد المرشحات بنجاح.']);
    }
    
    /**
     * عرض بيانات لإنشاء مرشح جديد (مثلاً بيانات المحطات).
     */
    public function create()
    {
        $user = auth()->user();
    
        // إذا لم يكن المستخدم admin يتم جلب المحطة المرتبطة به فقط
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            $unit = $user->unit;
    
            if ($unit) {
                // جلب البلدات المرتبطة بالوحدة ثم المحطات
                $towns = $unit->towns;
                $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
            }
        }
    
        return response()->json(['stations' => $stations]);
    }
    
    /**
     * تخزين مرشح جديد.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
    
        // إذا كان المستخدم admin يمكنه اختيار المحطة من الطلب، وإلا يتم تعيين محطة المستخدم تلقائياً
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'      => 'required|exists:stations,id',
                'filter_capacity' => 'required|numeric',
                'readiness_status'=> 'required|numeric',
                'filter_type'     => 'required|string|max:255',
            ]);
        } else {
            $validated = $request->validate([
                'filter_capacity' => 'required|numeric',
                'readiness_status'=> 'required|numeric',
                'filter_type'     => 'required|string|max:255',
            ]);
            $validated['station_id'] = $user->station_id;
        }
    
        $filter = Filter::create($validated);
    
        return response()->json([
            'message' => 'تمت إضافة المرشح بنجاح.',
            'filter'  => $filter,
        ], 201);
    }
    
    /**
     * عرض تفاصيل مرشح معين.
     */
    public function show(Filter $filter)
    {
        $user = auth()->user();
        // منع الوصول إذا كان المرشح لا ينتمي لمحطة المستخدم (ما عدا admin)
        if ($user->role_id != 'admin' && $filter->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا المرشح'], 403);
        }
        return response()->json($filter);
    }
    
    /**
     * عرض بيانات تحرير مرشح (المرشح والمحطات).
     */
    public function edit(Filter $filter)
    {
        $user = auth()->user();
        // منع التعديل إذا كان المرشح لا ينتمي لمحطة المستخدم (ما عدا admin)
        if ($user->role_id != 'admin' && $filter->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا المرشح'], 403);
        }
        
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            $stations = Station::all();
        }
    
        return response()->json([
            'filter'   => $filter,
            'stations' => $stations,
        ]);
    }
    
    /**
     * تحديث بيانات المرشح.
     */
    public function update(Request $request, Filter $filter)
    {
        $user = auth()->user();
        // منع التعديل إذا كان المرشح لا ينتمي لمحطة المستخدم (ما عدا admin)
        if ($user->role_id != 'admin' && $filter->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا المرشح'], 403);
        }
    
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'      => 'required|exists:stations,id',
                'filter_capacity' => 'required|numeric',
                'readiness_status'=> 'required|numeric',
                'filter_type'     => 'required|string|max:255',
            ]);
        } else {
            $validated = $request->validate([
                'filter_capacity' => 'required|numeric',
                'readiness_status'=> 'required|numeric',
                'filter_type'     => 'required|string|max:255',
            ]);
            $validated['station_id'] = $user->station_id;
        }
    
        $filter->update($validated);
    
        return response()->json([
            'message' => 'تم تحديث المرشح بنجاح.',
            'filter'  => $filter,
        ]);
    }
    
    /**
     * حذف مرشح معين.
     */
    public function destroy(Filter $filter)
    {
        $user = auth()->user();
        // منع الحذف إذا كان المرشح لا ينتمي لمحطة المستخدم (ما عدا admin)
        if ($user->role_id != 'admin' && $filter->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا المرشح'], 403);
        }
    
        $filter->delete();
    
        return response()->json(['message' => 'تم حذف المرشح بنجاح.']);
    }
}
