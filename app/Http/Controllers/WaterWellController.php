<?php

namespace App\Http\Controllers;

use App\Imports\WaterWellImport;
use App\Models\Station;
use App\Models\WaterWell;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WaterWellController extends Controller
{
    public function index(Request $request)
    {   
        $userUnitId = auth()->user()->unit_id;
        
        $stationCodes = $userUnitId 
            ? Station::whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            })->pluck('station_code') 
            : null;
    
        $waterWells = $stationCodes 
            ? WaterWell::whereIn('station_code', $stationCodes)->orderBy('well_name')->orderBy('created_at')->get()
            : WaterWell::orderBy('well_name')->orderBy('created_at')->get();
    
        $groupedWells = $waterWells->groupBy('well_name');
    
        $filteredWells = collect();
    
        foreach ($groupedWells as $wellName => $wells) {
            $previousEndMeter = null;
    
            foreach ($wells as $index => $well) {
                $actualQuantity = $well->flow_meter_end - $well->flow_meter_start;
                $soldQuantity = $well->water_sold_quantity;
                $well->quantity_check = (abs($actualQuantity - $soldQuantity) <= $soldQuantity * 0.05) ? 'صحيحة' : 'خاطئة';
    
                $freeWaterAmount = $well->free_filling_quantity * $well->water_price;
                $vehicleWaterAmount = $well->vehicle_filling_quantity * $well->water_price;
                $calculatedAmount = ($soldQuantity * $well->water_price) - $freeWaterAmount - $vehicleWaterAmount;
    
                $well->price_check = (abs($calculatedAmount - $well->total_amount) <= $well->total_amount * 0.05) ? 'صحيحة' : 'خاطئة';
    
                if ($previousEndMeter !== null) {
                    $well->meter_sequence_check = ($previousEndMeter == $well->flow_meter_start) ? 'صحيح' : 'خاطئ';
                } else {
                    $well->meter_sequence_check = 'اول ادخال';
                }
    
                $previousEndMeter = $well->flow_meter_end;
    
                // إذا كان الفلتر مفعلاً، نضيف فقط السجلات الخاطئة
                if ($request->filter == 'incorrect') {
                    if ($well->quantity_check === 'خاطئة' || $well->price_check === 'خاطئة' || $well->meter_sequence_check === 'خاطئ') {
                        $filteredWells->push($well);
                    }
                } else {
                    $filteredWells->push($well); // عرض جميع البيانات
                }
            }
        }
    
        return view('waterwells.index', compact('filteredWells', 'request'));
    }
    
    

    // عرض السجل المحدد
    public function show($id)
    {
        $waterWell = WaterWell::findOrFail($id);
        return view('waterwells.show', compact('waterWell'));
    }

    public function importForm()
    {
        return view('waterwells.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new WaterWellImport, $request->file('file'));

        return redirect()->route('waterwells.index')->with('success', 'تم استيراد البيانات بنجاح!');
    }
    

    
    /**
 * عرض صفحة إنشاء سجل جديد
 */

    public function create()
    {
        // عرض صفحة إنشاء السجل مع النموذج
        return view('waterwells.create');
    }

    // إنشاء سجل جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_code' => 'required|string',
            'well_name' => 'required|string',
            'has_flow_meter' => 'required|in:نعم,لا',
            'flow_meter_start' => 'required|numeric',
            'flow_meter_end' => 'required|numeric',
            'water_sold_quantity' => 'required|numeric',
            'water_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'has_vehicle_filling' => 'required|in:نعم,لا',
            'vehicle_filling_quantity' => 'nullable|numeric',
            'has_free_filling' => 'required|in:نعم,لا',
            'free_filling_quantity' => 'nullable|numeric',
            'entity_for_free_filling' => 'nullable|string',
            'document_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        WaterWell::create($validated);

        return redirect()->route('waterwells.index')->with('success', 'تم إضافة المنهل بنجاح');
    }

    // عرض صفحة تعديل السجل
    public function edit($id)
    {
        $waterWell = WaterWell::findOrFail($id);
        return view('waterwells.edit', compact('waterWell'));
    }

    // تحديث السجل المحدد
    public function update(Request $request, $id)
    {
        $waterWell = WaterWell::findOrFail($id);

        $validated = $request->validate([
            'station_code' => 'required|string',
            'well_name' => 'required|string',
            'has_flow_meter' => 'required|in:نعم,لا',
            'flow_meter_start' => 'required|numeric',
            'flow_meter_end' => 'required|numeric',
            'water_sold_quantity' => 'required|numeric',
            'water_price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'has_vehicle_filling' => 'required|in:نعم,لا',
            'vehicle_filling_quantity' => 'nullable|numeric',
            'has_free_filling' => 'required|in:نعم,لا',
            'free_filling_quantity' => 'nullable|numeric',
            'entity_for_free_filling' => 'nullable|string',
            'document_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $waterWell->update($validated);

        return redirect()->route('waterwells.show', $waterWell->id)->with('success', 'تم التعديل بنجاح');
    }

    // حذف السجل المحدد
    public function destroy($id)
    {
        $waterWell = WaterWell::findOrFail($id);
        $waterWell->delete();

        return redirect()->route('waterwells.index')->with('success', 'تم الحذف بنجاح');
    }
    
}
