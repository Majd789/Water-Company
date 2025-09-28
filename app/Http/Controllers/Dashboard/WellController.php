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
    public function __construct()
    {
        $this->middleware('permission:wells.view')->only(['index', 'show']);
        $this->middleware('permission:wells.create')->only(['create', 'store']);
        $this->middleware('permission:wells.edit')->only(['edit', 'update']);
        $this->middleware('permission:wells.delete')->only('destroy');
    }
    private function getAllowedEnergySources()
    {
        return [
            'لا يوجد', 'كهرباء', 'مولدة', 'طاقة شمسية', 'كهرباء و مولدة',
            'كهرباء و طاقة شمسية', 'مولدة و طاقة شمسية', 'كهرباء و مولدة و طاقة شمسية'
        ];
    }
   public function index(Request $request)
    {
        $units = Unit::all();
        $userUnitId = auth()->user()->unit_id;
        $query = Well::query();

        $selectedUnitId = $request->unit_id ?? $userUnitId;

        if (!empty($selectedUnitId)) {
            $towns = Town::where('unit_id', $selectedUnitId)->get();
            $stationIds = Station::whereIn('town_id', $towns->pluck('id'))->pluck('id');
            $query->whereIn('station_id', $stationIds);
        } else {
            $towns = Town::all();
        }

        // استخدام الترقيم الفعال بدلاً من جلب كل البيانات مرة واحدة
        $wells = $query->with('station')->paginate(1000);

        // مصفوفة لترجمة أسماء الحقول إلى العربية
        $fieldTranslations = [
            'well_flow' => 'التدفق',
            'static_depth' => 'العمق الساكن',
            'dynamic_depth' => 'العمق الديناميكي',
            'drilling_depth' => 'عمق الحفر',
            'pump_installation_depth' => 'عمق تركيب المضخة',
            'pump_capacity' => 'استطاعة المضخة',
            'actual_pump_flow' => 'التدفق الفعلي للمضخة',
            'pump_brand_model' => 'ماركة المضخة',
            'energy_source' => 'مصدر الطاقة',
        ];

        foreach ($wells as $well) {
            $well->has_violation = false;
            $violationReasons = [];

            if ($well->well_status == 'يعمل') {
                if ($well->static_depth !== null && $well->dynamic_depth !== null && $well->static_depth >= $well->dynamic_depth) $violationReasons[] = 'العمق الساكن يجب أن يكون أصغر من الديناميكي.';
                if ($well->drilling_depth !== null) {
                    if ($well->static_depth !== null && $well->drilling_depth <= $well->static_depth) $violationReasons[] = 'عمق الحفر يجب أن يكون أكبر من الساكن.';
                    if ($well->dynamic_depth !== null && $well->drilling_depth <= $well->dynamic_depth) $violationReasons[] = 'عمق الحفر يجب أن يكون أكبر من الديناميكي.';
                }
                if ($well->pump_installation_depth !== null) {
                    if ($well->dynamic_depth !== null && $well->pump_installation_depth < $well->dynamic_depth) $violationReasons[] = 'عمق تركيب المضخة يجب أن يكون >= الديناميكي.';
                    if ($well->drilling_depth !== null && $well->pump_installation_depth >= $well->drilling_depth) $violationReasons[] = 'عمق تركيب المضخة يجب أن يكون < عمق الحفر.';
                }if ($well->energy_source == 'لا يوجد') {
                $violationReasons[] = 'البئر يعمل لكن مصدر الطاقة المحدد هو "لا يوجد".';
            }

                foreach($fieldTranslations as $field => $translation) {
                    if (empty($well->$field)) {
                        $violationReasons[] = "البئر يعمل لكن حقل '{$translation}' فارغ.";
                    }
                }
            }
            elseif ($well->well_status == 'متوقف') {
                if (empty($well->stop_reason)) {
                    $violationReasons[] = 'البئر متوقف ولكن سبب التوقف غير مسجل.';
                }
            }

            if (empty($well->well_type)) {
                $violationReasons[] = 'نوع البئر غير محدد (جوفي/سطحي).';
            }

            if (!empty($violationReasons)) {
                $well->has_violation = true;
                $well->violation_reason = implode("\n", array_unique($violationReasons));
            } else {
                $well->violation_reason = '';
            }
        }

        return view('dashboard.wells.index', compact('wells', 'units', 'towns'));
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

        return redirect()->route('dashboard.wells.index')->with('success', 'تم استيراد الآبار بنجاح.');
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

        return view('dashboard.wells.create', compact('stations', 'towns'));
    }
    // حفظ بئر جديد
    public function store(Request $request)
    {
         $allowedPumpBrands = [
            'ATURIA', 'CHINESE', 'GRUNDFOS', 'RED JACKET', 'JET', 'LOWARA',
            'LOWARA/EU', 'LOWARA/FRANKLIN', 'LOWARA/VOGEL', 'PLUGER', 'RITZ',
            'ROVATTI', 'VANSAN', 'WILLO', 'غير معروف','لا يوجد'
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
        return redirect()->route('dashboard.wells.index')->with('success', 'تم إضافة البئر بنجاح');
    }

    // عرض نموذج تعديل بئر
    public function edit(Well $well)
    {
        $stations = Station::all(); // جلب جميع المحطات
        return view('dashboard.wells.edit', compact('well', 'stations')); // إرجاع العرض مع البيانات
    }

    // تحديث بيانات البئر
    public function update(Request $request, Well $well)
    {
        $allowedPumpBrands = [
            'ATURIA', 'CHINESE', 'GRUNDFOS', 'RED JACKET', 'JET', 'LOWARA',
            'LOWARA/EU', 'LOWARA/FRANKLIN', 'LOWARA/VOGEL', 'PLUGER', 'RITZ',
            'ROVATTI', 'VANSAN', 'WILLO', 'غير معروف','لا يوجد'
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
        return redirect()->route('dashboard.wells.index')->with('success', 'تم تحديث بيانات البئر بنجاح');
    }

    // حذف بئر
    public function destroy(Well $well)
    {
        // حذف البئر من قاعدة البيانات
        $well->delete();

        // إعادة التوجيه إلى صفحة الآبار مع رسالة نجاح
        return redirect()->route('dashboard.wells.index')->with('success', 'تم حذف البئر بنجاح');
    }

    // عرض تفاصيل البئر
    public function show(Well $well)
    {
        return view('dashboard.wells.show', compact('well')); // إرجاع عرض تفاصيل البئر
    }
}
