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
use Illuminate\Support\Facades\Auth;

class StationReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:station-reports.view')->only(['index', 'show']);
        $this->middleware('permission:station-reports.create')->only(['create', 'store']);
        $this->middleware('permission:station-reports.edit')->only(['edit', 'update']);
        $this->middleware('permission:station-reports.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = StationReport::with(['station', 'unit', 'operator']);

        // Filter by user's unit if they have one
        if ($user->unit_id) {
            $query->where('unit_id', $user->unit_id);
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

        // Get filter options
        $stations = Station::when($user->unit_id, function ($q) use ($user) {
            return $q->whereHas('town', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            });
        })->get();

        $statuses = StationOperationStatus::cases();

        return view('dashboard.station-reports.index', compact('reports', 'stations', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $units = Unit::all();
        $stations = Station::when($user->unit_id, function ($q) use ($user) {
            return $q->whereHas('town', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            });
        })->get();

        $operators = User::where('level', 'station_operator')->get();
        $pumpingSectors = PumpingSector::all();

        $statuses = StationOperationStatus::cases();
        $operatingEntities = StationOperatingEntityEum::cases();
        $energyResources = EnergyResource::cases();

        return view('dashboard.station-reports.create', compact(
            'units', 'stations', 'operators', 'pumpingSectors',
            'statuses', 'operatingEntities', 'energyResources'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StationReportStoreRequest $request)
    {
        $data = $request->validated();

        // Set default values
        if (!isset($data['operator_id'])) {
            $data['operator_id'] = Auth::id();
        }

        StationReport::create($data);

        return redirect()->route('dashboard.station-reports.index')
            ->with('success', 'تم إنشاء التقرير بنجاح');
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

        $units = Unit::all();
        $stations = Station::when($user->unit_id, function ($q) use ($user) {
            return $q->whereHas('town', function ($query) use ($user) {
                $query->where('unit_id', $user->unit_id);
            });
        })->get();

        $operators = User::where('level', 'station_operator')->get();
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
