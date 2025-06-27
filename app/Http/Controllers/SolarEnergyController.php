<?php

namespace App\Http\Controllers;

use App\Exports\SolarEnergiesExport;
use App\Imports\SolarEnergiesImport;
use App\Models\SolarEnergy;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SolarEnergyController extends Controller
{
    /**
     * عرض جميع بيانات الطاقة الشمسية
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;
    
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // استعلام للبحث عن بيانات الطاقة الشمسية
        $query = SolarEnergy::query();
    
        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
        // تصفية البيانات بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }
    
        // التحقق إذا كان يوجد قيمة في الطلب للبحث
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
    
            // البحث في اسم المحطة أو كود المحطة أو حجم اللوح
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('station', function ($q) use ($searchTerm) {
                    $q->where('station_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('station_code', 'like', '%' . $searchTerm . '%'); // البحث في كود المحطة
                })
                ->orWhere('panel_size', 'like', '%' . $searchTerm . '%'); // البحث في حجم اللوح
            });
        }
    
        // جلب البيانات مع المحطات المرتبطة
        $solarEnergies = $query->with('station')->paginate(10000); // الترقيم
    
        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('solar_energy.index', compact('solarEnergies', 'units', 'selectedUnitId'));
    }
    
    
    public function export()
    {
        return Excel::download(new SolarEnergiesExport, 'solar_energies.xlsx');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    // استيراد البيانات من الملف
    Excel::import(new SolarEnergiesImport, $request->file('file'));

    return redirect()->route('solar_energy.index')->with('success', 'تم استيراد البيانات بنجاح');
    }
    /**
     * عرض نموذج إنشاء بيانات الطاقة الشمسية جديدة
     */
    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;
    
        // إذا كانت هناك وحدة، جلب المحطات المرتبطة بالبلدات التي تحت الوحدة
        if ($unit) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = $unit->towns;
    
            // جلب المحطات بناءً على البلدات المرتبطة بالوحدة
            $stations = \App\Models\Station::whereIn('town_id', $towns->pluck('id'))->get();
        } else {
            // إذا لم تكن هناك وحدة، جلب جميع المحطات
            $stations = \App\Models\Station::all();
        }
    
        // إرسال المحطات إلى العرض
        return view('solar_energy.create', compact('stations'));
    }
    

    /**
     * تخزين بيانات الطاقة الشمسية الجديدة
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'station_id' => 'required|exists:stations,id',  // التحقق من وجود المحطة
            'panel_size' => 'required|numeric|min:0',  // قياس اللوح
            'panel_count' => 'required|integer|min:0',  // عدد الألواح
            'manufacturer' => 'required|string|max:255',  // الجهة المنشئة
           'base_type' => 'required|in:ثابتة,متحركة', // تقييد القيم  // نوع القاعدة
            'technical_condition' => 'required|string|max:255',  // الحالة الفنية
            'wells_supplied_count' => 'required|integer|min:0',  // عدد الآبار المغذاة
            'general_notes' => 'nullable|string',  // ملاحظات
            'latitude' => 'nullable|numeric',  // خط العرض
            'longitude' => 'nullable|numeric',  // خط الطول
        ]);

        // إنشاء سجل جديد للطاقة الشمسية
        SolarEnergy::create($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('solar_energy.index')->with('success', 'تمت إضافة بيانات الطاقة الشمسية بنجاح.');
    }

    /**
     * عرض تفاصيل بيانات الطاقة الشمسية
     */
    public function show(SolarEnergy $solarEnergy)
    {
        return view('solar_energy.show', compact('solarEnergy'));
    }

    /**
     * عرض نموذج تعديل بيانات الطاقة الشمسية
     */
    public function edit(SolarEnergy $solarEnergy)
    {
        // استرجاع جميع المحطات لتمكين المستخدم من الاختيار
        $stations = Station::all();
        return view('solar_energy.edit', compact('solarEnergy', 'stations'));
    }

    /**
     * تحديث بيانات الطاقة الشمسية
     */
    public function update(Request $request, SolarEnergy $solarEnergy)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'station_id' => 'required|exists:stations,id',  // التحقق من وجود المحطة
            'panel_size' => 'required|numeric|min:0',  // قياس اللوح
            'panel_count' => 'required|integer|min:0',  // عدد الألواح
            'manufacturer' => 'required|string|max:255',  // الجهة المنشئة
            'base_type' => 'required|in:ثابتة,متحركة', // تقييد القيم  // نوع القاعدة
            'technical_condition' => 'required|string|max:255',  // الحالة الفنية
            'wells_supplied_count' => 'required|integer|min:0',  // عدد الآبار المغذاة
            'general_notes' => 'nullable|string',  // ملاحظات
            'latitude' => 'nullable|numeric',  // خط العرض
            'longitude' => 'nullable|numeric',  // خط الطول
        ]);

        // تحديث بيانات الطاقة الشمسية
        $solarEnergy->update($request->all());

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('solar_energy.index')->with('success', 'تم تحديث بيانات الطاقة الشمسية بنجاح.');
    }

    /**
     * حذف بيانات الطاقة الشمسية
     */
    public function destroy(SolarEnergy $solarEnergy)
    {
        // حذف سجل الطاقة الشمسية
        $solarEnergy->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('solar_energy.index')->with('success', 'تم حذف بيانات الطاقة الشمسية بنجاح.');
    }
}
