<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StationReportStoreRequest;
use App\Http\Requests\StationReportUpdateRequest;
use App\Models\StationReport;
use App\Models\Station;
use App\Models\Unit;
use App\Models\User;
use App\Models\PumpingSector;
use App\Enum\StationOperationStatus;
use App\Enum\StationOperatingEntityEum;
use App\Enum\EnergyResource;
use Illuminate\Http\Request;
use App\Enum\UserLevel;
use App\Enum\OperatingEntityName;
use Illuminate\Support\Facades\Auth;

class StationReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:station_reports.view')->only(['index', 'show']);
        $this->middleware('permission:station_reports.create')->only(['create', 'store']);
        $this->middleware('permission:station_reports.edit')->only(['edit', 'update']);
        $this->middleware('permission:station_reports.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        $query = StationReport::with(['station', 'unit', 'operator']);

        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $query->where('unit_id', $user->unit_id);
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $query->where('station_id', $user->station_id);
                break;
            // case UserLevel::ADMIN:
            // لا يتم تطبيق أي فلترة، المدير يرى كل التقارير
        }

        // Apply filters
        if ($request->filled('station_id')) {
            $query->where('station_id', $request->station_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->orderBy('report_date', 'desc')->paginate(15);

        $stationsQuery = Station::query();
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $stationsQuery->where('id', $user->station_id);
                break;
        }
        $stations = $stationsQuery->get();

        $statuses = StationOperationStatus::cases();

        return view('dashboard.station-reports.index', compact('reports', 'stations', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $organizationNames = OperatingEntityName::cases();
        $stationsQuery = Station::query();
        $operatorQuery = User::query()->where('level', 'station_operator');
        $selectedStationId = null; // متغير لتحديد المحطة تلقائياً للمشغل
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                // مدير الوحدة يرى فقط المحطات التابعة لوحدته
                $stationsQuery->whereHas('town', fn($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $operatorQuery->where('station_id' ,Auth()->user()->station_id);
                // مدير المحطة والمشغل يرى فقط محطته
                $stationsQuery->where('id', $user->station_id);
                $selectedStationId = $user->station_id; // تحديد المحطة تلقائياً
                break;
        }
        $stations = $stationsQuery->get();
        $operators = $operatorQuery->get();
        $units = Unit::all();
        $pumpingSectors = PumpingSector::all();
        $statuses = StationOperationStatus::cases();
        $operatingEntities = StationOperatingEntityEum::cases();
        $energyResources = EnergyResource::cases();

        return view('dashboard.station-reports.create', compact(
            'units', 'stations', 'operators', 'pumpingSectors',
            'statuses', 'operatingEntities', 'energyResources', 'organizationNames',
            'selectedStationId' // [إضافة] تمرير متغير المحطة المحددة إلى الواجهة
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationReportStoreRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Set default values
        if (!isset($data['operator_id'])) {
            $data['operator_id'] = Auth::id();
        }
         // [تعديل] بداية: فرض قيم IDs الصحيحة لضمان الأمان وعدم التلاعب بالبيانات
         switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                // التأكد من أن المحطة المختارة تابعة لنفس وحدة المدير
                $station = Station::with('town')->findOrFail($data['station_id']);
                if ($station->town->unit_id != $user->unit_id) {
                    // إذا حاول مدير وحدة إضافة تقرير لمحطة خارج وحدته، يتم منعه
                    return back()->with('error', 'ليس لديك صلاحية لإضافة تقرير لهذه المحطة.');
                }
                $data['unit_id'] = $user->unit_id;
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                // فرض رقم المحطة ورقم الوحدة الخاص بالمستخدم لتجنب أي تلاعب
                $data['station_id'] = $user->station_id;
                $data['unit_id'] = $user->unit_id;
                break;
        }
        // [تعديل] نهاية: فرض القيم

        StationReport::create($data);

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم إنشاء التقرير بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(StationReport $stationReport)
    {
        $stationReport->load(['station', 'unit', 'operator']);

        return view('dashboard.station-reports.show', compact('stationReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StationReport $stationReport)
    {
        $user = Auth::user();
        $stationsQuery = Station::query();
        $operatorQuery = User::query()->where('level', 'station_operator');
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $operatorQuery->where('station_id' ,Auth()->user()->station_id);
                $stationsQuery->where('id', $user->station_id);
                break;
        }
        $stations = $stationsQuery->get();
        $units = Unit::all();
        $operators = $operatorQuery->get();
        $pumpingSectors = PumpingSector::all();

        $statuses = StationOperationStatus::cases();
        $operatingEntities = StationOperatingEntityEum::cases();
        $energyResources = EnergyResource::cases();

        return view('dashboard.station-reports.edit', compact(
            'stationReport', 'units', 'stations', 'operators', 'pumpingSectors',
            'statuses', 'operatingEntities', 'energyResources'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StationReportUpdateRequest $request, StationReport $stationReport)
    {
        $data = $request->validated();

        $stationReport->update($data);

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم تحديث التقرير بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationReport $stationReport)
    {
        $stationReport->delete();

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم حذف التقرير بنجاح');
    }
}
