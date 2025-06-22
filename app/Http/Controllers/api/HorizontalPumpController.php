<?php

namespace App\Http\Controllers\API;

use App\Exports\HorizontalPumpsExport;
use App\Imports\HorizontalPumpsImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HorizontalPump;
use App\Models\Station;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class HorizontalPumpController extends Controller
{
    /**
     * عرض قائمة المضخات مع الفلترة وإرجاعها بصيغة JSON.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // استرجاع جميع الوحدات لخيارات الفلترة
        $units = Unit::all();

        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = $user->unit_id;

        // إنشاء استعلام لجلب المضخات الأفقية مع تحميل المحطات
        $query = HorizontalPump::with('station');




        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }

        // جلب البيانات مع التصفية والصفحات
        $horizontalPumps = $query->paginate(10000);

        return response()->json([
            'horizontalPumps' => $horizontalPumps,
            'units'           => $units,
        ]);
    }

    /**
     * تصدير المضخات الأفقية إلى ملف Excel.
     */
    public function export()
    {
        return Excel::download(new HorizontalPumpsExport, 'horizontal_pumps.xlsx');
    }

    /**
     * استيراد بيانات المضخات الأفقية من ملف Excel.
     */
    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new HorizontalPumpsImport, $request->file('file'));

        return response()->json(['message' => 'تم استيراد المضخات الأفقية بنجاح.']);
    }

    /**
     * عرض بيانات لإنشاء مضخة جديدة (مثلاً بيانات المحطات).
     */
    public function create()
    {
        $user = auth()->user();
        // إذا كان المستخدم غير إداري، يتم جلب محطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            $unit = $user->unit;
            if ($unit) {
                $stations = Station::whereIn('town_id', $unit->towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
            }
        }

        return response()->json(['stations' => $stations]);
    }

    /**
     * حفظ مضخة جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'          => 'required|exists:stations,id',
                'pump_status'         => 'nullable|in:يعمل,متوقفة',
                'pump_name'           => 'nullable|string|max:255',
                'pump_capacity_hp'    => 'nullable|numeric|min:0',
                'pump_flow_rate_m3h'  => 'nullable|numeric|min:0',
                'pump_head'           => 'nullable|numeric|min:0',
                'pump_brand_model'    => 'nullable|string|max:255',
                'technical_condition' => 'nullable|string|max:255',
                'energy_source'       => 'nullable|string|max:255',
                'notes'               => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'pump_status'         => 'nullable|in:يعمل,متوقفة',
                'pump_name'           => 'nullable|string|max:255',
                'pump_capacity_hp'    => 'nullable|numeric|min:0',
                'pump_flow_rate_m3h'  => 'nullable|numeric|min:0',
                'pump_head'           => 'nullable|numeric|min:0',
                'pump_brand_model'    => 'nullable|string|max:255',
                'technical_condition' => 'nullable|string|max:255',
                'energy_source'       => 'nullable|string|max:255',
                'notes'               => 'nullable|string',
            ]);
            // تعيين محطة المستخدم تلقائيًا
            $validated['station_id'] = $user->station_id;
        }

        $horizontalPump = HorizontalPump::create($validated);

        return response()->json([
            'message'        => 'تم إضافة المضخة بنجاح.',
            'horizontalPump' => $horizontalPump,
        ], 201);
    }

    /**
     * عرض تفاصيل مضخة محددة.
     */
    public function show(HorizontalPump $horizontalPump)
    {
        $user = auth()->user();
        // التحقق من انتماء المضخة لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $horizontalPump->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذه المضخة'], 403);
        }
        return response()->json($horizontalPump);
    }

    /**
     * عرض بيانات تحرير مضخة موجودة (المضخة والمحطات).
     */
    public function edit(HorizontalPump $horizontalPump)
    {
        $user = auth()->user();
        // التحقق من انتماء المضخة لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $horizontalPump->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذه المضخة'], 403);
        }
        $stations = $user->role_id != 'admin'
            ? Station::where('id', $user->station_id)->get()
            : Station::all();

        return response()->json([
            'horizontalPump' => $horizontalPump,
            'stations'       => $stations,
        ]);
    }

    /**
     * تحديث بيانات مضخة موجودة.
     */
    public function update(Request $request, HorizontalPump $horizontalPump)
    {
        $user = auth()->user();
        // التحقق من انتماء المضخة لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $horizontalPump->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث بيانات هذه المضخة'], 403);
        }

        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'          => 'required|exists:stations,id',
                'pump_status'         => 'nullable|in:يعمل,متوقفة',
                'pump_name'           => 'nullable|string|max:255',
                'pump_capacity_hp'    => 'nullable|numeric|min:0',
                'pump_flow_rate_m3h'  => 'nullable|numeric|min:0',
                'pump_head'           => 'nullable|numeric|min:0',
                'pump_brand_model'    => 'nullable|string|max:255',
                'technical_condition' => 'nullable|string|max:255',
                'energy_source'       => 'nullable|string|max:255',
                'notes'               => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'pump_status'         => 'nullable|in:يعمل,متوقفة',
                'pump_name'           => 'nullable|string|max:255',
                'pump_capacity_hp'    => 'nullable|numeric|min:0',
                'pump_flow_rate_m3h'  => 'nullable|numeric|min:0',
                'pump_head'           => 'nullable|numeric|min:0',
                'pump_brand_model'    => 'nullable|string|max:255',
                'technical_condition' => 'nullable|string|max:255',
                'energy_source'       => 'nullable|string|max:255',
                'notes'               => 'nullable|string',
            ]);
            // تعيين محطة المستخدم تلقائيًا
            $validated['station_id'] = $user->station_id;
        }

        $horizontalPump->update($validated);

        return response()->json([
            'message'        => 'تم تحديث بيانات المضخة بنجاح.',
            'horizontalPump' => $horizontalPump,
        ]);
    }

    /**
     * حذف مضخة.
     */
    public function destroy(HorizontalPump $horizontalPump)
    {
        $user = auth()->user();
        // التحقق من انتماء المضخة لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $horizontalPump->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذه المضخة'], 403);
        }
        $horizontalPump->delete();

        return response()->json(['message' => 'تم حذف المضخة بنجاح.']);
    }
}
