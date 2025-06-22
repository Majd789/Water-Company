<?php

namespace App\Http\Controllers;

use App\Exports\GroundTanksExport;
use App\Imports\GroundTanksImport;
use Illuminate\Http\Request;
use App\Models\GroundTank;
use App\Models\Station;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class GroundTankController extends Controller
{
    public function index(Request $request)
    {
    // استرجاع جميع الوحدات لاستخدامها في الفلترة
    $units = Unit::all();

    // استرجاع وحدة المستخدم الحالية (إن وجدت)
    $userUnitId = auth()->user()->unit_id;

    // إنشاء استعلام لجلب الخزانات الأرضية
    $query = GroundTank::with('station');

    // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
    $selectedUnitId = $request->unit_id ?? $userUnitId;

    if (!empty($selectedUnitId)) {
        // تصفية المحطات بناءً على الوحدة المختارة
        $query->whereHas('station.town', function ($q) use ($selectedUnitId) {
            $q->where('unit_id', $selectedUnitId);
        });
    }

    // تصفية الخزانات بناءً على البلدة المختارة
    if ($request->filled('town_id')) {
        $query->whereHas('station', function ($q) use ($request) {
            $q->where('town_id', $request->town_id);
        });
    }

    // البحث باستخدام نص يشمل جميع الحقول ذات الصلة
    if ($request->filled('search')) {
        $searchTerm = trim($request->search);

        $query->where(function ($q) use ($searchTerm) {
            $q->where('tank_name', 'like', '%' . $searchTerm . '%') // البحث باسم الخزان
              ->orWhereHas('station', function ($stationQuery) use ($searchTerm) {
                  $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
              });
        });
    }

    // جلب البيانات مع التصفية والصفحات
    $groundTanks = $query->paginate(100);

    // عرض البيانات في الصفحة وتمرير الوحدات للفلترة
    return view('ground-tanks.index', compact('groundTanks', 'units'));
    }

    
    
        public function export()
    {
        return Excel::download(new GroundTanksExport, 'ground_tanks.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new GroundTanksImport, $request->file('file'));

        return redirect()->route('ground-tanks.index')->with('success', 'تم استيراد الخزانات الأرضية بنجاح.');
    }
    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;
    
        // إذا كانت هناك وحدة، جلب المحطات عبر البلدات المرتبطة بالوحدة
        if ($unit) {
            $stations = \App\Models\Station::whereIn('town_id', $unit->towns->pluck('id'))->get();
        } else {
            // إذا لم تكن هناك وحدة، جلب جميع المحطات
            $stations = \App\Models\Station::all();
        }
    
        return view('ground-tanks.create', compact('stations'));
    }
    
 
     // تخزين الخزان الجديد في قاعدة البيانات
     public function store(Request $request)
     {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'building_entity' => 'required|string|max:255',
            'construction_type' => 'required|in:قديم,جديد', // تحقق من نوع البناء
            'capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'feeding_station' => 'required|string|max:255',
            'town_supply' => 'required|string|max:255',
            'pipe_diameter_inside' => 'nullable|numeric|min:0',
            'pipe_diameter_outside' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'precision' => 'nullable|numeric',
        ]);
        
        
 
         GroundTank::create($request->all()); // إنشاء الخزان الجديد
         return redirect()->route('ground-tanks.index')->with('success', 'تم إضافة الخزان بنجاح');
     }
 
     // عرض نموذج تعديل خزان
     public function edit($id)
     {
         $groundTank = GroundTank::findOrFail($id);
         $stations = Station::all();
         return view('ground-tanks.edit', compact('groundTank', 'stations'));
     }
 
     // تحديث بيانات الخزان
     public function update(Request $request, $id)
     {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'building_entity' => 'required|string|max:255',
            'construction_type' => 'required|in:قديم,جديد', // تحقق من نوع البناء
            'capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'feeding_station' => 'required|string|max:255',
            'town_supply' => 'required|string|max:255',
            'pipe_diameter_inside' => 'nullable|numeric|min:0',
            'pipe_diameter_outside' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'precision' => 'nullable|numeric',
        ]);
        
        
 
         $groundTank = GroundTank::findOrFail($id);
         $groundTank->update($request->all()); // تحديث البيانات
         return redirect()->route('ground-tanks.index')->with('success', 'تم تحديث الخزان بنجاح');
     }
 
     // عرض تفاصيل الخزان
     public function show($id)
     {
         $groundTank = GroundTank::with('station')->findOrFail($id);
         return view('ground-tanks.show', compact('groundTank'));
     }
 
     // حذف الخزان
     public function destroy($id)
     {
         $groundTank = GroundTank::findOrFail($id);
         $groundTank->delete(); // حذف الخزان
         return redirect()->route('ground-tanks.index')->with('success', 'تم حذف الخزان بنجاح');
     }
 }