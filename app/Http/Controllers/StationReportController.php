<?php

namespace App\Http\Controllers;

use App\Imports\StationReportsImport;
use Illuminate\Http\Request;
use App\Models\StationReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



class StationReportController extends Controller
{
    public function index()
    {
        // الحصول على وحدة المستخدم الحالي
        $userUnitId = auth()->user()->unit_id;
    
        // جلب اسم الوحدة إذا كان الجدول يستخدم اسم الوحدة بدلاً من ID
        $userUnitName = DB::table('units')->where('id', $userUnitId)->value('unit_name');
    
        // استعلام إحصائيات الوحدات
        $unitStatsQuery = StationReport::select(
            'station_reports.وحدة المياه',
            DB::raw("SUM(total_well_hours) AS total_well_hours"),
            DB::raw("SUM(horizontal_pump_hours) AS total_horizontal_pump_hours"),
            DB::raw("SUM(solar_electricity_hours) AS total_solar_electricity_hours"),
            DB::raw("SUM(solar_generator_hours) AS total_solar_generator_hours"),
            DB::raw("SUM(solar_only_hours) AS total_solar_only_hours"),
            DB::raw("SUM(electricity_hours) AS total_electricity_hours"),
            DB::raw("SUM(electricity_consumption_kwh) AS total_electricity_consumption"),
            DB::raw("SUM(water_pumped_m3) AS total_water_pumped"),
            DB::raw("SUM(diesel_consumption) AS total_diesel_used"),
            DB::raw("SUM(oil_quantity) AS total_oil_added"),
            DB::raw("SUM(charged_electricity_kwh) AS total_charged_electricity")
        );
    
        if (!empty($userUnitName)) {
            $unitStatsQuery->where('station_reports.وحدة المياه', $userUnitName);
        }
    
        $unitStats = $unitStatsQuery->groupBy('station_reports.وحدة المياه')->get();
    
        // استعلام إحصائيات المحطات
        $stationStatsQuery = StationReport::select(
            'station_reports.وحدة المياه',
            'station_reports.المحطات',
            DB::raw("SUM(total_well_hours) AS total_well_hours"),
            DB::raw("SUM(horizontal_pump_hours) AS total_horizontal_pump_hours"),
            DB::raw("SUM(solar_electricity_hours) AS total_solar_electricity_hours"),
            DB::raw("SUM(solar_generator_hours) AS total_solar_generator_hours"),
            DB::raw("SUM(solar_only_hours) AS total_solar_only_hours"),
            DB::raw("SUM(electricity_hours) AS total_electricity_hours"),
            DB::raw("SUM(electricity_consumption_kwh) AS total_electricity_consumption"),
            DB::raw("SUM(water_pumped_m3) AS total_water_pumped"),
            DB::raw("SUM(diesel_consumption) AS total_diesel_used"),
            DB::raw("SUM(oil_quantity) AS total_oil_added"),
            DB::raw("SUM(charged_electricity_kwh) AS total_charged_electricity")
        );
    
        if (!empty($userUnitName)) {
            $stationStatsQuery->where('station_reports.وحدة المياه', $userUnitName);
        }
    
        $stationStats = $stationStatsQuery->groupBy('station_reports.وحدة المياه', 'station_reports.المحطات')->get();
    
        $avgStats = StationReport::when(!empty($userUnitName), function($query) use($userUnitName) {
            return $query->where('station_reports.وحدة المياه', $userUnitName);
        })
        ->select(
            'station_reports.المحطات',
            DB::raw("ROUND(AVG(total_well_hours), 4) as avg_total_well_hours"),
            DB::raw("ROUND(AVG(horizontal_pump_hours), 4) as avg_total_horizontal_pump_hours"),
            DB::raw("ROUND(AVG(solar_electricity_hours), 4) as avg_total_solar_electricity_hours"),
            DB::raw("ROUND(AVG(solar_generator_hours), 4) as avg_total_solar_generator_hours"),
            DB::raw("ROUND(AVG(solar_only_hours), 4) as avg_total_solar_only_hours"),
            DB::raw("ROUND(AVG(electricity_hours), 4) as avg_total_electricity_hours"),
            DB::raw("ROUND(AVG(electricity_consumption_kwh), 4) as avg_total_electricity_consumption"),
            DB::raw("ROUND(AVG(water_pumped_m3), 4) as avg_total_water_pumped"),
            DB::raw("ROUND(AVG(diesel_consumption), 4) as avg_total_diesel_used"),
            DB::raw("ROUND(AVG(oil_quantity), 4) as avg_total_oil_added"),
            DB::raw("ROUND(AVG(charged_electricity_kwh), 4) as avg_total_charged_electricity")
        )
        ->groupBy('station_reports.المحطات')
        ->get();
    
    
        // تحويل البيانات إلى JSON لاستخدامها في الرسوم البيانية (إن وجدت)
        $unitStatsForChart = $unitStats->map(function ($item) {
            return [
                'unit' => $item->{"وحدة المياه"},
                'well_hours' => $item->total_well_hours,
                'pump_hours' => $item->total_horizontal_pump_hours,
                'total_solar_electricity_hours' => $item->total_solar_electricity_hours,
                'total_solar_generator_hours' => $item->total_solar_generator_hours,
                'total_solar_only_hours' => $item->total_solar_only_hours,
                'total_electricity_hours' => $item->total_electricity_hours,
                'electricity_consumption' => $item->total_electricity_consumption,
                'water_pumped' => $item->total_water_pumped,
                'diesel_used' => $item->total_diesel_used,
                'oil_added' => $item->total_oil_added,
                'charged_electricity' => $item->total_charged_electricity
            ];
        });
    
        $stationStatsForChart = $stationStats->map(function ($item) {
            return [
                'station' => $item->{"المحطات"},
                'well_hours' => $item->total_well_hours,
                'pump_hours' => $item->total_horizontal_pump_hours,
                'total_solar_electricity_hours' => $item->total_solar_electricity_hours,
                'total_solar_generator_hours' => $item->total_solar_generator_hours,
                'total_solar_only_hours' => $item->total_solar_only_hours,
                'total_electricity_hours' => $item->total_electricity_hours,
                'electricity_consumption' => $item->total_electricity_consumption,
                'water_pumped' => $item->total_water_pumped,
                'diesel_used' => $item->total_diesel_used,
                'oil_added' => $item->total_oil_added,
                'charged_electricity' => $item->total_charged_electricity
            ];
        });
    
        return view('station_reports.index', compact('unitStats', 'stationStats', 'unitStatsForChart', 'stationStatsForChart', 'avgStats'));
    }
    
    
    
    
    
    public function create()
    {
        return view('station_reports.create');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new StationReportsImport, $request->file('file'));

        return redirect()->route('station_reports.index')->with('success', 'تم استيراد التقارير بنجاح.');
    }
   

    
    /**
     * تخزين تقرير جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'start' => 'nullable|string|max:255',
            'end' => 'nullable|string|max:255',
            'date' => 'required|date',
            'إسم المُشغل المناوب في المنهل' => 'nullable|string|max:255',
            'وحدة المياه' => 'nullable|string|max:255',
            'البلدة' => 'nullable|string|max:255',
            'المحطات' => 'nullable|string|max:255',
            'station_code' => 'required|string|max:255',
            'الوضع التشغيلي' => 'nullable|string|max:255',
            'سبب التوقف' => 'nullable|string|max:255',
            'operator_entity' => 'required|string|max:255',
            'operator_company' => 'nullable|string|max:255',
            'operating_wells_count' => 'nullable|integer|min:0',
            
            // التحقق من ساعات تشغيل كل بئر
            'well_1_hours' => 'nullable|integer|min:0',
            'well_2_hours' => 'nullable|integer|min:0',
            'well_3_hours' => 'nullable|integer|min:0',
            'well_4_hours' => 'nullable|integer|min:0',
            'well_5_hours' => 'nullable|integer|min:0',
            'well_6_hours' => 'nullable|integer|min:0',
            'well_7_hours' => 'nullable|integer|min:0',
        
            'total_well_hours' => 'nullable|integer|min:0',
            'has_horizontal_pump' => 'nullable|boolean',
            'horizontal_pump_hours' => 'nullable|integer|min:0',
            'station_operation_method' => 'nullable|string|max:255',
            'target_sector' => 'nullable|string|max:255',
            'has_disinfection' => 'nullable|boolean',
            'no_disinfection_reason' => 'nullable|string|max:255',
            'energy_source' => 'required|string|max:255',
            
            // عدد ساعات التشغيل لكل مصدر طاقة
            'solar_electricity_hours' => 'nullable|integer|min:0',
            'solar_generator_hours' => 'nullable|integer|min:0',
            'solar_only_hours' => 'nullable|integer|min:0',
            'electricity_hours' => 'nullable|integer|min:0',
            'electricity_consumption_kwh' => 'nullable|integer|min:0',
        
            // عداد الكهرباء
            'electric_meter_before' => 'nullable|integer|min:0',
            'electric_meter_after' => 'nullable|integer|min:0',
        
            // التشغيل بالمولدة والديزل
            'generator_hours' => 'nullable|integer|min:0',
            'diesel_consumption' => 'nullable|integer|min:0',
            'oil_replacement' => 'nullable|boolean',
            'oil_quantity' => 'nullable|integer|min:0',
        
            // كمية المياه والديزل
            'water_pumped_m3' => 'nullable|integer|min:0',
            'total_diesel_stock' => 'nullable|integer|min:0',
            'diesel_received' => 'nullable|boolean',
            'new_diesel_quantity' => 'nullable|integer|min:0',
            'diesel_provider' => 'nullable|string|max:255',
        
            // التعديلات والتجهيزات
            'station_modification' => 'nullable|boolean',
            'modification_location' => 'nullable|string|max:255',
            'modification_details' => 'nullable|string',
            'transfer_destination' => 'nullable|string|max:255',
        
            // شحن عداد الكهرباء
            'electric_meter_charged' => 'nullable|boolean',
            'charged_electricity_kwh' => 'nullable|integer|min:0',
        
            'operator_notes' => 'nullable|string',
        ]);
        

        StationReport::create($request->all());

        return redirect()->route('station_reports.index')->with('success', 'تم إنشاء التقرير بنجاح.');
    }

    /**
     * عرض تفاصيل تقرير معين
     */
    public function show(StationReport $report)
    {
        return view('station_reports.show', compact('report'));
    }

    public function edit(StationReport $report)
    {
        return view('station_reports.edit', compact('report'));
    }

    /**
     * تحديث تقرير في قاعدة البيانات
     */
    public function update(Request $request, StationReport $stationReport)
    {
        $request->validate([
            'start' => 'nullable|string|max:255',
            'end' => 'nullable|string|max:255',
            'date' => 'required|date',
            'إسم المُشغل المناوب في المنهل' => 'nullable|string|max:255',
            'وحدة المياه' => 'nullable|string|max:255',
            'البلدة' => 'nullable|string|max:255',
            'المحطات' => 'nullable|string|max:255',
            'station_code' => 'required|string|max:255',
            'الوضع التشغيلي' => 'nullable|string|max:255',
            'سبب التوقف' => 'nullable|string|max:255',
            'operator_entity' => 'required|string|max:255',
            'operator_company' => 'nullable|string|max:255',
            'operating_wells_count' => 'nullable|integer|min:0',
            
            // التحقق من ساعات تشغيل كل بئر
            'well_1_hours' => 'nullable|integer|min:0',
            'well_2_hours' => 'nullable|integer|min:0',
            'well_3_hours' => 'nullable|integer|min:0',
            'well_4_hours' => 'nullable|integer|min:0',
            'well_5_hours' => 'nullable|integer|min:0',
            'well_6_hours' => 'nullable|integer|min:0',
            'well_7_hours' => 'nullable|integer|min:0',
        
            'total_well_hours' => 'nullable|integer|min:0',
            'has_horizontal_pump' => 'nullable|boolean',
            'horizontal_pump_hours' => 'nullable|integer|min:0',
            'station_operation_method' => 'nullable|string|max:255',
            'target_sector' => 'nullable|string|max:255',
            'has_disinfection' => 'nullable|boolean',
            'no_disinfection_reason' => 'nullable|string|max:255',
            'energy_source' => 'required|string|max:255',
            
            // عدد ساعات التشغيل لكل مصدر طاقة
            'solar_electricity_hours' => 'nullable|integer|min:0',
            'solar_generator_hours' => 'nullable|integer|min:0',
            'solar_only_hours' => 'nullable|integer|min:0',
            'electricity_hours' => 'nullable|integer|min:0',
            'electricity_consumption_kwh' => 'nullable|integer|min:0',
        
            // عداد الكهرباء
            'electric_meter_before' => 'nullable|integer|min:0',
            'electric_meter_after' => 'nullable|integer|min:0',
        
            // التشغيل بالمولدة والديزل
            'generator_hours' => 'nullable|integer|min:0',
            'diesel_consumption' => 'nullable|integer|min:0',
            'oil_replacement' => 'nullable|boolean',
            'oil_quantity' => 'nullable|integer|min:0',
        
            // كمية المياه والديزل
            'water_pumped_m3' => 'nullable|integer|min:0',
            'total_diesel_stock' => 'nullable|integer|min:0',
            'diesel_received' => 'nullable|boolean',
            'new_diesel_quantity' => 'nullable|integer|min:0',
            'diesel_provider' => 'nullable|string|max:255',
        
            // التعديلات والتجهيزات
            'station_modification' => 'nullable|boolean',
            'modification_location' => 'nullable|string|max:255',
            'modification_details' => 'nullable|string',
            'transfer_destination' => 'nullable|string|max:255',
        
            // شحن عداد الكهرباء
            'electric_meter_charged' => 'nullable|boolean',
            'charged_electricity_kwh' => 'nullable|integer|min:0',
        
            'operator_notes' => 'nullable|string',
        ]);
        

        $stationReport->update($request->all());

        return redirect()->route('station_reports.index')->with('success', 'تم تحديث التقرير بنجاح.');
    }

    /**
     * حذف تقرير من قاعدة البيانات
     */
    public function destroy(StationReport $stationReport)
    {
        $stationReport->delete();
        return redirect()->route('station_reports.index')->with('success', 'تم حذف التقرير بنجاح.');
    }
}
