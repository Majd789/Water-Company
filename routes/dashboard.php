<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ActivityLogController,
    AuthController, DailyStationReportController, DashboardController, DataExportController, DieselTankController,
    DisinfectionPumpController, ElectricityHourController, ElectricityTransformerController,
    UnitController, TownController, StationController, WellController,
    GenerationGroupController, HorizontalPumpController, GroundTankController,
    ElevatedTankController, FilterController, InfiltratorController,
    InstitutionPropertyController, MaintenanceController, ManholeController, ManholeReportController,
    NoteController, PrivateWellController, PumpingSectionController,
    SolarEnergyController, StationMapController, StationReportController, VannaChatController, WaterWell2Controller,
    WaterWellController,
    WeeklyReportController
};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::get('/activity-log/export', [ActivityLogController::class, 'export'])->name('activity-log.export');
// مسار لعرض واجهة الدردشة
Route::get('/vanna-chat', [VannaChatController::class, 'index'])->name('vanna.chat.index');
Route::get('/station-reports/export', [StationReportController::class, 'export'])->name('station_reports.export');

// مسار للتعامل مع بث الدردشة (SSE)
// يجب أن يتطابق هذا المسار مع ما يستدعيه JavaScript في الواجهة الأمامية
Route::post('/vanna-chat/stream', [VannaChatController::class, 'stream'])->name('vanna.chat.stream');
Route::resource('daily-station-reports', DailyStationReportController::class);
// Route::get('/', function () {
//     return redirect()->route('login'); // تحويل أي دخول للصفحة الرئيسية إلى تسجيل الدخول
// });

// Route::get('dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

        Route::get('/waterwells2/aggregated', [WaterWell2Controller::class, 'aggregatedIndex'])
        ->name('waterwells2.aggregated');

        Route::get('/latest-news', [WeeklyReportController::class, 'news'])
        ->name('weekly_reports.news');
        Route::middleware(['auth'])->group(function () {
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
        Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
        Route::patch('/notes/{note}/status', [NoteController::class, 'updateStatus'])->name('notes.updateStatus');
        Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

        Route::resource('station_reports', StationReportController::class);
        Route::post('/station-reports/import', [StationReportController::class, 'import'])->name('station_reports.import');


        Route::resource('waterwells2', WaterWell2Controller::class);
        Route::get('waterwells2/import', [WaterWell2Controller::class, 'importForm'])->name('waterwells2.importForm');
        Route::post('waterwells2/import', [WaterWell2Controller::class, 'import'])->name('waterwells2.import');
        Route::get('waterwells2/calculate/{wellName}/{stationCode}', [WaterWell2Controller::class, 'calculateWellData'])->name('waterwells2.calculate');
        Route::delete('waterwells2/destroy', [WaterWell2Controller::class, 'destroy'])->name('waterwells2.destroy');

        Route::get('/towns/export', [TownController::class, 'export'])->name('towns.export');
        Route::get('/stations/export', [StationController::class, 'export'])->name('stations.export');
        Route::get('/towns/export', [TownController::class, 'export'])->name('towns.export');
        Route::get('/stations/export', [StationController::class, 'export'])->name('stations.export');
        Route::get('/wells/export', [WellController::class, 'export'])->name('wells.export');
        Route::get('/generation-groups/export', [GenerationGroupController::class, 'exportGenerationGroups'])->name('generation-groups.export');
        Route::get('/disinfection-pumps/export', [DisinfectionPumpController::class, 'export'])->name('disinfection_pumps.export');
        Route::get('/horizontal-pumps/export', [HorizontalPumpController::class, 'export'])->name('horizontal_pumps.export');
        Route::get('/export/ground-tanks', [GroundTankController::class, 'export'])->name('ground-tanks.export');
        Route::get('/export/elevated-tanks', [ElevatedTankController::class, 'export'])->name('elevated-tanks.export');
        Route::get('/export/pumping-sectors', [PumpingSectionController::class, 'export'])->name('pumping-sectors.export');
        Route::get('/export/electricity-hours', [ElectricityHourController::class, 'export'])->name('electricity-hours.export');
        Route::get('/export/electricity-transformers', [ElectricityTransformerController::class, 'export'])->name('electricity-transformers.export');
        Route::get('/export/private-wells', [PrivateWellController::class, 'export'])->name('private-wells.export');
        Route::get('/export/infiltrators', [InfiltratorController::class, 'export'])->name('infiltrators.export');
        Route::get('/export/filters', [FilterController::class, 'export'])->name('filters.export');
        Route::get('/export/manholes', [ManholeController::class, 'export'])->name('manholes.export');
        Route::get('/export/solar-energies', [SolarEnergyController::class, 'export'])->name('solar-energies.export');
        Route::get('/export/diesel-tanks', [DieselTankController::class, 'export'])->name('diesel-tanks.export');
        Route::get('/export/maintenances', [MaintenanceController::class, 'export'])->name('maintenances.export');
        Route::get('/weekly-reports/export', [WeeklyReportController::class, 'export'])->name('weekly_reports.export');
        Route::get('/export/all-data', [DataExportController::class, 'exportAll'])->name('export.all');

        Route::get('/station-reports', [StationReportController::class, 'index'])->name('station_reports.index');
        Route::resource('weekly_reports', WeeklyReportController::class);
        Route::get('/stations-map', [StationMapController::class, 'index'])
        ->name('stations.map');

    });

//admin routes
Route::middleware(['auth','AdminRole:admin'])->group(function () {
    Route::resource('units', UnitController::class);
    Route::resource('towns', TownController::class);
    Route::resource('towns', TownController::class);
    Route::resource('stations', StationController::class);
    Route::resource('wells', WellController::class);
    Route::resource('generation-groups', GenerationGroupController::class);
    Route::resource('horizontal-pumps', HorizontalPumpController::class);
    Route::resource('ground-tanks', GroundTankController ::class);
    Route::resource('elevated-tanks', ElevatedTankController::class);
    Route::resource('pumping-sectors', PumpingSectionController::class);
    Route::resource('electricity-hours', ElectricityHourController::class);
    Route::resource('electricity-transformers', ElectricityTransformerController::class);
    Route::resource('private-wells', PrivateWellController::class);
    Route::resource('infiltrators', InfiltratorController::class);
    Route::resource('filters', FilterController ::class);
    Route::resource('manholes', ManholeController ::class);
    Route::resource('solar_energy', SolarEnergyController::class);
    Route::resource('diesel_tanks', DieselTankController::class);
    Route::resource('institution_properties', InstitutionPropertyController::class);
    Route::resource('disinfection_pumps',DisinfectionPumpController::class);
    Route::resource('stations', StationController::class);
    Route::resource('wells', WellController::class);
    Route::resource('generation-groups', GenerationGroupController::class);
    Route::resource('horizontal-pumps', HorizontalPumpController::class);
    Route::resource('ground-tanks', GroundTankController ::class);
    Route::resource('elevated-tanks', ElevatedTankController::class);
    Route::resource('pumping-sectors', PumpingSectionController::class);
    Route::resource('electricity-hours', ElectricityHourController::class);
    Route::resource('electricity-transformers', ElectricityTransformerController::class);
    Route::resource('private-wells', PrivateWellController::class);
    Route::resource('infiltrators', InfiltratorController::class);
    Route::resource('filters', FilterController ::class);
    Route::resource('manholes', ManholeController ::class);
    Route::resource('solar_energy', SolarEnergyController::class);
    Route::resource('diesel_tanks', DieselTankController::class);
    Route::resource('institution_properties', InstitutionPropertyController::class);
    Route::resource('disinfection_pumps',DisinfectionPumpController::class);
    Route::resource('maintenances',MaintenanceController::class);
    Route::get('/stations-map', [StationMapController::class, 'index'])
     ->name('stations.map');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');


    //استيراد البيانات
    Route::post('/units/import', [UnitController::class, 'import'])->name('units.import');
    Route::post('/towns/import', [TownController::class, 'import'])->name('towns.import');
    Route::post('/stations/import', [StationController::class, 'import'])->name('stations.import');
    Route::post('wells/import', [WellController::class, 'import'])->name('wells.import');
    Route::post('generation-groups/import', [GenerationGroupController::class, 'import'])->name('generation_groups.import');
    Route::post('disinfection-pumps/import', [DisinfectionPumpController::class, 'import'])->name('disinfection_pumps.import');
    Route::post('horizontal-pumps/import', [HorizontalPumpController::class, 'import'])->name('horizontal_pumps.import');
    Route::post('ground-tanks/import', [GroundTankController::class, 'import'])->name('ground_tanks.import');
    Route::post('elevated-tanks/import', [ElevatedTankController::class, 'import'])->name('elevated_tanks.import');
    Route::post('pumping-sectors/import', [PumpingSectionController::class, 'import'])->name('pumping_sectors.import');
    Route::post('electricity-hours/import', [ElectricityHourController::class, 'import'])->name('electricity_hours.import');
    Route::post('electricity-transformers/import', [ElectricityTransformerController::class, 'import'])->name('electricity_transformers.import');
    Route::post('private-wells/import', [PrivateWellController::class, 'import'])->name('private_wells.import');
    Route::post('infiltrators/import', [InfiltratorController::class, 'import'])->name('infiltrators.import');
    Route::post('filters/import', [FilterController::class, 'import'])->name('filters.import');
    Route::post('manholes/import', [ManholeController::class, 'import'])->name('manholes.import');
    Route::post('solar_energy/import', [SolarEnergyController::class, 'import'])->name('import.solar_energies');
    Route::post('diesel_tanks/import', [DieselTankController::class, 'import'])->name('import.diesel_tanks');
    Route::post('maintenances/import', [MaintenanceController::class, 'import'])->name('import.maintenances');

    });

    //super routes units
    Route::middleware(['auth', 'AdminRole:super,superA,admin'])->group(function () {

        Route::resource('towns', TownController::class)->except(['destroy']);
        Route::resource('towns', TownController::class)->except(['destroy']);
        Route::resource('stations', StationController::class)->except(['destroy']);
        Route::resource('wells', WellController::class)->except(['destroy']);
        Route::resource('generation-groups', GenerationGroupController::class)->except(['destroy']);
        Route::resource('horizontal-pumps', HorizontalPumpController::class)->except(['destroy']);
        Route::resource('ground-tanks', GroundTankController ::class)->except(['destroy']);
        Route::resource('elevated-tanks', ElevatedTankController::class)->except(['destroy']);
        Route::resource('pumping-sectors', PumpingSectionController::class)->except(['destroy']);
        Route::resource('electricity-hours', ElectricityHourController::class)->except(['destroy']);
        Route::resource('electricity-transformers', ElectricityTransformerController::class)->except(['destroy']);
        Route::resource('private-wells', PrivateWellController::class)->except(['destroy']);
        Route::resource('infiltrators', InfiltratorController::class)->except(['destroy']);
        Route::resource('filters', FilterController ::class)->except(['destroy']);
        Route::resource('manholes', ManholeController ::class)->except(['destroy']);
        Route::resource('solar_energy', SolarEnergyController::class)->except(['destroy']);
        Route::resource('diesel_tanks', DieselTankController::class)->except(['destroy']);
        Route::resource('institution_properties', InstitutionPropertyController::class)->except(['destroy']);
        Route::resource('disinfection_pumps',DisinfectionPumpController::class)->except(['destroy']);
        Route::resource('stations', StationController::class)->except(['destroy']);
        Route::resource('wells', WellController::class)->except(['destroy']);
        Route::resource('generation-groups', GenerationGroupController::class)->except(['destroy']);
        Route::resource('horizontal-pumps', HorizontalPumpController::class)->except(['destroy']);
        Route::resource('ground-tanks', GroundTankController ::class)->except(['destroy']);
        Route::resource('elevated-tanks', ElevatedTankController::class)->except(['destroy']);
        Route::resource('pumping-sectors', PumpingSectionController::class)->except(['destroy']);
        Route::resource('electricity-hours', ElectricityHourController::class)->except(['destroy']);
        Route::resource('electricity-transformers', ElectricityTransformerController::class)->except(['destroy']);
        Route::resource('private-wells', PrivateWellController::class)->except(['destroy']);
        Route::resource('infiltrators', InfiltratorController::class)->except(['destroy']);
        Route::resource('filters', FilterController ::class)->except(['destroy']);
        Route::resource('manholes', ManholeController ::class)->except(['destroy']);
        Route::resource('solar_energy', SolarEnergyController::class)->except(['destroy']);
        Route::resource('diesel_tanks', DieselTankController::class)->except(['destroy']);
        Route::resource('institution_properties', InstitutionPropertyController::class)->except(['destroy']);
        Route::resource('disinfection_pumps',DisinfectionPumpController::class)->except(['destroy']);
        Route::resource('maintenances',MaintenanceController::class)->except(['destroy']);
        Route::get('/stations-map', [StationMapController::class, 'index'])
        ->name('stations.map');
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');



        Route::get('/towns/export', [TownController::class, 'export'])->name('towns.export');
        Route::get('/stations/export', [StationController::class, 'export'])->name('stations.export');
        Route::get('/wells/export', [WellController::class, 'export'])->name('wells.export');
        Route::get('/generation-groups/export', [GenerationGroupController::class, 'exportGenerationGroups'])->name('generation-groups.export');
        Route::get('/disinfection-pumps/export', [DisinfectionPumpController::class, 'export'])->name('disinfection_pumps.export');
        Route::get('/horizontal-pumps/export', [HorizontalPumpController::class, 'export'])->name('horizontal_pumps.export');
        Route::get('/export/ground-tanks', [GroundTankController::class, 'export'])->name('ground-tanks.export');
        Route::get('/export/elevated-tanks', [ElevatedTankController::class, 'export'])->name('elevated-tanks.export');
        Route::get('/export/pumping-sectors', [PumpingSectionController::class, 'export'])->name('pumping-sectors.export');
        Route::get('/export/electricity-hours', [ElectricityHourController::class, 'export'])->name('electricity-hours.export');
        Route::get('/export/electricity-transformers', [ElectricityTransformerController::class, 'export'])->name('electricity-transformers.export');
        Route::get('/export/private-wells', [PrivateWellController::class, 'export'])->name('private-wells.export');
        Route::get('/export/infiltrators', [InfiltratorController::class, 'export'])->name('infiltrators.export');
        Route::get('/export/filters', [FilterController::class, 'export'])->name('filters.export');
        Route::get('/export/manholes', [ManholeController::class, 'export'])->name('manholes.export');
        Route::get('/export/solar-energies', [SolarEnergyController::class, 'export'])->name('solar-energies.export');
        Route::get('/export/diesel-tanks', [DieselTankController::class, 'export'])->name('diesel-tanks.export');
        Route::get('/weekly-reports/export', [WeeklyReportController::class, 'export'])->name('weekly_reports.export');
        Route::get('/export/all-data', [DataExportController::class, 'exportAll'])->name('export.all');


        //استيراد البيانات
        Route::post('/units/import', [UnitController::class, 'import'])->name('units.import');
        Route::post('/towns/import', [TownController::class, 'import'])->name('towns.import');
        Route::post('/stations/import', [StationController::class, 'import'])->name('stations.import');
        Route::post('wells/import', [WellController::class, 'import'])->name('wells.import');
        Route::post('generation-groups/import', [GenerationGroupController::class, 'import'])->name('generation_groups.import');
        Route::post('disinfection-pumps/import', [DisinfectionPumpController::class, 'import'])->name('disinfection_pumps.import');
        Route::post('horizontal-pumps/import', [HorizontalPumpController::class, 'import'])->name('horizontal_pumps.import');
        Route::post('ground-tanks/import', [GroundTankController::class, 'import'])->name('ground_tanks.import');
        Route::post('elevated-tanks/import', [ElevatedTankController::class, 'import'])->name('elevated_tanks.import');
        Route::post('pumping-sectors/import', [PumpingSectionController::class, 'import'])->name('pumping_sectors.import');
        Route::post('electricity-hours/import', [ElectricityHourController::class, 'import'])->name('electricity_hours.import');
        Route::post('electricity-transformers/import', [ElectricityTransformerController::class, 'import'])->name('electricity_transformers.import');
        Route::post('private-wells/import', [PrivateWellController::class, 'import'])->name('private_wells.import');
        Route::post('infiltrators/import', [InfiltratorController::class, 'import'])->name('infiltrators.import');
        Route::post('filters/import', [FilterController::class, 'import'])->name('filters.import');
        Route::post('manholes/import', [ManholeController::class, 'import'])->name('manholes.import');
        Route::post('solar_energy/import', [SolarEnergyController::class, 'import'])->name('import.solar_energies');
        Route::post('diesel_tanks/import', [DieselTankController::class, 'import'])->name('import.diesel_tanks');

    });

    //user routes
    Route::middleware(['auth', 'AdminRole:user,admin'])->group(function () {

    });
    Route::middleware(['auth','AdminRole:unknown'])->group(function (){

    });

Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/public/{$folder}/{$filename}");

    if (!File::exists($path)) {
        abort(404, 'File not found.');
    }

    $mime = File::mimeType($path);

    return Response::make(file_get_contents($path), 200, [
        'Content-Type' => $mime,
        'Content-Disposition' => 'inline; filename="'.basename($path).'"',
    ]);
})->where(['folder' => '[a-zA-Z0-9_\-]+', 'filename' => '[a-zA-Z0-9_\-\.]+'])
  ->name('storage.image');


