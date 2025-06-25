<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyStationReport;
use App\Models\DieselTank;
use App\Models\DisinfectionPump;
use App\Models\ElectricityHour;
use App\Models\ElectricityTransformer;
use App\Models\ElevatedTank;
use App\Models\Filter;
use App\Models\GenerationGroup;
use App\Models\GroundTank;
use App\Models\HorizontalPump;
use App\Models\Infiltrator;
use App\Models\InstitutionProperty;
use App\Models\Maintenance;
use App\Models\Manhole;
use App\Models\PrivateWell;
use App\Models\PumpingSector;
use App\Models\SolarEnergy;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use App\Models\User;
use App\Models\Well;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponse;

class StatisticsController extends Controller
{
    use ApiResponse;
     public function index(): JsonResponse
    {
        $statistics = [
            'stations_count' => Station::count(),
            'wells_count' => Well::count(),
            'private_wells_count' => PrivateWell::count(),
            'users_count' => User::count(),
            'units_count' => Unit::count(),
            'towns_count' => Town::count(),
            'pumping_sectors_count' => PumpingSector::count(),
            'diesel_tanks_count' => DieselTank::count(),
            'manholes_count' => Manhole::count(),
            'solar_energy_count' => SolarEnergy::count(),
            'filters_count' => Filter::count(),
            'infiltrators_count' => Infiltrator::count(),
            'electricity_transformers_count' => ElectricityTransformer::count(),
            'elevated_tanks_count' => ElevatedTank::count(),
            'disinfection_pumps_count' => DisinfectionPump::count(),
            'horizontal_pumps_count' => HorizontalPump::count(),
            'generation_groups_count' => GenerationGroup::count(),
            'electricity_hours_count' => ElectricityHour::count(),
            'ground_tanks_count' => GroundTank::count(),
            // 'institution_properties_count' => InstitutionProperty::count(),
            // 'maintenances_count' => Maintenance::count(),
            // 'daily_station_reports_count' => DailyStationReport::count(),
        ];

        return $this->successResponse($statistics, 'Statistics retrieved successfully.');
    }
}
