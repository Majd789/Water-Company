<?php

namespace App\Http\Controllers\Api;

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
    public function index(Request $request): JsonResponse
    {
        $stationId = $request->query('station_id');

        if ($stationId) {
            $station = Station::find($stationId);
            if (!$station) {
                return $this->errorResponse('.المحطة غير موجودة', 404);
            }

            // Station-specific statistics
            $statistics = [
                'station_name' => $station->station_name,
                'station_code' => $station->station_code,
                'wells_count' => Well::where('station_id', $stationId)->count(),
                'private_wells_count' => PrivateWell::where('station_id', $stationId)->count(),
                'pumping_sectors_count' => PumpingSector::where('station_id', $stationId)->count(),
                'diesel_tanks_count' => DieselTank::where('station_id', $stationId)->count(),
                'manholes_count' => Manhole::where('station_id', $stationId)->count(),
                'solar_energy_count' => SolarEnergy::where('station_id', $stationId)->count(),
                'filters_count' => Filter::where('station_id', $stationId)->count(),
                'infiltrators_count' => Infiltrator::where('station_id', $stationId)->count(),
                'electricity_transformers_count' => ElectricityTransformer::where('station_id', $stationId)->count(),
                'elevated_tanks_count' => ElevatedTank::where('station_id', $stationId)->count(),
                'disinfection_pumps_count' => DisinfectionPump::where('station_id', $stationId)->count(),
                'horizontal_pumps_count' => HorizontalPump::where('station_id', $stationId)->count(),
                'generation_groups_count' => GenerationGroup::where('station_id', $stationId)->count(),
                'electricity_hours_count' => ElectricityHour::where('station_id', $stationId)->count(),
                'ground_tanks_count' => GroundTank::where('station_id', $stationId)->count(),
                // 'maintenances_count' => Maintenance::where('station_id', $stationId)->count(),
                // 'daily_station_reports_count' => DailyStationReport::where('station_id', $stationId)->count(),
            ];
            $message = "احصائيات محطة '{$station->station_name}' جلبت بنجاح.";

        } else {
            // Global statistics
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
            $message = '.تم جلب جميع الاحصائيات العامة بنجاح ';
        }

        return $this->successResponse($statistics, $message);
    }
}
