<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\Manhole;
use App\Models\PumpingSector;
use App\Models\SolarEnergy;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use App\Models\User;
use App\Models\Well;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية مع الإحصائيات العامة أو الخاصة بمحطة.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // جلب جميع المحطات لعرضها في الفلتر
        $stations = Station::orderBy('station_name')->get();

        $stationId = $request->query('station_id');
        $selectedStation = null;

        if ($stationId) {
            $station = Station::find($stationId);
            if (!$station) {
                abort(404, 'المحطة غير موجودة.');
            }
            $selectedStation = $station;

            // إحصائيات خاصة بالمحطة المحددة
            $statistics = [
                'wells_count' => $station->wells()->count(),
                'pumping_sectors_count' => $station->pumpingSectors()->count(),
                'diesel_tanks_count' => $station->dieselTank()->count(), // <-- تم التصحيح
                'manholes_count' => $station->manholes()->count(),
                'solar_energy_count' => $station->solarEnergies()->count(),
                'filters_count' => $station->filters()->count(),
                'infiltrators_count' => $station->infiltrator()->count(), // <-- تم التصحيح
                'electricity_transformers_count' => $station->electricityTransformer()->count(), // <-- تم التصحيح
                'elevated_tanks_count' => $station->elevatedTanks()->count(),
                'disinfection_pumps_count' => $station->disinfectionPump()->count(), // <-- تم التصحيح
                'horizontal_pumps_count' => $station->horizontalPumps()->count(),
                'generation_groups_count' => $station->generationGroups()->count(),
                'electricity_hours_count' => $station->electricityHours()->count(),
                'ground_tanks_count' => $station->groundTanks()->count(),
            ];
            $message = "إحصائيات محطة: '{$station->station_name}'";

        } else {
            // إحصائيات عامة للنظام بالكامل
            $statistics = [
                'stations_count' => Station::count(),
                'users_count' => User::count(),
                'units_count' => Unit::count(),
                'towns_count' => Town::count(),
                'wells_count' => Well::count(),
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
            ];
            $message = 'الإحصائيات العامة للنظام';
        }

        // تمرير البيانات إلى الـ View
        return view('dashboard', compact('statistics', 'message', 'stations', 'selectedStation'));
    }
}
