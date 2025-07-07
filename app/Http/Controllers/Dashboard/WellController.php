<?php

// في ملف WellController.php في مجلد app/Http/Controllers

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\WellsExport;
use App\Imports\WellsImport;
use App\Models\Well;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class WellController extends Controller
{
     private function getAllowedEnergySources()
    {
        return [
            'لا يوجد', 'كهرباء', 'مولدة', 'طاقة شمسية', 'كهرباء و مولدة',
            'كهرباء و طاقة شمسية', 'مولدة و طاقة شمسية', 'كهرباء و مولدة و طاقة شمسية'
        ];
    }
    public function index(Request $request)
    {
        // استرجاع جميع الوحدات
        $units = Unit::all();

        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = auth()->user()->unit_id;

        // استعلام لجلب الآبار
        $wells = Well::query();

        // التحقق مما إذا كان المستخدم لديه وحدة مرتبطة أو تم اختيار وحدة من الطلب
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        if (!empty($selectedUnitId)) {
            // تصفية البلدات بناءً على الوحدة المحددة
            $towns = Town::where('unit_id', $selectedUnitId)->get();

            // تصفية المحطات بناءً على البلدات المرتبطة بالوحدة
            $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();

            // تصفية الآبار بناءً على المحطات المرتبطة
            $wells = $wells->whereIn('station_id', $stations->pluck('id'));
        } else {
            // إذا لم يكن هناك وحدة مرتبطة بالمستخدم، استرجاع جميع البلدات والمحطات
            $towns = Town::all();
            $stations = Station::query();
        }

        // تصفية الآبار بناءً على البلدة المحددة
        if ($request->has('town_id') && $request->town_id != '') {
            $stations = $stations->where('town_id', $request->town_id);
            $wells = $wells->whereIn('station_id', $stations->pluck('id'));
        }

        // تصفية الآبار بناءً على كود المحطة، اسم المحطة، أو اسم البئر
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $wells = $wells->whereHas('station', function ($query) use ($searchTerm) {
                $query->where('station_code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('station_name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhere('well_name', 'like', '%' . $searchTerm . '%');
        }

        // استرجاع الآبار مع المحطات
        $wells = $wells->with('station')->paginate(5000);

        return view('wells.index', compact('wells', 'units', 'towns'));
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

        return redirect()->route('wells.index')->with('success', 'تم استيراد الآبار بنجاح.');
    }

    public function create()
    {
        // إذا كان هناك وحدة للمستخدم
        if(auth()->user()->unit_id) {
            // استرجاع الوحدة المرتبطة بالمستخدم
            $unit = auth()->user()->unit;

            // الحصول على البلدات التي تتبع الوحدة
            $towns = Town::where('unit_id', $unit->id)->get();

            // الحصول على المحطات التي تتبع البلدات
            $stations = Station::whereIn('town_id', $towns->pluck('id'))->get();
        } else {
            // في حالة عدم وجود وحدة للمستخدم، عرض جميع المحطات
            $stations = Station::all();
            $towns = Town::all();  // عرض جميع البلدات في حالة عدم وجود وحدة للمستخدم
        }

        return view('wells.create', compact('stations', 'towns'));
    }
    // حفظ بئر جديد
    public function store(Request $request)
    {
         $allowedPumpBrands = [
            'ATURIA', 'CHINESE', 'GRUNDFOS', 'RED JACKET', 'JET', 'LOWARA',
            'LOWARA/EU', 'LOWARA/FRANKLIN', 'LOWARA/VOGEL', 'PLUGER', 'RITZ',
            'ROVATTI', 'VANSAN', 'WILLO', 'غير معروف'
        ];
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id', // يجب أن يكون معتمدًا على المحطات
            'town_code' => 'required|string|max:255',
            'well_name' => 'required|string|max:255',
            'well_status' => 'nullable|in:يعمل,متوقف',
            'stop_reason' => 'nullable|string',
            'distance_from_station' => 'nullable|numeric',
            'well_type' => 'nullable|in:جوفي,سطحي',
            'well_flow' => 'nullable|numeric',
            'static_depth' => 'nullable|numeric',
            'dynamic_depth' => 'nullable|numeric',
            'drilling_depth' => 'nullable|numeric',
            'well_diameter' => 'nullable|numeric',
            'pump_installation_depth' => 'nullable|numeric',
            'pump_capacity' => 'nullable|numeric',
            'actual_pump_flow' => 'nullable|numeric',
            'pump_lifting' => 'nullable|numeric',
            'pump_brand_model' => ['nullable', Rule::in($allowedPumpBrands)],
           'energy_source' => ['nullable', Rule::in($this->getAllowedEnergySources())],
            'well_address' => 'nullable|string',
            'general_notes' => 'nullable|string',
            'well_location' => 'nullable|string', // تأكد من تنسيق الـ point
        ]);

        // حفظ البيانات في قاعدة البيانات
        Well::create($validated);

        // إعادة التوجيه إلى صفحة الآبار مع رسالة نجاح
        return redirect()->route('wells.index')->with('success', 'تم إضافة البئر بنجاح');
    }

    // عرض نموذج تعديل بئر
    public function edit(Well $well)
    {
        $stations = Station::all(); // جلب جميع المحطات
        return view('wells.edit', compact('well', 'stations')); // إرجاع العرض مع البيانات
    }

    // تحديث بيانات البئر
    public function update(Request $request, Well $well)
    {
        $allowedPumpBrands = [
            'ATURIA', 'CHINESE', 'GRUNDFOS', 'RED JACKET', 'JET', 'LOWARA',
            'LOWARA/EU', 'LOWARA/FRANKLIN', 'LOWARA/VOGEL', 'PLUGER', 'RITZ',
            'ROVATTI', 'VANSAN', 'WILLO', 'غير معروف'
        ];
        // التحقق من صحة المدخلات
        $validated = $request->validate([
           'station_id' => 'required|exists:stations,id', // يجب أن يكون معتمدًا على المحطات
            'town_code' => 'required|string|max:255',
            'well_name' => 'required|string|max:255',
            'well_status' => 'nullable|in:يعمل,متوقف',
            'stop_reason' => 'nullable|string',
            'distance_from_station' => 'nullable|numeric',
            'well_type' => 'nullable|in:جوفي,سطحي',
            'well_flow' => 'nullable|numeric',
            'static_depth' => 'nullable|numeric',
            'dynamic_depth' => 'nullable|numeric',
            'drilling_depth' => 'nullable|numeric',
            'well_diameter' => 'nullable|numeric',
            'pump_installation_depth' => 'nullable|numeric',
            'pump_capacity' => 'nullable|numeric',
            'actual_pump_flow' => 'nullable|numeric',
            'pump_lifting' => 'nullable|numeric',
            'pump_brand_model' => ['nullable', Rule::in($allowedPumpBrands)],
            'energy_source' => ['nullable', Rule::in($this->getAllowedEnergySources())],
            'well_address' => 'nullable|string',
            'general_notes' => 'nullable|string',
            'well_location' => 'nullable|string', // تأكد من تنسيق الـ point
        ]);

        // تحديث البيانات في قاعدة البيانات
        $well->update($validated);

        // إعادة التوجيه إلى صفحة الآبار مع رسالة نجاح
        return redirect()->route('wells.index')->with('success', 'تم تحديث بيانات البئر بنجاح');
    }

    // حذف بئر
    public function destroy(Well $well)
    {
        // حذف البئر من قاعدة البيانات
        $well->delete();

        // إعادة التوجيه إلى صفحة الآبار مع رسالة نجاح
        return redirect()->route('wells.index')->with('success', 'تم حذف البئر بنجاح');
    }

    // عرض تفاصيل البئر
    public function show(Well $well)
    {
        return view('wells.show', compact('well')); // إرجاع عرض تفاصيل البئر
    }
}
