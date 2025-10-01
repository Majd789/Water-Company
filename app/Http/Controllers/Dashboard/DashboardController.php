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
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $selectedStation = null;
        $statistics = [];
        $geoJsonData = [];
        // ==========================================================
        // 1. تحديد النطاق (Scope) بناءً على المستخدم
        // ==========================================================
        $stationQuery = Station::query();
        $baseMessage = 'نظرة عامة وشاملة على النظام';

        // إذا كان المستخدم مرتبطاً بوحدة، نقوم بتضييق نطاق البحث
        if ($user->unit_id) {
            $unit = Unit::find($user->unit_id);
            $townIds = $unit->towns()->pluck('id');
            $stationQuery->whereIn('town_id', $townIds);
            $baseMessage = "نظرة عامة على وحدة: {$unit->unit_name}";
        }

        // جلب المحطات المتاحة للمستخدم (إما كل المحطات أو محطات الوحدة فقط)
        $stationsForDropdown = $stationQuery->clone()->orderBy('station_name')->get();
        $stationId = $request->query('station_id');

        if ($stationId && $station = $stationQuery->clone()->find($stationId)) {
            // ==========================================================
            // 2. حساب الإحصائيات لمحطة واحدة محددة
            // ==========================================================
            $selectedStation = $station;
            $message = "إحصائيات محطة: '{$station->station_name}'";
            $statistics = [
                'wells_count' => $station->wells()->count(),
                'pumping_sectors_count' => $station->pumpingSectors()->count(),
                'diesel_tanks_count' => $station->dieselTank()->count(),
                'manholes_count' => $station->manholes()->count(),
                'solar_energy_count' => $station->solarEnergies()->count(),
                'filters_count' => $station->filters()->count(),
                'infiltrators_count' => $station->infiltrator()->count(),
                'electricity_transformers_count' => $station->electricityTransformer()->count(),
                'elevated_tanks_count' => $station->elevatedTanks()->count(),
                'disinfection_pumps_count' => $station->disinfectionPump()->count(),
                'horizontal_pumps_count' => $station->horizontalPumps()->count(),
                'generation_groups_count' => $station->generationGroups()->count(),
                'electricity_hours_count' => $station->electricityHours()->count(),
                'ground_tanks_count' => $station->groundTanks()->count(),
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
                // الإحصائيات العامة التي لا تتعلق بالمحطات مباشرة
                'users_count' => $user->unit_id ? User::where('unit_id', $user->unit_id)->count() : User::count(),
                'units_count' => $user->unit_id ? 1 : Unit::count(),
                'towns_count' => $user->unit_id ? $stationQuery->distinct('town_id')->count() : DB::table('towns')->count(),

                // الإحصائيات المتعلقة بالمحطات ضمن النطاق
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
            $statistics['maintenance_in_progress'] = DB::table('maintenances')->whereIn('station_id', $scopedStationIds)->where('status', 'قيد التنفيذ')->count();

            $statistics['stations_by_status'] = Station::whereIn('id', $scopedStationIds)->select('operational_status', DB::raw('count(*) as count'))->groupBy('operational_status')->pluck('count', 'operational_status');
            $statistics['energy_source_distribution'] = Station::whereIn('id', $scopedStationIds)->select('energy_source', DB::raw('count(*) as count'))->whereNotNull('energy_source')->groupBy('energy_source')->pluck('count', 'energy_source');
            $statistics['top_stations_by_wells'] = Station::withCount('wells')->whereIn('id', $scopedStationIds)->orderByDesc('wells_count')->limit(5)->get();
            $statistics['low_readiness_diesel_tanks'] = DieselTank::with('station')->whereIn('station_id', $scopedStationIds)->where('readiness_percentage', '<', 25)->orderBy('readiness_percentage')->limit(5)->get();
            $statistics['top_well_stop_reasons'] = DB::table('wells')->whereIn('station_id', $scopedStationIds)->select('stop_reason', DB::raw('count(*) as count'))->where('well_status', 'متوقف')->whereNotNull('stop_reason')->groupBy('stop_reason')->orderByDesc('count')->limit(5)->get();
            $statistics['recent_activities'] = Activity::with('causer')->latest()->limit(5)->get(); // آخر النشاطات تبقى عامة
        }
     if ($selectedStation) {
            $geoJsonData = [
                // **تعديل: لا نمرر اسم عمود الموقع للمحطات**
                'stations' => $this->getGeoJsonForModel(Station::where('id', $selectedStation->id), 'station_name', 'blue', 'dashboard.stations.show'),

                // **تعديل: نمرر اسم عمود الموقع للآبار فقط**
                'wells' => $this->getGeoJsonForModel($selectedStation->wells(), 'well_name', 'darkblue', 'dashboard.wells.show', 'well_location'),

                // افترض أن البقية لديها أعمدة منفصلة (عدّل إذا كان العكس)
                'solar_energies' => $this->getGeoJsonForModel($selectedStation->solarEnergies(), 'panel_brand', 'orange', 'dashboard.solar_energies.show'),
                'ground_tanks' => $this->getGeoJsonForModel($selectedStation->groundTanks(), 'tank_name', 'brown', 'dashboard.ground_tanks.show'),
                'elevated_tanks' => $this->getGeoJsonForModel($selectedStation->elevatedTanks(), 'tank_name', 'purple', 'dashboard.elevated_tanks.show'),
            ];
        } else {
            $scopedStationIds = $stationQuery->pluck('id');
            $geoJsonData = [
                'stations' => $this->getGeoJsonForModel(Station::whereIn('id', $scopedStationIds), 'station_name', 'blue', 'dashboard.stations.show'),
                'wells' => $this->getGeoJsonForModel(Well::whereIn('station_id', $scopedStationIds), 'well_name', 'darkblue', 'dashboard.wells.show', 'well_location'),
                'solar_energies' => $this->getGeoJsonForModel(SolarEnergy::whereIn('station_id', $scopedStationIds), 'panel_brand', 'orange', 'dashboard.solar_energies.show'),
                'ground_tanks' => $this->getGeoJsonForModel(GroundTank::whereIn('station_id', $scopedStationIds), 'tank_name', 'brown', 'dashboard.ground_tanks.show'),
                'elevated_tanks' => $this->getGeoJsonForModel(ElevatedTank::whereIn('station_id', $scopedStationIds), 'tank_name', 'purple', 'dashboard.elevated_tanks.show'),
            ];
        }
        return view('dashboard', [
            'statistics' => $statistics,
            'message' => $message,
            'stations' => $stationsForDropdown, // نرسل القائمة المفلترة
            'selectedStation' => $selectedStation,
            'geoJsonData' => $geoJsonData,
        ]);
    }
   /**
     * دالة مساعدة لتحويل بيانات الموديل إلى تنسيق GeoJSON FeatureCollection.
     * **نسخة معدلة لتعمل مع عمود موقع نصي واحد**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nameField
     * @param string $color
     * @param string $routeName
     * @param string $locationColumn // **الإضافة الجديدة: اسم عمود الموقع**
     * @return array
     */
    private function getGeoJsonForModel($query, string $nameField, string $color, string $routeName, ?string $locationColumn = null)
    {
        $modelClass = get_class($query->getModel());

        if ($modelClass !== \App\Models\Station::class) {
            $query->with('station');
        }

        // **المنطق الجديد: تحديد الأعمدة بناءً على وجود $locationColumn**
        if ($locationColumn) {
            // الحالة 1: لدينا عمود نصي واحد (مثل 'well_location')
            $items = $query->whereNotNull($locationColumn)->where($locationColumn, '!=', '')->get();
        } else {
            // الحالة 2: لدينا أعمدة منفصلة 'latitude' و 'longitude'
            $items = $query->whereNotNull('latitude')->whereNotNull('longitude')->get();
        }

        $features = $items->map(function ($item) use ($modelClass, $nameField, $color, $routeName, $locationColumn) {

            $coordinates = [];

            if ($locationColumn) {
                // **تحليل من عمود نصي**
                $locationString = $item->{$locationColumn};
                preg_match_all('/[-+]?\d+\.\d+/', $locationString, $matches);

                if (isset($matches[0]) && count($matches[0]) >= 2) {
                    $latCandidates = array_filter($matches[0], fn($n) => $n > 35 && $n < 37);
                    $lonCandidates = array_filter($matches[0], fn($n) => $n > 36 && $n < 38);

                    if (!empty($latCandidates) && !empty($lonCandidates)) {
                        $coordinates = [(float)reset($lonCandidates), (float)reset($latCandidates)];
                    }
                }
            } else {
                // **قراءة مباشرة من الأعمدة المنفصلة**
                $coordinates = [(float)$item->longitude, (float)$item->latitude];
            }

            if (empty($coordinates)) {
                return null;
            }

            $detailUrl = Route::has($routeName) ? route($routeName, $item->id) : '#';
            $stationName = ($modelClass !== \App\Models\Station::class) ? optional($item->station)->station_name : null;

            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $item->{$nameField},
                    'station_name' => $stationName,
                    'detail_url' => $detailUrl,
                    'color' => $color,
                    'status' => $item->operational_status ?? $item->well_status ?? 'غير معروف',
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
            'features' => $features->values(),
        ];
    }
}
