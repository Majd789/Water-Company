<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\Unit;
use App\Models\Town;
use App\Models\Well; // Import the Well model

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch Stations with their related data
        $stations = Station::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with([
                'town.unit',
                'town.unit.governorate',
                'horizontalPumps',
                // Add relationships needed for filters/display later if necessary
            ])
            ->select(
                'id',
                'station_name',
                'latitude',
                'longitude',
                'town_id',
                'operational_status',
                'operator_entity',
                'station_type',
                'energy_source'
            )
            ->get();

        // Fetch Wells with their related data
        $wells = Well::whereHas('station', function ($query) {
                // Only include wells that have a station with valid lat/lng
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->with([
                'station:id,station_name,latitude,longitude,town_id', // Select necessary station fields
                'station.town:id,town_name,unit_id',
                'station.town.unit:id,unit_name,governorate_id',
                'station.town.unit.governorate:id,name',
            ])
            ->select(
                'id',
                'station_id',
                'well_name',
                'well_status',
                'well_type',
                'energy_source',
                'well_location' // Assuming this is used for lat/lng if not directly on well
            )
            ->get();

        // Fetch list of Units for filter dropdown
        $units = Unit::select('id', 'unit_name')->get();

        // Fetch list of Governorates for filter dropdown
        $governorates = Governorate::all();

        return view('dashboard', compact('stations', 'wells', 'units', 'governorates'));
    }
}
