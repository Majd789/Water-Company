<?php

namespace App\Http\Controllers\Dashboard;

use App\Enum\EnergyResource;
use App\Enum\OperatingEntityName;
use App\Enum\StationOperatingEntityEum;
use App\Enum\StationOperationStatus;
use App\Enum\UserLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\StationReportStoreRequest;
use App\Http\Requests\StationReportUpdateRequest;
use App\Models\PumpingSector;
use App\Models\Station;
use App\Models\StationReport;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationReportsController extends Controller
{
    public function __construct()
    {
        // Permissions Middleware
        $this->middleware('permission:station_reports.view')->only(['index', 'show', 'monitoringDashboard', 'submissionStatusDashboard']);
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

        // Filter reports based on user level
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $query->where('unit_id', $user->unit_id);
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $query->where('station_id', $user->station_id);
                break;
        }

        // Apply search filters
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

        $reports = $query->latest('report_date')->paginate(15);

        // Prepare data for filter dropdowns
        $stationsQuery = Station::query();
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn ($q) => $q->where('unit_id', $user->unit_id));
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
        $stationsQuery = Station::query();
        $operatorQuery = User::query()->where('level', UserLevel::STATION_OPERATOR);
        $selectedStationId = null;

        // Filter stations and operators based on user level
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn ($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $stationsQuery->where('id', $user->station_id);
                $operatorQuery->where('station_id', $user->station_id);
                $selectedStationId = $user->station_id;
                break;
        }

        return view('dashboard.station-reports.create', [
            'stations' => $stationsQuery->get(),
            'operators' => $operatorQuery->get(),
            'units' => Unit::all(),
            'pumpingSectors' => PumpingSector::all(),
            'statuses' => StationOperationStatus::cases(),
            'operatingEntities' => StationOperatingEntityEum::cases(),
            'energyResources' => EnergyResource::cases(),
            'organizationNames' => OperatingEntityName::cases(),
            'selectedStationId' => $selectedStationId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationReportStoreRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Assign operator_id if not set (for operators creating their own reports)
        $data['operator_id'] = $data['operator_id'] ?? $user->id;

        // Securely assign unit_id and station_id based on user level
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $station = Station::with('town')->findOrFail($data['station_id']);
                if ($station->town->unit_id != $user->unit_id) {
                    return back()->with('error', 'ليس لديك صلاحية لإضافة تقرير لهذه المحطة.');
                }
                $data['unit_id'] = $user->unit_id;
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $data['station_id'] = $user->station_id;
                $data['unit_id'] = $user->unit_id;
                break;
        }

        StationReport::create($data);

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم إنشاء التقرير بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StationReport $stationReport)
    {
        $stationReport->load(['station', 'unit', 'operator', 'pumpingSector']);

        return view('dashboard.station-reports.show', compact('stationReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StationReport $stationReport)
    {
        $user = Auth::user();
        $stationsQuery = Station::query();
        $operatorQuery = User::query()->where('level', UserLevel::STATION_OPERATOR);

        // Filter stations and operators based on user level
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn ($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $stationsQuery->where('id', $user->station_id);
                $operatorQuery->where('station_id', $user->station_id);
                break;
        }

        return view('dashboard.station-reports.edit', [
            'stationReport' => $stationReport,
            'stations' => $stationsQuery->get(),
            'operators' => $operatorQuery->get(),
            'units' => Unit::all(),
            'pumpingSectors' => PumpingSector::all(),
            'statuses' => StationOperationStatus::cases(),
            'operatingEntities' => StationOperatingEntityEum::cases(),
            'energyResources' => EnergyResource::cases(),
            'organizationNames' => OperatingEntityName::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StationReportUpdateRequest $request, StationReport $stationReport)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $stationReport->update($data);

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم تحديث التقرير بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StationReport $stationReport)
    {
        $stationReport->delete();

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم حذف التقرير بنجاح.');
    }




   public function submissionStatusDashboard(Request $request)
    {
        // 1. تحديد الشهر والسنة
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        // 2. جلب قائمة المحطات مع احترام الصلاحيات والفلترة وحالة المحطة
        $user = Auth::user();
        $stationsQuery = Station::query();
        $units = collect();
        $selectedUnitId = $request->input('unit_id');

        // === التحسين الجديد هنا ===
        // عرض المحطات التي ترسل تقارير فقط (is_verified = true)
        $stationsQuery->where('is_verified', true);
        // ========================

        // تحديد الوحدات المتاحة للفلترة فقط لمدير النظام
        if ($user->level === UserLevel::ADMIN) {
            $units = Unit::orderBy('unit_name')->get();
        }

        // تطبيق الفلترة بناءً على صلاحيات المستخدم والوحدة المختارة
        switch ($user->level) {
            case UserLevel::UNIT_ADMIN:
                $stationsQuery->whereHas('town', fn($q) => $q->where('unit_id', $user->unit_id));
                break;
            case UserLevel::STATION_ADMIN:
            case UserLevel::STATION_OPERATOR:
                $stationsQuery->where('id', $user->station_id);
                break;
            case UserLevel::ADMIN:
                if ($selectedUnitId) {
                    $stationsQuery->whereHas('town', fn($q) => $q->where('unit_id', $selectedUnitId));
                }
                break;
            default:
                $stationsQuery->whereRaw('1 = 0');
                break;
        }

        $stations = $stationsQuery->orderBy('station_name')->get();
        $stationIds = $stations->pluck('id');

        // 3. جلب التقارير (يبقى كما هو)
        $reports = collect();
        if ($stationIds->isNotEmpty()) {
            $reports = StationReport::whereIn('station_id', $stationIds)
                ->whereYear('report_date', $year)
                ->whereMonth('report_date', $month)
                ->select('station_id', 'report_date')
                ->get();
        }
        
        // 4. بناء مصفوفة البحث (يبقى كما هو)
        $reportMatrix = [];
        foreach ($reports as $report) {
            $day = Carbon::parse($report->report_date)->day;
            $reportMatrix[$report->station_id . '-' . $day] = true;
        }
        
        // 5. تجهيز بيانات الفلاتر (يبقى كما هو)
        $years = range(Carbon::now()->year, Carbon::now()->year - 5);
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو',
            7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];

        return view('dashboard.station-reports.submission-status', compact(
            'stations', 'year', 'month', 'daysInMonth', 'reportMatrix', 'years', 'months',
            'units', 'selectedUnitId'
        ));
    }
    /**
     * Display the printable paper-format monthly report.
     */
    public function showPaperReport(Station $station, $year, $month)
    {
        // Eager load relationships to prevent N+1 query issues
        $monthlyReports = StationReport::with(['operator', 'pumpingSector'])
            ->where('station_id', $station->id)
            ->whereYear('report_date', $year)
            ->whereMonth('report_date', $month)
            ->orderBy('report_date', 'asc')
            ->get();

        $reportsByDay = $monthlyReports->keyBy(fn ($report) => Carbon::parse($report->report_date)->day);

        // Get operating entity info from the first available report
        $firstReport = $monthlyReports->first();
        $operatingEntityName = 'غير محدد';
        if ($firstReport?->operating_entity) {
            $operatingEntityName = $firstReport->operating_entity->getLabel();
            if ($firstReport->operating_entity_name) {
                $operatingEntityName .= ' - ' . $firstReport->operating_entity_name;
            }
        }

        $monthlyTotals = [
            'operating_hours' => $monthlyReports->sum('operating_hours'),
            'electricity_power_kwh' => $monthlyReports->sum('electricity_power_kwh'),
            'solar_hours' => $monthlyReports->sum('solar_hours'),
            'generator_hours' => $monthlyReports->sum('generator_hours'),
            'water_pumped_m3' => $monthlyReports->sum('water_pumped_m3'),
            'quantity_of_electricity_meter_charged_kwh' => $monthlyReports->sum('quantity_of_electricity_meter_charged_kwh'),
            'diesel_consumed_liters' => $monthlyReports->sum('diesel_consumed_liters'),
        ];

        return view('dashboard.station-reports.paper', compact(
            'station', 'year', 'month', 'reportsByDay', 'monthlyTotals', 'operatingEntityName'
        ));
    }
}