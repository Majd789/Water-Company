<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\InfiltratorsExport;
use App\Imports\InfiltratorsImport;
use App\Models\Infiltrator;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class InfiltratorController extends Controller
{
    /**
     * عرض جميع الانفلترات
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لجلب الأجهزة المرتبطة بالمحطات
        $query = Infiltrator::with('station'); // إضافة المحطات بشكل مسبق

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية الأجهزة بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // التحقق إذا كان يوجد قيمة في الطلب للبحث
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة

            // البحث في اسم المحطة وكود المحطة
            $query->whereHas('station', function ($stationQuery) use ($searchTerm) {
                $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                             ->orWhere('station_code', 'like', '%' . $searchTerm . '%'); // البحث في كود المحطة
            });
        }

        // جلب البيانات بعد التصفية مع الترقيم
        $infiltrators = $query->paginate(100); // استخدام الترقيم لعرض البيانات

        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('infiltrators.index', compact('infiltrators', 'units', 'selectedUnitId'));
    }


    public function export()
    {
        return Excel::download(new InfiltratorsExport, 'infiltrators.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new InfiltratorsImport, $request->file('file'));

        return redirect()->route('infiltrators.index')->with('success', 'تم استيراد الإنفلترات بنجاح.');
    }
    /**
     * عرض نموذج إنشاء انفلتر جديد
     */
    public function create()
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $unit = auth()->user()->unit;

        // إذا كانت هناك وحدة، جلب المحطات عبر البلدات المرتبطة بالوحدة
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
        return view('infiltrators.create', compact('stations'));
    }

     private function getAllowedInfiltratorTypes()
    {
        return [
            'VEIKONG', 'USFULL', 'LS', 'ABB', 'GROWATT', 'SMA', 'HUAWEI', 'DANFOSS',
            'FRECON', 'BAISON', 'GMTCNT', 'CELIK', 'TREST', 'TRUST', 'STAR POWER',
            'STAR NEW', 'WINGS INTERNATIONAL', 'ORIGINAL COLD', 'NGGRID', 'POWER MAX PRO',
            'FREKON', 'GELEK', 'INVT', 'ENPHASE', 'SOLAREDGE', 'GOODWE', 'VICTRON ENERGY',
            'DELTA', 'SUNGROW', 'YASKAWA', 'KACO', 'FRONIUS', 'SOLAX', 'SOLIS', 'VFD-LS',
            'RUST', 'COM', 'SHIRE', 'CLICK', 'HLUX', 'MOLTO', 'ON-GRID', 'OFF-GRID',
            'HYBRID', 'غير معروف'
        ];
    }

    /**
     * تخزين انفلتر جديد
     */
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id', // التحقق من وجود المحطة
            'infiltrator_capacity' => 'required|numeric', // استطاعة الانفلتر
            'readiness_status' => 'required|numeric', // حالة الجاهزية
           'infiltrator_type' => ['required', Rule::in($this->getAllowedInfiltratorTypes())], // نوع الانفلتر
            'notes' => 'nullable|string', // ملاحظات
        ]);

        // إنشاء الانفلتر الجديد في قاعدة البيانات
        Infiltrator::create($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('infiltrators.index')->with('success', 'تمت إضافة الانفلتر بنجاح.');
    }

    /**
     * عرض تفاصيل انفلتر معين
     */
    public function show(Infiltrator $infiltrator)
    {
        return view('infiltrators.show', compact('infiltrator'));
    }

    /**
     * عرض نموذج تعديل انفلتر
     */
    public function edit(Infiltrator $infiltrator)
    {
        // جلب جميع المحطات
        $stations = Station::all();
        return view('infiltrators.edit', compact('infiltrator', 'stations'));
    }

    /**
     * تحديث بيانات الانفلتر
     */
    public function update(Request $request, Infiltrator $infiltrator)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id', // التحقق من وجود المحطة
            'infiltrator_capacity' => 'required|numeric', // استطاعة الانفلتر
            'readiness_status' => 'required|numeric', // حالة الجاهزية
           'infiltrator_type' => ['required', Rule::in($this->getAllowedInfiltratorTypes())], // نوع الانفلتر
            'notes' => 'nullable|string', // ملاحظات
        ]);

        // تحديث بيانات الانفلتر في قاعدة البيانات
        $infiltrator->update($validated);

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('infiltrators.index')->with('success', 'تم تحديث الانفلتر بنجاح.');
    }

    /**
     * حذف انفلتر معين
     */
    public function destroy(Infiltrator $infiltrator)
    {
        // حذف الانفلتر من قاعدة البيانات
        $infiltrator->delete();

        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('infiltrators.index')->with('success', 'تم حذف الانفلتر بنجاح.');
    }
}
