<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Exports\DisinfectionPumpsExport;
use App\Imports\DisinfectionPumpsImport;
use App\Models\DisinfectionPump;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DisinfectionPumpController extends Controller
{
    /**
     * عرض جميع مضخات التعقيم
     */
    public function index(Request $request)
    {
        // استرجاع جميع الوحدات
        $units = Unit::all();

        // الحصول على وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = auth()->user()->unit_id;

        // إنشاء استعلام لجلب مضخات التعقيم
        $query = DisinfectionPump::with('station');

        // تحديد الوحدة المختارة (إما من المستخدم أو من الطلب)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        if (!empty($selectedUnitId)) {
            // تصفية مضخات التعقيم بناءً على الوحدة المختارة
            $query->whereHas('station.town', function ($q) use ($selectedUnitId) {
                $q->where('unit_id', $selectedUnitId);
            });
        }

        // تصفية المضخات بناءً على البلدة المختارة
        if ($request->has('town_id') && $request->town_id != '') {
            $query->whereHas('station', function ($q) use ($request) {
                $q->where('town_id', $request->town_id);
            });
        }

        // البحث باستخدام نص واحد يشمل جميع الحقول ذات الصلة
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('station', function ($stationQuery) use ($searchTerm) {
                    $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('disinfection_pump_status', 'like', '%' . $searchTerm . '%')
                ->orWhere('pump_brand_model', 'like', '%' . $searchTerm . '%');
            });
        }

        // جلب البيانات مع التصفية والصفحات
        $disinfectionPumps = $query->paginate(10000);

        // عرض البيانات في الصفحة
        return view('disinfection_pumps.index', compact('disinfectionPumps', 'units'));
    }


    public function export()
    {
        return Excel::download(new DisinfectionPumpsExport, 'disinfection_pumps.xlsx');
    }

    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new DisinfectionPumpsImport, $request->file('file'));

        return redirect()->route('disinfection_pumps.index')->with('success', 'تم استيراد مضخات التعقيم بنجاح.');
    }

    /**
     * عرض نموذج إنشاء مضخة تعقيم جديدة
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

        return view('disinfection_pumps.create', compact('stations'));
    }

    private function getAllowedPumpBrands()
        {
            return [
                'TEKNA EVO', 'SEKO', 'AQUA', 'BETA', 'Sempom', 'SACO',
                'Grundfos', 'Antech', 'FCE', 'SEL', 'غير معروف'
            ];
        }
    /**
     * تخزين مضخة تعقيم جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'disinfection_pump_status' => 'nullable|in:يعمل,متوقف',
           'pump_brand_model' => ['nullable', Rule::in($this->getAllowedPumpBrands())],
            'pump_flow_rate' => 'nullable|numeric|min:0',
            'operating_pressure' => 'nullable|numeric|min:0',
            'technical_condition' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DisinfectionPump::create($request->all());

        return redirect()->route('disinfection_pumps.index')->with('success', 'تمت إضافة مضخة التعقيم بنجاح.');
    }

    /**
     * عرض تفاصيل مضخة تعقيم معينة
     */
    public function show($id)
    {
        $disinfectionPump = DisinfectionPump::find($id);

        if (!$disinfectionPump) {
            return redirect()->route('disinfection_pumps.index')->with('error', 'السجل غير موجود.');
        }

        return view('disinfection_pumps.show', compact('disinfectionPump'));
    }



    /**
     * عرض نموذج تعديل مضخة تعقيم
     */
    public function edit(DisinfectionPump $disinfectionPump)
    {
        $stations = Station::all();
        return view('disinfection_pumps.edit', compact('disinfectionPump', 'stations'));
    }

    /**
     * تحديث بيانات مضخة التعقيم
     */
    public function update(Request $request, DisinfectionPump $disinfectionPump)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'disinfection_pump_status' => 'nullable|in:يعمل,متوقف',
            'pump_brand_model' => ['nullable', Rule::in($this->getAllowedPumpBrands())],
            'pump_flow_rate' => 'nullable|numeric|min:0',
            'operating_pressure' => 'nullable|numeric|min:0',
            'technical_condition' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $disinfectionPump->update($request->all());

        return redirect()->route('disinfection_pumps.index')->with('success', 'تم تحديث بيانات مضخة التعقيم بنجاح.');
    }

    /**
     * حذف مضخة تعقيم معينة
     */
    public function destroy(DisinfectionPump $disinfectionPump)
    {
        $disinfectionPump->delete();

        return redirect()->route('disinfection_pumps.index')->with('success', 'تم حذف مضخة التعقيم بنجاح.');
    }
}
