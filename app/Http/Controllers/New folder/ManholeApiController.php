<?php

namespace App\Http\Controllers\Api;

use App\Exports\ManholesExport;
use App\Http\Controllers\Controller;
use App\Imports\ManholesImport;
use App\Models\Manhole;
use App\Models\Station;
use App\Models\Unit;
use App\Models\Town;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ManholeApiController extends Controller
{
    /**
     * عرض جميع المنهلات مع الفلترة.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = $user->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لجلب المنهلات المرتبطة بالمحطات
        $query = Manhole::query();

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تقييد النتائج للمستخدمين غير الإداريين بحيث تُسترجع المنهلات الخاصة بمحطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }

        // جلب البيانات مع العلاقات وترقيم النتائج
        $manholes = $query->with(['station', 'unit', 'town'])->paginate(10000);

        return response()->json([
            'manholes' => $manholes,
            'units' => $units,
            'selectedUnitId' => $selectedUnitId
        ]);
    }

    /**
     * تصدير المنهلات إلى ملف Excel.
     */
    public function export()
    {
        return Excel::download(new ManholesExport, 'manholes.xlsx');
    }
    
    /**
     * استيراد المنهلات من ملف Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ManholesImport, $request->file('file'));

        return response()->json([
            'message' => 'تم استيراد المنهولات بنجاح.'
        ]);
    }

    /**
     * عرض نموذج إنشاء منهل جديد.
     */
    public function create()
    {
        $user = auth()->user();
        // للمستخدمين غير الإداريين جلب محطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
            // جلب البلدات المرتبطة بالمحطة
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
            'towns' => $towns,
            'unit' => $user->role_id != 'admin' ? $user->unit : null
        ]);
    }

    /**
     * تخزين منهل جديد.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'    => 'required|exists:stations,id',
                'unit_id'       => 'required|exists:units,id',
                'town_id'       => 'required|exists:towns,id',
                'manhole_name'  => 'required|string|max:255',
                'status'        => 'required|in:يعمل,متوقف',
                'stop_reason'   => 'nullable|string',
                'has_flow_meter'=> 'required|boolean',
                'chassis_number'=> 'nullable|string|max:255',
                'meter_diameter'=> 'nullable|numeric',
                'meter_status'  => 'nullable|in:يعمل,متوقف',
                'meter_operation_method_in_meter' => 'nullable|string|max:255',
                'has_storage_tank' => 'required|boolean',
                'tank_capacity' => 'nullable|numeric',
                'general_notes' => 'nullable|string',
            ]);
        } else {
            // للمستخدمين غير الإداريين نتجاهل القيم الواردة ونفرض قيم محطة المستخدم
            $validated = $request->validate([
                'manhole_name'  => 'required|string|max:255',
                'status'        => 'required|in:يعمل,متوقف',
                'stop_reason'   => 'nullable|string',
                'has_flow_meter'=> 'required|boolean',
                'chassis_number'=> 'nullable|string|max:255',
                'meter_diameter'=> 'nullable|numeric',
                'meter_status'  => 'nullable|in:يعمل,متوقف',
                'meter_operation_method_in_meter' => 'nullable|string|max:255',
                'has_storage_tank' => 'required|boolean',
                'tank_capacity' => 'nullable|numeric',
                'general_notes' => 'nullable|string',
            ]);
            // فرض station_id من بيانات المستخدم
            $validated['station_id'] = $user->station_id;
            // تحديد وحدة المستخدم
            $validated['unit_id'] = $user->unit_id;
            // جلب المحطة لتحديد البلد المرتبط بها
            $station = Station::findOrFail($user->station_id);
            $validated['town_id'] = $station->town_id;
        }

        $manhole = Manhole::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المنهل بنجاح.',
            'manhole' => $manhole
        ]);
    }

    /**
     * عرض تفاصيل منهل معين.
     */
    public function show(Manhole $manhole)
    {
        $user = auth()->user();
        // التحقق من انتماء المنهل لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $manhole->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا المنهل'], 403);
        }
        return response()->json($manhole);
    }

    /**
     * عرض نموذج تعديل منهل.
     */
    public function edit(Manhole $manhole)
    {
        $user = auth()->user();
        // التحقق من انتماء المنهل لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $manhole->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا المنهل'], 403);
        }
        
        // جلب جميع المحطات، الوحدات، والبلدات؛ وإذا كان المستخدم غير إداري، جلب محطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
            $units = Unit::where('id', $user->unit_id)->get();
            $station = $stations->first();
            $towns = $station ? Town::where('id', $station->town_id)->get() : collect([]);
        } else {
            $stations = Station::all();
            $units = Unit::all();
            $towns = Town::all();
        }
    
        return response()->json([
            'manhole' => $manhole,
            'stations' => $stations,
            'units' => $units,
            'towns' => $towns
        ]);
    }

    /**
     * تحديث بيانات المنهل.
     */
    public function update(Request $request, Manhole $manhole)
    {
        $user = auth()->user();
        // التحقق من انتماء المنهل لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $manhole->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث هذا المنهل'], 403);
        }

        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'    => 'required|exists:stations,id',
                'unit_id'       => 'required|exists:units,id',
                'town_id'       => 'required|exists:towns,id',
                'manhole_name'  => 'required|string|max:255',
                'status'        => 'required|in:يعمل,متوقف',
                'stop_reason'   => 'nullable|string',
                'has_flow_meter'=> 'required|boolean',
                'chassis_number'=> 'nullable|string|max:255',
                'meter_diameter'=> 'nullable|numeric',
                'meter_status'  => 'nullable|in:يعمل,متوقف',
                'meter_operation_method_in_meter' => 'nullable|string|max:255',
                'has_storage_tank' => 'required|boolean',
                'tank_capacity' => 'nullable|numeric',
                'general_notes' => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'manhole_name'  => 'required|string|max:255',
                'status'        => 'required|in:يعمل,متوقف',
                'stop_reason'   => 'nullable|string',
                'has_flow_meter'=> 'required|boolean',
                'chassis_number'=> 'nullable|string|max:255',
                'meter_diameter'=> 'nullable|numeric',
                'meter_status'  => 'nullable|in:يعمل,متوقف',
                'meter_operation_method_in_meter' => 'nullable|string|max:255',
                'has_storage_tank' => 'required|boolean',
                'tank_capacity' => 'nullable|numeric',
                'general_notes' => 'nullable|string',
            ]);
            // فرض قيم محطة المستخدم
            $validated['station_id'] = $user->station_id;
            $validated['unit_id'] = $user->unit_id;
            $station = Station::findOrFail($user->station_id);
            $validated['town_id'] = $station->town_id;
        }

        $manhole->update($validated);

        return response()->json([
            'message' => 'تم تحديث المنهل بنجاح.',
            'manhole' => $manhole
        ]);
    }

    /**
     * حذف منهل معين.
     */
    public function destroy(Manhole $manhole)
    {
        $user = auth()->user();
        // التحقق من انتماء المنهل لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $manhole->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا المنهل'], 403);
        }

        $manhole->delete();

        return response()->json([
            'message' => 'تم حذف المنهل بنجاح.'
        ]);
    }
}
