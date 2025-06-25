<?php

namespace App\Http\Controllers\API;

use App\Exports\GenerationGroupsExport;
use App\Imports\GenerationGroupsImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GenerationGroup;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class GenerationGroupController extends Controller
{
    /**
     * عرض قائمة مجموعات التوليد مع الفلترة وإرجاعها بصيغة JSON.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // استرجاع جميع الوحدات
        $units = Unit::all();

        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = $user->unit_id;

        // إنشاء استعلام لجلب المجموعات التوليدية
        $query = GenerationGroup::query();
        // تقييد النتائج إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }

        // جلب البيانات مع تحميل المحطات وترقيم النتائج
        $generationGroups = $query->with('station')->paginate(10000);

        return response()->json([
            'generationGroups' => $generationGroups,
            'units'            => $units,
            'towns'            => isset($towns) ? $towns : null,
        ]);
    }

    /**
     * تصدير مجموعات التوليد إلى ملف Excel.
     */
    public function exportGenerationGroups()
    {
        return Excel::download(new GenerationGroupsExport, 'generation_groups.xlsx');
    }

    /**
     * استيراد بيانات مجموعات التوليد من ملف Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new GenerationGroupsImport, $request->file('file'));

        return response()->json(['message' => 'تم استيراد مجموعات التوليد بنجاح.']);
    }

    /**
     * عرض بيانات لإنشاء مجموعة توليد جديدة (مثلاً بيانات المحطات).
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role_id != 'admin') {
            // للمستخدمين غير الإداريين: جلب محطة المستخدم فقط
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            if ($user->unit_id) {
                // للمسؤول: جلب المحطات المرتبطة بالبلدات التابعة للوحدة
                $towns = Town::where('unit_id', $user->unit_id)->get();
                $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
            }
        }

        return response()->json(['stations' => $stations]);
    }

    /**
     * تخزين مجموعة توليد جديدة.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id' => 'required|exists:stations,id',
                'generator_name' => 'required|string|max:255',
                'generation_capacity' => 'required|numeric|min:0',
                'actual_operating_capacity' => 'required|numeric|min:0',
                'generation_group_readiness_percentage' => 'nullable|numeric|min:0|max:100',
                'fuel_consumption' => 'required|numeric|min:0',
                'oil_usage_duration' => 'required|integer|min:0',
                'oil_quantity_for_replacement' => 'required|numeric|min:0',
                'operational_status' => 'required|in:عاملة,متوقفة',
                'stop_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'generator_name' => 'required|string|max:255',
                'generation_capacity' => 'required|numeric|min:0',
                'actual_operating_capacity' => 'required|numeric|min:0',
                'generation_group_readiness_percentage' => 'nullable|numeric|min:0|max:100',
                'fuel_consumption' => 'required|numeric|min:0',
                'oil_usage_duration' => 'required|integer|min:0',
                'oil_quantity_for_replacement' => 'required|numeric|min:0',
                'operational_status' => 'required|in:عاملة,متوقفة',
                'stop_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
            // تعيين محطة المستخدم تلقائياً
            $validated['station_id'] = $user->station_id;
        }

        $generationGroup = GenerationGroup::create($validated);

        return response()->json([
            'message' => 'تم إنشاء مجموعة التوليد بنجاح.',
            'generationGroup' => $generationGroup,
        ], 201);
    }

    /**
     * عرض تفاصيل مجموعة توليد معينة.
     */
    public function show(GenerationGroup $generationGroup)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $generationGroup->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى مجموعة التوليد هذه'], 403);
        }

        return response()->json($generationGroup);
    }

    /**
     * عرض بيانات تحرير مجموعة توليد (المجموعة والمحطات).
     */
    public function edit(GenerationGroup $generationGroup)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $generationGroup->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل مجموعة التوليد هذه'], 403);
        }
        if ($user->role_id != 'admin') {
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            $stations = Station::all();
        }
        return response()->json([
            'generationGroup' => $generationGroup,
            'stations' => $stations,
        ]);
    }

    /**
     * تحديث مجموعة توليد موجودة.
     */
    public function update(Request $request, GenerationGroup $generationGroup)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $generationGroup->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل مجموعة التوليد هذه'], 403);
        }
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id' => 'required|exists:stations,id',
                'generator_name' => 'required|string|max:255',
                'generation_capacity' => 'required|numeric|min:0',
                'actual_operating_capacity' => 'required|numeric|min:0',
                'generation_group_readiness_percentage' => 'nullable|numeric|min:0|max:100',
                'fuel_consumption' => 'required|numeric|min:0',
                'oil_usage_duration' => 'required|integer|min:0',
                'oil_quantity_for_replacement' => 'required|numeric|min:0',
                'operational_status' => 'required|in:عاملة,متوقفة',
                'stop_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'generator_name' => 'required|string|max:255',
                'generation_capacity' => 'required|numeric|min:0',
                'actual_operating_capacity' => 'required|numeric|min:0',
                'generation_group_readiness_percentage' => 'nullable|numeric|min:0|max:100',
                'fuel_consumption' => 'required|numeric|min:0',
                'oil_usage_duration' => 'required|integer|min:0',
                'oil_quantity_for_replacement' => 'required|numeric|min:0',
                'operational_status' => 'required|in:عاملة,متوقفة',
                'stop_reason' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);
            $validated['station_id'] = $user->station_id;
        }

        $generationGroup->update($validated);

        return response()->json([
            'message' => 'تم تحديث مجموعة التوليد بنجاح.',
            'generationGroup' => $generationGroup,
        ]);
    }

    /**
     * حذف مجموعة توليد.
     */
    public function destroy(GenerationGroup $generationGroup)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $generationGroup->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف مجموعة التوليد هذه'], 403);
        }
        $generationGroup->delete();

        return response()->json(['message' => 'تم حذف مجموعة التوليد بنجاح.']);
    }
}
