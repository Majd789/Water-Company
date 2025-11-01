<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DieselTank;
use App\Models\ElevatedTank;
use App\Models\GroundTank;
use App\Models\SolarEnergy;
use App\Models\Station;
use App\Models\Unit;
use App\Models\User;
use App\Models\Well;
use App\Models\WellLicense;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
        $user = Auth::user();
        $selectedStation = null;
        $statistics = [];
        $geoJsonData = [];

        // ==========================================================
        // 1. تحديد النطاق (Scope) بناءً على دور المستخدم
        // ==========================================================
        $stationQuery = Station::query();
        $baseMessage = 'نظرة عامة وشاملة على النظام';

        // إذا كان المستخدم مرتبطاً بوحدة، يتم تضييق نطاق البحث ليشمل محطات هذه الوحدة فقط
        if ($user->unit_id) {
            $unit = Unit::find($user->unit_id);
            if ($unit) {
                $townIds = $unit->towns()->pluck('id');
                $stationQuery->whereIn('town_id', $townIds);
                $baseMessage = "نظرة عامة على وحدة: {$unit->unit_name}";
            }
        }

        // جلب قائمة المحطات المتاحة للمستخدم لعرضها في القائمة المنسدلة
        $stationsForDropdown = $stationQuery->clone()->orderBy('station_name')->get();
        $stationId = $request->query('station_id');

        if ($stationId && $station = $stationQuery->clone()->withCount(
            'wells', 'pumpingSectors', 'dieselTank', 'manholes', 'solarEnergies', 'filters',
            'infiltrator', 'electricityTransformer', 'elevatedTanks', 'disinfectionPump',
            'horizontalPumps', 'generationGroups', 'electricityHours', 'groundTanks'
            )->find($stationId)) {

            // ==========================================================
            // 2. حساب الإحصائيات لمحطة واحدة محددة
            // ==========================================================
            $selectedStation = $station;
            $message = "إحصائيات محطة: '{$station->station_name}'";

            // استخدام العلاقات المحسوبة `withCount` لتحسين الأداء
            $statistics = [
                'wells_count' => $station->wells_count,
                'pumping_sectors_count' => $station->pumping_sectors_count,
                'diesel_tanks_count' => $station->diesel_tank_count,
                'manholes_count' => $station->manholes_count,
                'solar_energy_count' => $station->solar_energies_count,
                'filters_count' => $station->filters_count,
                'infiltrators_count' => $station->infiltrator_count,
                'electricity_transformers_count' => $station->electricity_transformer_count,
                'elevated_tanks_count' => $station->elevated_tanks_count,
                'disinfection_pumps_count' => $station->disinfection_pump_count,
                'horizontal_pumps_count' => $station->horizontal_pumps_count,
                'generation_groups_count' => $station->generation_groups_count,
                'electricity_hours_count' => $station->electricity_hours_count,
                'ground_tanks_count' => $station->ground_tanks_count,
            ];

            // إعداد بيانات الخريطة للمحطة المحددة ومكوناتها
            $geoJsonData = [
                'stations' => $this->getGeoJsonForModel(Station::where('id', $selectedStation->id), 'station_name', 'blue', 'dashboard.stations.show'),
                'wells' => $this->getGeoJsonForModel($selectedStation->wells(), 'well_name', 'darkblue', 'dashboard.wells.show', 'well_location'),
                'solar_energies' => $this->getGeoJsonForModel($selectedStation->solarEnergies(), 'manufacturer', 'orange', 'dashboard.solar_energy.show'),
                'ground_tanks' => $this->getGeoJsonForModel($selectedStation->groundTanks(), 'tank_name', 'brown', 'dashboard.ground-tanks.show'),
                'elevated_tanks' => $this->getGeoJsonForModel($selectedStation->elevatedTanks(), 'tank_name', 'purple', 'dashboard.elevated-tanks.show'),
            ];

        } else {
            // ==========================================================
            // 3. حساب الإحصائيات العامة (إما للنظام كله أو للوحدة فقط)
            // ==========================================================
            $message = $baseMessage;
            $scopedStationIds = $stationQuery->pluck('id');

            // -- الجرد التفصيلي لمكونات النطاق --
            $statistics = [
                'stations_count' => $scopedStationIds->count(),
                'users_count' => $user->unit_id ? User::where('unit_id', $user->unit_id)->count() : User::count(),
                'units_count' => $user->unit_id ? 1 : Unit::count(),
                'towns_count' => $user->unit_id ? $stationQuery->distinct('town_id')->count() : DB::table('towns')->count(),
                'wells_count' => DB::table('wells')->whereIn('station_id', $scopedStationIds)->count(),
                'generation_groups_count' => DB::table('generation_groups')->whereIn('station_id', $scopedStationIds)->count(),
                'horizontal_pumps_count' => DB::table('horizontal_pumps')->whereIn('station_id', $scopedStationIds)->count(),
                'ground_tanks_count' => DB::table('ground_tanks')->whereIn('station_id', $scopedStationIds)->count(),
                'elevated_tanks_count' => DB::table('elevated_tanks')->whereIn('station_id', $scopedStationIds)->count(),
                'pumping_sectors_count' => DB::table('pumping_sectors')->whereIn('station_id', $scopedStationIds)->count(),
                'electricity_hours_count' => DB::table('electricity_hours')->whereIn('station_id', $scopedStationIds)->count(),
                'electricity_transformers_count' => DB::table('electricity_transformers')->whereIn('station_id', $scopedStationIds)->count(),
                'infiltrators_count' => DB::table('infiltrators')->whereIn('station_id', $scopedStationIds)->count(),
                'filters_count' => DB::table('filters')->whereIn('station_id', $scopedStationIds)->count(),
                'manholes_count' => DB::table('manholes')->whereIn('station_id', $scopedStationIds)->count(),
                'solar_energy_count' => DB::table('solar_energies')->whereIn('station_id', $scopedStationIds)->count(),
                'diesel_tanks_count' => DB::table('diesel_tanks')->whereIn('station_id', $scopedStationIds)->count(),
                'disinfection_pumps_count' => DB::table('disinfection_pumps')->whereIn('station_id', $scopedStationIds)->count(),
            ];

            // -- مؤشرات الأداء والرؤى التحليلية للنطاق --
            $statistics['beneficiary_families'] = Station::whereIn('id', $scopedStationIds)->sum('beneficiary_families_count');
            $statistics['total_water_storage_m3'] = DB::table('ground_tanks')->whereIn('station_id', $scopedStationIds)->sum('capacity') + DB::table('elevated_tanks')->whereIn('station_id', $scopedStationIds)->sum('capacity');
            $statistics['total_generation_capacity_kva'] = DB::table('generation_groups')->whereIn('station_id', $scopedStationIds)->sum('generation_capacity');

            $statistics['stations_by_status'] = Station::whereIn('id', $scopedStationIds)->select('operational_status', DB::raw('count(*) as count'))->groupBy('operational_status')->pluck('count', 'operational_status');
            $statistics['energy_source_distribution'] = Station::whereIn('id', $scopedStationIds)->select('energy_source', DB::raw('count(*) as count'))->whereNotNull('energy_source')->groupBy('energy_source')->pluck('count', 'energy_source');
            $statistics['top_stations_by_wells'] = Station::with('town.unit')->withCount('wells')->whereIn('id', $scopedStationIds)->orderByDesc('wells_count')->limit(5)->get();
            $statistics['low_readiness_diesel_tanks'] = DieselTank::with('station')->whereIn('station_id', $scopedStationIds)->where('readiness_percentage', '<', 25)->orderBy('readiness_percentage')->limit(5)->get();
            $statistics['top_well_stop_reasons'] = DB::table('wells')->whereIn('station_id', $scopedStationIds)->select('stop_reason', DB::raw('count(*) as count'))->where('well_status', 'متوقف')->whereNotNull('stop_reason')->groupBy('stop_reason')->orderByDesc('count')->limit(5)->get();
            $statistics['recent_activities'] = Activity::with('causer')->latest()->limit(5)->get();

            // === إحصائيات تراخيص الآبار (تم وضعها في المكان الصحيح) ===
            $statistics['well_licenses_count'] = WellLicense::count();
            $statistics['licenses_by_type'] = WellLicense::query()
                ->select('request_type', DB::raw('count(*) as count'))
                ->groupBy('request_type')
                ->orderBy('count', 'desc')
                ->pluck('count', 'request_type');
            $statistics['recent_licenses'] = WellLicense::query()
                ->latest()
                ->take(5)
                ->get();

            // إعداد بيانات الخريطة للوضع العام (عرض المحطات فقط)
            $geoJsonData = [
                'stations' => $this->getGeoJsonForModel(Station::whereIn('id', $scopedStationIds), 'station_name', 'blue', 'dashboard.stations.show'),
            ];
        }

        return view('dashboard', [
            'statistics' => $statistics,
            'message' => $message,
            'stations' => $stationsForDropdown,
            'selectedStation' => $selectedStation,
            'geoJsonData' => $geoJsonData,
        ]);
    }

   /**
     * دالة مساعدة لتحويل بيانات الموديل إلى تنسيق GeoJSON FeatureCollection.
     * نسخة محسنة تتعامل مع كل من كائنات الاستعلام (Builder) والعلاقات (Relation).
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query
     * @param string $nameField
     * @param string $color
     * @param string $routeName
     * @param string|null $locationColumn
     * @return array
     */
    private function getGeoJsonForModel($query, string $nameField, string $color, string $routeName, ?string $locationColumn = null): array
    {
        // *** بداية التعديل ***
        // نتحقق إذا كان الكائن من نوع علاقة للحصول على الموديل المرتبط،
        // وإلا نحصل عليه مباشرة من كائن الاستعلام.
        if ($query instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
            $modelClass = get_class($query->getRelated());
        } else {
            $modelClass = get_class($query->getModel());
        }
        // *** نهاية التعديل ***

        if ($modelClass !== \App\Models\Station::class) {
            $query->with('station:id,station_name');
        }

        if ($locationColumn) {
            $items = $query->whereNotNull($locationColumn)->where($locationColumn, '!=', '')->get();
        } else {
            $items = $query->whereNotNull('latitude')->whereNotNull('longitude')->get();
        }

        $features = $items->map(function ($item) use ($nameField, $color, $routeName, $locationColumn) {
            $coordinates = [];
            if ($locationColumn) {
                $locationString = $item->{$locationColumn};
                preg_match_all('/[-+]?\d+\.\d+/', $locationString, $matches);
                if (isset($matches[0]) && count($matches[0]) >= 2) {
                    $latCandidates = array_filter($matches[0], fn($n) => $n >= 35 && $n <= 37.5);
                    $lonCandidates = array_filter($matches[0], fn($n) => $n >= 36 && $n <= 38.5);
                    if (!empty($latCandidates) && !empty($lonCandidates)) {
                        $coordinates = [(float)reset($lonCandidates), (float)reset($latCandidates)];
                    }
                }
            } else {
                $coordinates = [(float)$item->longitude, (float)$item->latitude];
            }

            if (empty($coordinates)) {
                return null;
            }

            $detailUrl = Route::has($routeName) ? route($routeName, $item->id) : '#';

            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $item->{$nameField},
                    'station_name' => optional($item->station)->station_name,
                    'detail_url' => $detailUrl,
                    'color' => $color,
                    'status' => $item->operational_status ?? $item->well_status ?? 'N/A',
                    'type' => class_basename($item),
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $coordinates,
                ],
            ];
        })->filter();

        return [
            'type' => 'FeatureCollection',
            'features' => $features->values()->all(),
        ];
    }
}
