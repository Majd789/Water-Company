<?php

namespace App\Http\Controllers;

use App\Exports\StationsExport;
use App\Imports\StationsImport;
use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // الحصول على المستخدم الحالي
        $user = Auth::user();
        
        // استرجاع جميع الوحدات
        $units = Unit::all();
        
        // التحقق مما إذا كان للمستخدم وحدة محددة
        if ($user->unit_id) {
            $userUnitId = $user->unit_id;
    
            // استرجاع البلدات المرتبطة بالوحدة الخاصة بالمستخدم
            $towns = Town::where('unit_id', $userUnitId)->get();
    
            // تصفية المحطات المرتبطة فقط بالبلدات التابعة للوحدة الخاصة بالمستخدم
            $stations = Station::whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            });
        } else {
            // إذا لم يكن لديه وحدة، استرجاع جميع البلدات والمحطات
            $towns = Town::all();
            $stations = Station::query();
        }
    
        // تصفية المحطات بناءً على الوحدة إذا تم اختيار وحدة معينة (للمستخدمين غير المرتبطين بوحدة)
        if ($request->has('unit_id') && $request->unit_id != '') {
            $stations->whereHas('town', function ($query) use ($request) {
                $query->where('unit_id', $request->unit_id);
            });
            $towns = Town::where('unit_id', $request->unit_id)->get();
        }
    
        // تصفية المحطات بناءً على البلدة المحددة
        if ($request->has('town_id') && $request->town_id != '') {
            $stations->where('town_id', $request->town_id);
        }
    
        // تصفية المحطات بناءً على كود المحطة
        if ($request->has('station_code') && $request->station_code != '') {
            $stations->where('station_code', 'like', '%' . $request->station_code . '%');
        }
    
        // استرجاع المحطات مع البلدات
        $stations = $stations->with('town')->paginate(50000);
    
        return view('stations.index', compact('stations', 'towns', 'units'));
    }
    
        public function export()
        {
            $unitId = auth()->user()->unit_id ?? null;
            return Excel::download(new StationsExport($unitId), 'stations.xlsx');
        }

        public function import(Request $request)
        {
            // تحقق من صحة الملف
            $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);
    
            // استيراد البيانات من الملف
            Excel::import(new StationsImport, $request->file('file'));
    
            // إعادة التوجيه مع رسالة النجاح
            return redirect()->route('stations.index')->with('success', 'تم استيراد المحطات بنجاح.');
        }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $towns = Town::all();
        return view('stations.create', compact('towns'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // قائمة مصادر الطاقة المسموح بها
        $allowedEnergySources = [
            'لا يوجد', 'كهرباء', 'مولدة', 'طاقة شمسية', 'كهرباء و مولدة',
            'كهرباء و طاقة شمسية', 'مولدة و طاقة شمسية', 'كهرباء و مولدة و طاقة شمسية'
        ];
        $allowedNetworkTypes = [
                'بولي إيثيلين', 'حديد', 'فونط (حديد صب)', 'أترنيت', 'PVC',
                'بولي إيثيلين و حديد', 'بولي إيثيلين و فونط', 'بولي إيثيلين و أترنيت',
                'حديد و أترنيت', 'PVC و أترنيت', 'بولي إيثيلين و حديد و أترنيت',
                'خط ضخ', 'غير محدد / أخرى'
            ];
        $request->validate([
            'station_code' => 'required|unique:stations,station_code|max:255',
            'station_name' => 'required|max:255',
            'operational_status' => 'required|in:خارج الخدمة,عاملة,متوقفة',
            'stop_reason' => 'nullable|max:255',
            // --- التعديل هنا ---
            'energy_source' => ['nullable', Rule::in($allowedEnergySources)],
            'operator_entity' => 'nullable|in:تشغيل تشاركي,المؤسسة العامة لمياه الشرب',
            'operator_name' => 'nullable|max:255',
            'general_notes' => 'nullable',
            'town_id' => 'required|exists:towns,id',
            'water_delivery_method' => ['nullable', 'in:شبكة,منهل,شبكة و منهل'],
            'network_readiness_percentage' => 'nullable|numeric|min:0|max:100',
            'network_type' => ['nullable', Rule::in($allowedNetworkTypes)],
            'beneficiary_families_count' => 'nullable|integer|min:0',
            'has_disinfection' => 'required|boolean',
            'disinfection_reason' => 'nullable|max:255',
            'served_locations' => 'nullable',
            'actual_flow_rate' => 'nullable|numeric|min:0',
            'station_type' => 'nullable|max:255',
            'detailed_address' => 'nullable',
            'land_area' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|max:255',
            'building_notes' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_verified' => 'required|boolean',
        ]);

        Station::create($request->all());

        return redirect()->route('stations.index')->with('success', 'تم إضافة المحطة بنجاح');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $station = Station::with('town')->findOrFail($id);
        return view('stations.show', compact('station'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $station = Station::findOrFail($id);
        $towns = Town::all();
        return view('stations.edit', compact('station', 'towns'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, string $id)
    {
        $station = Station::findOrFail($id);
         $allowedNetworkTypes = [
        'بولي إيثيلين', 'حديد', 'فونط (حديد صب)', 'أترنيت', 'PVC',
        'بولي إيثيلين و حديد', 'بولي إيثيلين و فونط', 'بولي إيثيلين و أترنيت',
        'حديد و أترنيت', 'PVC و أترنيت', 'بولي إيثيلين و حديد و أترنيت',
        'خط ضخ', 'غير محدد / أخرى'
    ];
        // قائمة مصادر الطاقة المسموح بها
        $allowedEnergySources = [
            'لا يوجد', 'كهرباء', 'مولدة', 'طاقة شمسية', 'كهرباء و مولدة',
            'كهرباء و طاقة شمسية', 'مولدة و طاقة شمسية', 'كهرباء و مولدة و طاقة شمسية'
        ];

        $request->validate([
            'station_code' => 'required|unique:stations,station_code,' . $station->id . '|max:255',
            'station_name' => 'required|max:255',
            'operational_status' => 'required|in:خارج الخدمة,عاملة,متوقفة',
            'stop_reason' => 'nullable|max:255',
            // --- التعديل هنا ---
            'energy_source' => ['nullable', Rule::in($allowedEnergySources)],
            'operator_entity' => 'nullable|in:تشغيل تشاركي,المؤسسة العامة لمياه الشرب',
            'operator_name' => 'nullable|max:255',
            'general_notes' => 'nullable',
            'town_id' => 'required|exists:towns,id',
            'water_delivery_method' => ['nullable', 'in:شبكة,منهل,شبكة و منهل'],
            'network_readiness_percentage' => 'nullable|numeric|min:0|max:100',
           'network_type' => ['nullable', Rule::in($allowedNetworkTypes)],
            'beneficiary_families_count' => 'nullable|integer|min:0',
            'has_disinfection' => 'required|boolean',
            'disinfection_reason' => 'nullable|max:255',
            'served_locations' => 'nullable',
            'actual_flow_rate' => 'nullable|numeric|min:0',
            'station_type' => 'nullable|max:255',
            'detailed_address' => 'nullable',
            'land_area' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|max:255',
            'building_notes' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_verified' => 'required|boolean',
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')->with('success', 'تم تحديث المحطة بنجاح');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $station = Station::findOrFail($id);
        $station->delete();

        return redirect()->route('stations.index')->with('success', 'تم حذف المحطة بنجاح');
    }
}
