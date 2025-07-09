<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Imports\WaterWell2Import;
use App\Models\Station;
use App\Models\WaterWell2;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AggregatedWaterWellsExport;

class WaterWell2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:water-wells2.view')->only(['index', 'show']);
        $this->middleware('permission:water-wells2.create')->only(['create', 'store']);
        $this->middleware('permission:water-wells2.edit')->only(['edit', 'update']);
        $this->middleware('permission:water-wells2.delete')->only('destroy');
        $this->middleware('permission:water-wells2.export-aggregated')->only('exportAggregated');
        $this->middleware('permission:water-wells2.import')->only(['importForm', 'import']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // الكود القديم كما هو لديك
        $userUnitId = auth()->user()->unit_id;

        $stationCodes = $userUnitId
            ? Station::whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            })->pluck('station_code')
            : null;

        $waterWells = WaterWell2::where('has_flow_meter', 'نعم')
            ->when($stationCodes, function ($query) use ($stationCodes) {
                return $query->whereIn('station_code', $stationCodes);
            })
            ->orderBy('date')
            ->orderBy('well_name')
            ->orderBy('station_code')
            ->orderBy('flow_meter_start')
            ->get();

        $groupedWells = $waterWells->groupBy(fn($well) => $well->station_code . '_' . $well->well_name);

        $filteredWells = collect();

        foreach ($groupedWells as $wells) {
            $previousEndMeter = null;
            foreach ($wells as $index => $well) {
                $actualQuantity = $well->flow_meter_end - $well->flow_meter_start;
                $soldQuantity   = $well->water_sold_quantity;

                $well->quantity_check = (abs($actualQuantity - $soldQuantity) <= $soldQuantity * 0.05) ? 'صحيحة' : 'خاطئة';

                $freeWaterAmount   = $well->free_filling_quantity * $well->water_price;
                $vehicleWaterAmount = $well->vehicle_filling_quantity * $well->water_price;
                $calculatedAmount  = ($soldQuantity * $well->water_price) - $freeWaterAmount - $vehicleWaterAmount;

                $well->price_check = (abs($calculatedAmount - $well->total_amount) <= $well->total_amount * 0.05) ? 'صحيحة' : 'خاطئة';

                if ($index === 0) {
                    $well->meter_sequence_check = 'اول ادخال';
                } else {
                    $well->meter_sequence_check = (abs($previousEndMeter - $well->flow_meter_start) <= 1) ? 'صحيح' : 'خاطئ';
                }
                $previousEndMeter = $well->flow_meter_end;

                if ($request->filter == 'incorrect') {
                    if ($well->quantity_check === 'خاطئة' || $well->price_check === 'خاطئة' || $well->meter_sequence_check === 'خاطئ') {
                        $filteredWells->push($well);
                    }
                } else {
                    $filteredWells->push($well);
                }
            }
        }

        if ($request->date_filter) {
            $filterDate = Carbon::parse($request->date_filter)->format('Y-m-d');
            $nextDate   = Carbon::parse($filterDate)->addDay()->format('Y-m-d');
            $filteredWells = $filteredWells->filter(function ($well) use ($filterDate, $nextDate) {
                $wellDate = Carbon::createFromFormat('Y-m-d', '1970-01-01')
                    ->addDays($well->date - 25569)
                    ->format('Y-m-d');
                return $wellDate === $filterDate || $wellDate === $nextDate;
            });
        }

        return view('dashboard.waterwells2.index', compact('filteredWells', 'request'));
    }

    /**
     * دالة جديدة لتجميع بيانات المناهل وإجراء الحسابات الإجمالية
     */
    public function aggregatedIndex(Request $request)
    {
        // جلب وحدة المستخدم الحالية لتصفية المحطات حسب الوحدة إن وجدت
        $userUnitId = auth()->user()->unit_id;

        $stationCodes = $userUnitId
            ? Station::whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            })->pluck('station_code')
            : null;

        // جلب سجلات المناهل التي تحتوي على عداد تدفق
        $waterWells = WaterWell2::where('has_flow_meter', 'نعم')
            ->when($stationCodes, function ($query) use ($stationCodes) {
                return $query->whereIn('station_code', $stationCodes);
            })
            ->orderBy('date')
            ->orderBy('well_name')
            ->orderBy('station_code')
            ->orderBy('flow_meter_start')
            ->get();

        // إذا كان هناك فلتر تاريخي، يتم تطبيقه هنا أيضاً دون التأثير على العمليات القديمة
        if ($request->date_filter) {
            $filterDate = Carbon::parse($request->date_filter)->format('Y-m-d');
            $nextDate   = Carbon::parse($filterDate)->addDay()->format('Y-m-d');
            $waterWells = $waterWells->filter(function ($well) use ($filterDate, $nextDate) {
                $wellDate = Carbon::createFromFormat('Y-m-d', '1970-01-01')
                    ->addDays($well->date - 25569)
                    ->format('Y-m-d');
                return $wellDate === $filterDate || $wellDate === $nextDate;
            });
        }

        /*
         * تجميع البيانات حسب اسم المنهل
         * حتى وإن تكرر اسم المنهل يتم جمع القيم الخاصة بكل سجل
         */
        $groupedWells = $waterWells->groupBy('well_name');

        $aggregatedResults = $groupedWells->map(function ($wells, $wellName) {
            // إجمالي الكمية المقاسة (فرق القراءة النهائية عن القراءة الابتدائية)
            $totalMeasuredQuantity = $wells->sum(function ($well) {
                return $well->flow_meter_end - $well->flow_meter_start;
            });

            // إجمالي كمية المياه المباعة
            $totalSoldQuantity = $wells->sum('water_sold_quantity');

            // إجمالي كمية المياه المجانية
            $totalFreeQuantity = $wells->sum('free_filling_quantity');

            // إجمالي كمية تعبئة المركبات
            $totalVehicleQuantity = $wells->sum('vehicle_filling_quantity');

            // نفترض أن سعر المياه ثابت داخل سجلات المنهل الواحد
            $waterPrice = $wells->first()->water_price;

            /*
             * حساب القيمة الإجمالية للمنهل:
             * قيمة المبيعات = كمية البيع * سعر المياه
             * ثم نقوم بخصم قيمة المياه المجانية وتعبئة المركبات
             */
            $totalAmount = ($totalSoldQuantity * $waterPrice)
                - ($totalFreeQuantity * $waterPrice)
                - ($totalVehicleQuantity * $waterPrice);

            return [
                'well_name'          => $wellName,
                'total_measured_qty' => $totalMeasuredQuantity,
                'total_sold_qty'     => $totalSoldQuantity,
                'total_free_qty'     => $totalFreeQuantity,
                'total_vehicle_qty'  => $totalVehicleQuantity,
                'water_price'        => $waterPrice,
                'total_amount'       => $totalAmount,
            ];
        })->values();

        // عرض النتائج المجمعة في صفحة منفصلة (أو نفس الصفحة بحسب تصميمك)
        return view('dashboard.waterwells2.aggregated', compact('aggregatedResults', 'request'));
    }





    public function show($id)
    {
        $waterWell = WaterWell2::findOrFail($id);
        return view('dashboard.waterwells2.show', compact('waterWell'));
    }

    public function importForm()
    {
        return view('dashboard.waterwells2.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new WaterWell2Import, $request->file('file'));

        return redirect()->route('dashboard.waterwells2.index')->with('success', 'تم استيراد البيانات بنجاح!');
    }
         public function exportAggregated(Request $request)
    {
        // ------------------ نسخ نفس منطق جلب البيانات من دالة aggregatedIndex ------------------

        $userUnitId = auth()->user()->unit_id;

        $stationCodes = $userUnitId
            ? Station::whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            })->pluck('station_code')
            : null;

        $waterWells = WaterWell2::where('has_flow_meter', 'نعم')
            ->when($stationCodes, function ($query) use ($stationCodes) {
                return $query->whereIn('station_code', $stationCodes);
            })
            ->orderBy('date')
            ->orderBy('well_name')
            ->orderBy('station_code')
            ->orderBy('flow_meter_start')
            ->get();

        if ($request->date_filter) {
            $filterDate = Carbon::parse($request->date_filter)->format('Y-m-d');
            $nextDate   = Carbon::parse($filterDate)->addDay()->format('Y-m-d');
            $waterWells = $waterWells->filter(function ($well) use ($filterDate, $nextDate) {
                $wellDate = Carbon::createFromFormat('Y-m-d', '1970-01-01')
                    ->addDays($well->date - 25569)
                    ->format('Y-m-d');
                return $wellDate === $filterDate || $wellDate === $nextDate;
            });
        }

        $groupedWells = $waterWells->groupBy('well_name');

        $aggregatedResults = $groupedWells->map(function ($wells, $wellName) {
            $totalMeasuredQuantity = $wells->sum(fn($w) => $w->flow_meter_end - $w->flow_meter_start);
            $totalSoldQuantity = $wells->sum('water_sold_quantity');
            $totalFreeQuantity = $wells->sum('free_filling_quantity');
            $totalVehicleQuantity = $wells->sum('vehicle_filling_quantity');
            $waterPrice = $wells->first()->water_price ?? 0;
            $totalAmount = ($totalSoldQuantity * $waterPrice) - ($totalFreeQuantity * $waterPrice) - ($totalVehicleQuantity * $waterPrice);

            return [
                'well_name'          => $wellName,
                'total_measured_qty' => $totalMeasuredQuantity,
                'total_sold_qty'     => $totalSoldQuantity,
                'total_free_qty'     => $totalFreeQuantity,
                'total_vehicle_qty'  => $totalVehicleQuantity,
                'water_price'        => $waterPrice,
                'total_amount'       => $totalAmount,
            ];
        })->values();

        // ------------------ نهاية نسخ المنطق ------------------

        // اسم الملف
        $fileName = 'aggregated_water_wells_' . now()->format('Y-m-d') . '.xlsx';

        // استدعاء فئة التصدير وتمرير البيانات المجمعة لها
        return Excel::download(new AggregatedWaterWellsExport($aggregatedResults), $fileName);
    }
    /**
     * عرض صفحة إنشاء سجل جديد
     */
    public function create()
    {
        return view('dashboard.waterwells2.create');
    }

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

        WaterWell2::create($validated);

        return redirect()->route('dashboard.waterwells2.index')->with('success', 'تم إضافة المنهل بنجاح');
    }

    public function edit($id)
    {
        $waterWell = WaterWell2::findOrFail($id);
        return view('dashboard.waterwells2.edit', compact('waterWell'));
    }

    public function update(Request $request, $id)
    {
        $waterWell = WaterWell2::findOrFail($id);

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

        return redirect()->route('dashboard.waterwells2.show', $waterWell->id)->with('success', 'تم التعديل بنجاح');
    }



    public function destroy(Request $request)
    {
        $user = auth()->user();
        $userUnitId = $user->unit_id;
        $roleId = $user->role_id;

        // إذا كان المستخدم لديه صلاحية "admin" و"unit_id" فارغ، يتم حذف جميع البيانات
        if ($roleId === 'admin' && !$userUnitId) {
            WaterWell2::query()->delete();
            DB::statement('ALTER TABLE water_wells2 AUTO_INCREMENT = 1'); // إعادة الترقيم

            return redirect()->route('dashboard.waterwells2.index')->with('success', 'تم حذف جميع التقارير وإعادة ترقيم الـ ID.');
        }

        // في حالة أن المستخدم لديه "unit_id"، يتم الحذف بناءً على وحدة المستخدم
        if ($userUnitId) {
            WaterWell2::whereHas('station', function ($query) use ($userUnitId) {
                $query->whereHas('town', function ($query) use ($userUnitId) {
                    $query->where('unit_id', $userUnitId);
                });
            })->delete();

            // التحقق مما إذا كان الجدول فارغًا قبل إعادة الترقيم
            if (WaterWell2::count() === 0) {
                DB::statement('ALTER TABLE water_wells2 AUTO_INCREMENT = 1');
            }

            return redirect()->route('dashboard.waterwells2.index')->with('success', 'تم حذف جميع التقارير الخاصة بوحدتك وإعادة ترقيم الـ ID.');
        }

        return redirect()->route('dashboard.waterwells2.index')->with('error', 'لا يمكن حذف البيانات لأن المستخدم ليس لديه صلاحية.');
    }



}
