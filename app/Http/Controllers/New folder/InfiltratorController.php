<?php

namespace App\Http\Controllers\Api;

use App\Exports\InfiltratorsExport;
use App\Http\Controllers\Controller;
use App\Imports\InfiltratorsImport;
use App\Models\Infiltrator;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InfiltratorController extends Controller
{
    /**
     * عرض جميع الانفلترات مع الفلترة والتقييد بناءً على محطة المستخدم.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = $user->unit_id;
    
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // استعلام لجلب الانفلترات مع تحميل المحطات
        $query = Infiltrator::with('station');
    
        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
      
    
        // تقييد النتائج إذا لم يكن المستخدم إداريًا بحيث يتم جلب الانفلترات الخاصة بمحطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }
    
        // جلب البيانات بعد التصفية مع الترقيم
        $infiltrators = $query->paginate(10000);
    
        return response()->json([
            'infiltrators'    => $infiltrators,
            'units'           => $units,
            'selectedUnitId'  => $selectedUnitId,
        ]);
    }
    
    public function export()
    {
        return Excel::download(new InfiltratorsExport, 'infiltrators.xlsx');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        Excel::import(new InfiltratorsImport, $request->file('file'));
    
        return response()->json([
            'message' => 'تم استيراد الإنفلترات بنجاح.',
        ]);
    }
    
    /**
     * عرض نموذج إنشاء انفلتر جديد.
     */
    public function create()
    {
        $user = auth()->user();
        // إذا كان المستخدم غير إداري، يتم جلب محطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            // إذا كان المستخدم إداري، جلب المحطات بناءً على وحدة المستخدم إن وجدت أو جميعها
            $unit = $user->unit;
            if ($unit) {
                $towns = $unit->towns;
                $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
            }
        }
    
        return response()->json([
            'stations' => $stations,
        ]);
    }
    
    /**
     * تخزين انفلتر جديد.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'            => 'required|exists:stations,id',
                'infiltrator_capacity'  => 'required|numeric',
                'readiness_status'      => 'required|numeric',
                'infiltrator_type'      => 'required|string|max:255',
                'notes'                 => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'infiltrator_capacity'  => 'required|numeric',
                'readiness_status'      => 'required|numeric',
                'infiltrator_type'      => 'required|string|max:255',
                'notes'                 => 'nullable|string',
            ]);
            // تعيين محطة المستخدم تلقائيًا للمستخدم غير الإداري
            $validated['station_id'] = $user->station_id;
        }
    
        Infiltrator::create($validated);
    
        return response()->json([
            'message' => 'تمت إضافة الانفلتر بنجاح.',
        ]);
    }
    
    /**
     * عرض تفاصيل انفلتر معين.
     */
    public function show(Infiltrator $infiltrator)
    {
        $user = auth()->user();
        // التحقق من انتماء الانفلتر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $infiltrator->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا الانفلتر'], 403);
        }
    
        return response()->json([
            'infiltrator' => $infiltrator,
        ]);
    }
    
    /**
     * عرض نموذج تعديل انفلتر.
     */
    public function edit(Infiltrator $infiltrator)
    {
        $user = auth()->user();
        // التحقق من انتماء الانفلتر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $infiltrator->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا الانفلتر'], 403);
        }
    
        // جلب جميع المحطات؛ وإذا كان المستخدم غير إداري، يتم جلب محطة المستخدم فقط
        $stations = $user->role_id != 'admin'
            ? Station::where('id', $user->station_id)->get()
            : Station::all();
    
        return response()->json([
            'infiltrator' => $infiltrator,
            'stations'    => $stations,
        ]);
    }
    
    /**
     * تحديث بيانات الانفلتر.
     */
    public function update(Request $request, Infiltrator $infiltrator)
    {
        $user = auth()->user();
        // التحقق من انتماء الانفلتر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $infiltrator->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث هذا الانفلتر'], 403);
        }
    
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'            => 'required|exists:stations,id',
                'infiltrator_capacity'  => 'required|numeric',
                'readiness_status'      => 'required|numeric',
                'infiltrator_type'      => 'required|string|max:255',
                'notes'                 => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'infiltrator_capacity'  => 'required|numeric',
                'readiness_status'      => 'required|numeric',
                'infiltrator_type'      => 'required|string|max:255',
                'notes'                 => 'nullable|string',
            ]);
            $validated['station_id'] = $user->station_id;
        }
    
        $infiltrator->update($validated);
    
        return response()->json([
            'message' => 'تم تحديث الانفلتر بنجاح.',
        ]);
    }
    
    /**
     * حذف انفلتر معين.
     */
    public function destroy(Infiltrator $infiltrator)
    {
        $user = auth()->user();
        // التحقق من انتماء الانفلتر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $infiltrator->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا الانفلتر'], 403);
        }
    
        $infiltrator->delete();
    
        return response()->json([
            'message' => 'تم حذف الانفلتر بنجاح.',
        ]);
    }
}
