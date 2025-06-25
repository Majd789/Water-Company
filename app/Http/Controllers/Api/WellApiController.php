<?php

namespace App\Http\Controllers\Api;

use App\Exports\WellsExport;
use App\Imports\WellsImport;
use App\Models\Well;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class WellApiController extends Controller
{
    public function index(Request $request)
    {
        // التحقق مما إذا كان المستخدم مسجّل الدخول
        if (!auth()->check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً.'], 401);
        }
    
        $user = auth()->user(); // استرجاع المستخدم الحالي
    
        // التأكد من أن المستخدم لديه `station_id`
        if (!$user->station_id) {
            return response()->json(['message' => 'المستخدم ليس لديه محطة مرتبطة.'], 403);
        }
    
        // استعلام لجلب الآبار التابعة فقط للمحطة الخاصة بالمستخدم
        $wellsQuery = Well::where('station_id', $user->station_id);
    
        // استرجاع الآبار مع المحطات وترقيم النتائج
        $wells = $wellsQuery->with('station')->paginate(50);
    
        return response()->json([
            'data'  => $wells,
        ], 200);
    }
    

    
    public function export()
    {
        return Excel::download(new WellsExport, 'wells.xlsx');
    }
    
    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        // استيراد البيانات
        Excel::import(new WellsImport, $request->file('file'));
    
        return response()->json([
            'message' => 'تم استيراد الآبار بنجاح.'
        ], 200);
    }
    
    public function create()
    {
        $user = auth()->user();
        // إذا كان هناك وحدة للمستخدم
        if ($user->role_id != 'admin' && $user->unit_id) {
            // استرجاع الوحدة المرتبطة بالمستخدم
            $unit = $user->unit;
            
            // الحصول على البلدات التي تتبع الوحدة
            $towns = Town::where('unit_id', $unit->id)->get();
            
            // الحصول على المحطات التي تتبع البلدات
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            // في حالة المستخدم الإداري أو عدم وجود وحدة، عرض جميع المحطات والبلدات
            $stations = Station::all();
            $towns = Town::all();
        }
    
        return response()->json([
            'stations' => $stations,
            'towns'    => $towns
        ], 200);
    }
    
    // حفظ بئر جديد
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            // التحقق من صحة المدخلات للمستخدم الإداري
            $validated = $request->validate([
                'station_id'             => 'required|exists:stations,id',
                'town_code'              => 'required|string|max:255',
                'well_name'              => 'required|string|max:255',
                'well_status'            => 'nullable|in:يعمل,متوقف',
                'stop_reason'            => 'nullable|string',
                'distance_from_station'  => 'nullable|numeric',
                'well_type'              => 'nullable|in:جوفي,سطحي',
                'well_flow'              => 'nullable|numeric',
                'static_depth'           => 'nullable|numeric',
                'dynamic_depth'          => 'nullable|numeric',
                'drilling_depth'         => 'nullable|numeric',
                'well_diameter'          => 'nullable|numeric',
                'pump_installation_depth'=> 'nullable|numeric',
                'pump_capacity'          => 'nullable|numeric',
                'actual_pump_flow'       => 'nullable|numeric',
                'pump_lifting'           => 'nullable|numeric',
                'pump_brand_model'       => 'nullable|string',
                'energy_source'          => 'nullable|string',
                'well_address'           => 'nullable|string',
                'general_notes'          => 'nullable|string',
                'well_location'          => 'nullable|string',
            ]);
        } else {
            // للمستخدمين غير الإداريين: تجاهل station_id المُرسل وفرضه من بيانات المستخدم
            $validated = $request->validate([
                'town_code'              => 'required|string|max:255',
                'well_name'              => 'required|string|max:255',
                'well_status'            => 'nullable|in:يعمل,متوقف',
                'stop_reason'            => 'nullable|string',
                'distance_from_station'  => 'nullable|numeric',
                'well_type'              => 'nullable|in:جوفي,سطحي',
                'well_flow'              => 'nullable|numeric',
                'static_depth'           => 'nullable|numeric',
                'dynamic_depth'          => 'nullable|numeric',
                'drilling_depth'         => 'nullable|numeric',
                'well_diameter'          => 'nullable|numeric',
                'pump_installation_depth'=> 'nullable|numeric',
                'pump_capacity'          => 'nullable|numeric',
                'actual_pump_flow'       => 'nullable|numeric',
                'pump_lifting'           => 'nullable|numeric',
                'pump_brand_model'       => 'nullable|string',
                'energy_source'          => 'nullable|string',
                'well_address'           => 'nullable|string',
                'general_notes'          => 'nullable|string',
                'well_location'          => 'nullable|string',
            ]);
            $validated['station_id'] = $user->station_id;
        }
    
        $well = Well::create($validated);
    
        return response()->json([
            'message' => 'تم إضافة البئر بنجاح',
            'data'    => $well
        ], 201);
    }
    
    // عرض تفاصيل بئر معين
    public function show(Well $well)
    {
        $user = auth()->user();
        // التحقق من انتماء البئر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $well->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا البئر'], 403);
        }
    
        return response()->json([
            'data' => $well
        ], 200);
    }
    
    // تحديث بيانات البئر
    public function update(Request $request, Well $well)
    {
        $user = auth()->user();
        // التحقق من انتماء البئر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $well->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث هذا البئر'], 403);
        }
    
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'             => 'required|exists:stations,id',
                'town_code'              => 'required|string|max:255',
                'well_name'              => 'required|string|max:255',
                'well_status'            => 'nullable|in:يعمل,متوقف',
                'stop_reason'            => 'nullable|string',
                'distance_from_station'  => 'nullable|numeric',
                'well_type'              => 'nullable|in:جوفي,سطحي',
                'well_flow'              => 'nullable|numeric',
                'static_depth'           => 'nullable|numeric',
                'dynamic_depth'          => 'nullable|numeric',
                'drilling_depth'         => 'nullable|numeric',
                'well_diameter'          => 'nullable|numeric',
                'pump_installation_depth'=> 'nullable|numeric',
                'pump_capacity'          => 'nullable|numeric',
                'actual_pump_flow'       => 'nullable|numeric',
                'pump_lifting'           => 'nullable|numeric',
                'pump_brand_model'       => 'nullable|string',
                'energy_source'          => 'nullable|string',
                'well_address'           => 'nullable|string',
                'general_notes'          => 'nullable|string',
                'well_location'          => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'town_code'              => 'required|string|max:255',
                'well_name'              => 'required|string|max:255',
                'well_status'            => 'nullable|in:يعمل,متوقف',
                'stop_reason'            => 'nullable|string',
                'distance_from_station'  => 'nullable|numeric',
                'well_type'              => 'nullable|in:جوفي,سطحي',
                'well_flow'              => 'nullable|numeric',
                'static_depth'           => 'nullable|numeric',
                'dynamic_depth'          => 'nullable|numeric',
                'drilling_depth'         => 'nullable|numeric',
                'well_diameter'          => 'nullable|numeric',
                'pump_installation_depth'=> 'nullable|numeric',
                'pump_capacity'          => 'nullable|numeric',
                'actual_pump_flow'       => 'nullable|numeric',
                'pump_lifting'           => 'nullable|numeric',
                'pump_brand_model'       => 'nullable|string',
                'energy_source'          => 'nullable|string',
                'well_address'           => 'nullable|string',
                'general_notes'          => 'nullable|string',
                'well_location'          => 'nullable|string',
            ]);
            // فرض محطة المستخدم تلقائيًا
            $validated['station_id'] = $user->station_id;
        }
    
        $well->update($validated);
    
        return response()->json([
            'message' => 'تم تحديث بيانات البئر بنجاح',
            'data'    => $well
        ], 200);
    }
    
    // حذف بئر
    public function destroy(Well $well)
    {
        $user = auth()->user();
        // التحقق من انتماء البئر لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $well->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا البئر'], 403);
        }
    
        $well->delete();
    
        return response()->json([
            'message' => 'تم حذف البئر بنجاح'
        ], 200);
    }
}
