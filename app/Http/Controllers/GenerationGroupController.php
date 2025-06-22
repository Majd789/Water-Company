<?php

namespace App\Http\Controllers;

use App\Exports\GenerationGroupsExport;
use App\Imports\GenerationGroupsImport;
use Illuminate\Http\Request;
use App\Models\GenerationGroup;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class GenerationGroupController extends Controller
{
   /**
     * عرض قائمة مجموعات التوليد.
     */
    public function index(Request $request)
    {
        // استرجاع جميع الوحدات
        $units = Unit::all();
    
        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = auth()->user()->unit_id;
    
        // استعلام لجلب المجموعات التوليدية
        $generationGroups = GenerationGroup::query();
    
        // التحقق مما إذا كان المستخدم لديه وحدة مرتبطة أو تم اختيار وحدة من الطلب
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
        if (!empty($selectedUnitId)) {
            // تصفية البلدات بناءً على الوحدة المحددة
            $towns = Town::where('unit_id', $selectedUnitId)->get();
    
            // تصفية المحطات بناءً على البلدات المرتبطة بالوحدة
            $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
    
            // تصفية المجموعات التوليدية بناءً على المحطات
            $generationGroups = $generationGroups->whereIn('station_id', $stations->pluck('id'));
        } else {
            // إذا لم يكن هناك وحدة محددة، استرجاع جميع البلدات والمحطات
            $towns = Town::all();
            $stations = Station::query();
        }
    
        // تصفية المجموعات التوليدية بناءً على البلدة المحددة
        if ($request->has('town_id') && $request->town_id != '') {
            $stations = $stations->where('town_id', $request->town_id);
            $generationGroups = $generationGroups->whereIn('station_id', $stations->pluck('id'));
        }
    
        // تصفية المجموعات التوليدية بناءً على كود المحطة، اسم المحطة، أو اسم المولدة
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $generationGroups = $generationGroups->where(function ($query) use ($searchTerm) {
                $query->whereHas('station', function ($stationQuery) use ($searchTerm) {
                    $stationQuery->where('station_code', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('station_name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('generator_name', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // استرجاع البيانات مع المحطات والفلترة
        $generationGroups = $generationGroups->with('station')->paginate(10000);
    
        return view('generation-groups.index', compact('generationGroups', 'units', 'towns'));
    }
    
    
    
        public function exportGenerationGroups()
        {
            return Excel::download(new GenerationGroupsExport, 'generation_groups.xlsx');
        }

        public function import(Request $request)
        {
            // التحقق من صحة الملف
            $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);
    
            // استيراد البيانات
            Excel::import(new GenerationGroupsImport, $request->file('file'));
    
            return redirect()->route('generation-groups.index')->with('success', 'تم استيراد مجموعات التوليد بنجاح.');
        }
    /**
     * عرض نموذج إنشاء مجموعة توليد جديدة.
     */
    public function create()
    {
        // التحقق إذا كان المستخدم لديه وحدة مرتبطة
        if (auth()->user()->unit_id) {
            $userUnitId = auth()->user()->unit_id;
    
            // الحصول على البلدات المرتبطة بوحدة المستخدم
            $towns = Town::where('unit_id', $userUnitId)->get();
    
            // جلب المحطات المرتبطة بالبلدات
            $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
        } else {
            // إذا لم يكن هناك وحدة مرتبطة بالمستخدم، عرض جميع المحطات
            $stations = Station::all();
        }
    
        return view('generation-groups.create', compact('stations'));
    }
    

    /**
     * تخزين مجموعة توليد جديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
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

        GenerationGroup::create($request->all());

        return redirect()->route('generation-groups.index')->with('success', 'تم إنشاء مجموعة التوليد بنجاح.');
    }

    /**
     * عرض تفاصيل مجموعة توليد معينة.
     */
    public function show(GenerationGroup $generationGroup)
    {
        return view('generation-groups.show', compact('generationGroup'));
    }

    /**
     * عرض نموذج تعديل مجموعة توليد.
     */
    public function edit(GenerationGroup $generationGroup)
    {
        $stations = Station::all();
        return view('generation-groups.edit', compact('generationGroup', 'stations'));
    }

    /**
     * تحديث مجموعة توليد موجودة.
     */
    public function update(Request $request, GenerationGroup $generationGroup)
    {
        $request->validate([
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

        $generationGroup->update($request->all());

        return redirect()->route('generation-groups.index')->with('success', 'تم تحديث مجموعة التوليد بنجاح.');
    }

    /**
     * حذف مجموعة توليد.
     */
    public function destroy(GenerationGroup $generationGroup)
    {
        $generationGroup->delete();

        return redirect()->route('generation-groups.index')->with('success', 'تم حذف مجموعة التوليد بنجاح.');
    }
}