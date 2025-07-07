<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Exports\ElectricityTransformersExport;
use App\Imports\ElectricityTransformersImport;
use App\Models\Station;
use App\Models\ElectricityTransformer;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ElectricityTransformerController extends Controller
{
    /**
     * عرض جميع المحولات الكهربائية
     */
    public function index(Request $request)
    {
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;

        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استعلام لجلب المحولات مع المحطات
        $query = ElectricityTransformer::with('station');

        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية المحطات بناءً على الوحدة المختارة
        if (!empty($selectedUnitId)) {
            $query->whereHas('station.town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // التحقق من وجود نص للبحث
        if ($request->filled('search')) {
            $searchTerm = trim($request->search); // إزالة المسافات الزائدة

            // تصفية البيانات بناءً على اسم المحطة أو كود المحطة
            $query->whereHas('station', function ($stationQuery) use ($searchTerm) {
                $stationQuery->where('station_name', 'like', '%' . $searchTerm . '%')
                             ->orWhere('station_code', 'like', '%' . $searchTerm . '%');
            });
        }

        // جلب البيانات مع التصفية والصفحات
        $transformers = $query->paginate(10000);

        // تمرير البيانات إلى العرض مع الوحدات لاستخدامها في الفلترة
        return view('electricity-transformers.index', compact('transformers', 'units', 'selectedUnitId'));
    }



    public function export()
    {
        return Excel::download(new ElectricityTransformersExport, 'electricity_transformers.xlsx');
    }
    public function import(Request $request)
    {
        // التحقق من صحة الملف
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // استيراد البيانات
        Excel::import(new ElectricityTransformersImport, $request->file('file'));

        return redirect()->route('electricity-transformers.index')->with('success', 'تم استيراد محولات الكهرباء بنجاح.');
    }
    /**
     * عرض نموذج إنشاء محولة جديدة
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
        return view('electricity-transformers.create', compact('stations'));
    }

    /**
     * تخزين محولة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'operational_status' => 'required|in:تعمل,متوقفة',
            'transformer_capacity' => 'required|numeric',
            'distance_from_station' => 'required|numeric',
            'is_station_transformer' => 'required|boolean',
            'talk_about_station_transformer' => 'nullable|string',
            'is_capacity_sufficient' => 'required|boolean',
            'how_mush_capacity_need' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        ElectricityTransformer::create($request->all());

        return redirect()->route('electricity-transformers.index')->with('success', 'تمت إضافة المحولة بنجاح.');
    }

    /**
     * عرض تفاصيل محولة معينة
     */
    public function show(ElectricityTransformer $electricityTransformer)
    {
        return view('electricity-transformers.show', compact('electricityTransformer'));
    }

    /**
     * عرض نموذج تعديل المحولة
     */
    public function edit(ElectricityTransformer $electricityTransformer)
    {
        $stations = Station::all();
        return view('electricity-transformers.edit', compact('electricityTransformer', 'stations'));
    }

    /**
     * تحديث بيانات المحولة
     */
    public function update(Request $request, ElectricityTransformer $electricityTransformer)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'operational_status' => 'required|in:تعمل,متوقفة',
            'transformer_capacity' => 'required|numeric',
            'distance_from_station' => 'required|numeric',
            'is_station_transformer' => 'required|boolean',
            'talk_about_station_transformer' => 'nullable|string',
            'is_capacity_sufficient' => 'required|boolean',
            'how_mush_capacity_need' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $electricityTransformer->update($request->all());

        return redirect()->route('electricity-transformers.index')->with('success', 'تم تحديث المحولة بنجاح.');
    }

    /**
     * حذف محولة معينة
     */
    public function destroy(ElectricityTransformer $electricityTransformer)
    {
        $electricityTransformer->delete();

        return redirect()->route('electricity-transformers.index')->with('success', 'تم حذف المحولة بنجاح.');
    }
}
