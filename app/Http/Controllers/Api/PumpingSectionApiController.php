<?php

namespace App\Http\Controllers\Api;

use App\Exports\PumpingSectorsExport;
use App\Http\Controllers\Controller;
use App\Imports\PumpingSectorsImport;
use App\Models\PumpingSector;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PumpingSectionApiController extends Controller   
{
    /**
     * عرض قائمة الأقسام مع الفلترة.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = $user->unit_id;
        
        // استعلام لجلب قطاع الضخ مع المحطات
        $query = PumpingSector::query();
    
        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
      
    
        // تقييد النتائج للمستخدمين غير الإداريين بحيث تُسترجع الأقسام الخاصة بمحطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }
    
        // جلب البيانات بعد التصفية مع العلاقات وترقيم النتائج
        $PumpingSectors = $query->with(['station', 'station.town'])->paginate(10000);
    
        return response()->json([
            'PumpingSectors' => $PumpingSectors,
            'units' => $units,
            'selectedUnitId' => $selectedUnitId
        ]);
    }
    
    public function export()
    {
        return Excel::download(new PumpingSectorsExport, 'pumping_sectors.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        Excel::import(new PumpingSectorsImport, $request->file('file'));
    
        return response()->json(['message' => 'تم استيراد قطاعات الضخ بنجاح.']);
    }

    /**
     * عرض نموذج إنشاء قسم جديد.
     */
    public function create()
    {
        $user = auth()->user();
        // للمستخدمين غير الإداريين جلب محطة المستخدم والبلدات المرتبطة بها
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
            $station = $stations->first();
            $towns = $station ? Town::where('id', $station->town_id)->get() : collect([]);
        } else {
            // للمستخدم الإداري: جلب المحطات والبلدات بناءً على الوحدة أو الكل
            $unit = $user->unit;
            if ($unit) {
                $towns = $unit->towns;
                $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
                $towns = Town::all();
            }
        }
    
        return response()->json([
            'stations' => $stations,
            'towns' => $towns
        ]);
    }

    /**
     * تخزين قسم جديد.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'  => 'required|exists:stations,id',
                'sector_name' => 'required|string|max:255',
                'town_id'     => 'required|exists:towns,id',
                'notes'       => 'nullable|string',
            ]);
        } else {
            // للمستخدمين غير الإداريين يتم تجاهل القيم الواردة ويتم فرضها من بيانات المستخدم
            $validated = $request->validate([
                'sector_name' => 'required|string|max:255',
                'notes'       => 'nullable|string',
            ]);
            $validated['station_id'] = $user->station_id;
            // جلب المحطة لتحديد البلد المرتبط بها
            $station = Station::findOrFail($user->station_id);
            $validated['town_id'] = $station->town_id;
        }
    
        PumpingSector::create($validated);
    
        return response()->json(['message' => 'تمت إضافة القسم بنجاح.']);
    }

    /**
     * عرض تفاصيل قسم معين.
     */
    public function show(PumpingSector $PumpingSector)
    {
        $user = auth()->user();
        // التحقق من انتماء القسم لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $PumpingSector->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا القسم'], 403);
        }
    
        return response()->json(['PumpingSector' => $PumpingSector]);
    }

    /**
     * عرض نموذج تعديل قسم.
     */
    public function edit(PumpingSector $PumpingSector)
    {
        $user = auth()->user();
        // التحقق من انتماء القسم لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $PumpingSector->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا القسم'], 403);
        }
    
        // للمستخدمين غير الإداريين جلب محطة المستخدم والبلد المرتبط بها، وإلا جميع المحطات والبلدات
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
            $station = $stations->first();
            $towns = $station ? Town::where('id', $station->town_id)->get() : collect([]);
        } else {
            $stations = Station::all();
            $towns = Town::all();
        }
    
        return response()->json([
            'PumpingSector' => $PumpingSector,
            'stations' => $stations,
            'towns' => $towns
        ]);
    }

    /**
     * تحديث بيانات القسم.
     */
    public function update(Request $request, PumpingSector $PumpingSector)
    {
        $user = auth()->user();
        // التحقق من انتماء القسم لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $PumpingSector->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث هذا القسم'], 403);
        }
    
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'  => 'required|exists:stations,id',
                'sector_name' => 'required|string|max:255',
                'town_id'     => 'required|exists:towns,id',
                'notes'       => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'sector_name' => 'required|string|max:255',
                'notes'       => 'nullable|string',
            ]);
            // فرض قيم محطة المستخدم
            $validated['station_id'] = $user->station_id;
            $station = Station::findOrFail($user->station_id);
            $validated['town_id'] = $station->town_id;
        }
    
        $PumpingSector->update($validated);
    
        return response()->json(['message' => 'تم تحديث القسم بنجاح.']);
    }

    /**
     * حذف قسم معين.
     */
    public function destroy(PumpingSector $PumpingSector)
    {
        $user = auth()->user();
        // التحقق من انتماء القسم لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $PumpingSector->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا القسم'], 403);
        }
    
        $PumpingSector->delete();
    
        return response()->json(['message' => 'تم حذف القسم بنجاح.']);
    }
}
