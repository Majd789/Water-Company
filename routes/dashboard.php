<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Dashboard\{
    DashboardController, UserController, RoleController, UnitController, TownController, StationController,
    WellController, GenerationGroupController, HorizontalPumpController, GroundTankController,
    ElevatedTankController, FilterController, InfiltratorController, InstitutionPropertyController,
     ManholeController, NoteController, PrivateWellController, PumpingSectionController,
    SolarEnergyController, StationMapController, StationReportController, WaterWell2Controller,
    WeeklyReportController, ActivityLogController, DataExportController, DailyStationReportController,
    DieselTankController, DisinfectionPumpController, ElectricityHourController,
    ElectricityTransformerController, MaintenanceTaskController
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| تم تنظيم هذا الملف ليكون نظيفًا ويعتمد على الصلاحيات في المتحكمات.
*/

// --- 1. المسارات العامة (لا تتطلب تسجيل الدخول) ---
Route::get('/', fn() => redirect()->route('login'));
Route::get('/latest-news', [WeeklyReportController::class, 'news'])->name('weekly_reports.news');

// --- 2. مسارات لوحة التحكم الرئيسية (محمية وتستخدم البادئة /dashboard) ---
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // === الموارد الرئيسية (Resources) ===
    // الحماية تتم داخل كل متحكم عبر __construct
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('units', UnitController::class);
    Route::resource('towns', TownController::class);
    Route::resource('stations', StationController::class);
    Route::resource('wells', WellController::class);
    Route::resource('generation-groups', GenerationGroupController::class);
    Route::resource('horizontal-pumps', HorizontalPumpController::class);
    Route::resource('ground-tanks', GroundTankController::class);
    Route::resource('elevated-tanks', ElevatedTankController::class);
    Route::resource('pumping-sectors', PumpingSectionController::class);
    Route::resource('electricity-hours', ElectricityHourController::class);
    Route::resource('electricity-transformers', ElectricityTransformerController::class);
    Route::resource('private-wells', PrivateWellController::class);
    Route::resource('infiltrators', InfiltratorController::class);
    Route::resource('filters', FilterController::class);
    Route::resource('manholes', ManholeController::class);
    Route::resource('solar_energy', SolarEnergyController::class); // تم الإبقاء على الاسم كما هو
    Route::resource('diesel_tanks', DieselTankController::class);
    Route::resource('disinfection_pumps', DisinfectionPumpController::class);
    Route::resource('station_reports', StationReportController::class);
    Route::resource('weekly_reports', WeeklyReportController::class);
    Route::resource('daily-station-reports', DailyStationReportController::class);
    Route::resource('waterwells2', WaterWell2Controller::class);
    Route::resource('notes', NoteController::class);
    Route::resource('maintenance_tasks', MaintenanceTaskController::class);
    Route::get('waterwells2/calculate/{wellName}/{stationCode}', [WaterWell2Controller::class, 'calculateWellData'])->name('waterwells2.calculate');
    Route::delete('waterwells2/destroy', [WaterWell2Controller::class, 'destroy'])->name('waterwells2.destroy');
    Route::get('/station-reports', [StationReportController::class, 'index'])->name('station_reports.index');
    // === مسارات خاصة واستثنائية ===
    Route::get('stations-map', [StationMapController::class, 'index'])->name('stations.map');
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::patch('notes/{note}/status', [NoteController::class, 'updateStatus'])->name('notes.updateStatus');
    Route::get('waterwells2/aggregated', [WaterWell2Controller::class, 'aggregatedIndex'])->name('waterwells2.aggregated');
    Route::get('waterwells2/calculate/{wellName}/{stationCode}', [WaterWell2Controller::class, 'calculateWellData'])->name('waterwells2.calculate');


    Route::get('/activity-log/export', [ActivityLogController::class, 'export'])->name('activity-log.export');
    Route::get('/station-reports/export', [StationReportController::class, 'export'])->name('station_reports.export');
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
    // داخل مجموعة الروابط الخاصة بـ dashboard
    Route::get('maintenance_tasks.export', [MaintenanceTaskController::class, 'export'])->name('maintenance_tasks.export');
    Route::get('/station-reports/export', [StationReportController::class, 'export'])->name('station_reports.export');
    // === مسارات الاستيراد (Import) ===
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
    Route::get('waterwells2/import', [WaterWell2Controller::class, 'importForm'])->name('waterwells2.importForm');
    Route::post('waterwells2/import', [WaterWell2Controller::class, 'import'])->name('waterwells2.import'); 
    Route::post('/station-reports/import', [StationReportController::class, 'import'])->name('station_reports.import');
    Route::post('/maintenance_tasks/import', [MaintenanceTaskController::class, 'import'])->name('maintenance_tasks.import');
});

// --- 3. مسارات النظام (ملف التعريف، المصادقة) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// مسار عرض الملفات من storage بشكل آمن
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/public/{$folder}/{$filename}");
    abort_if(!File::exists($path), 404, 'File not found.');
    $mime = File::mimeType($path);
    return response(File::get($path), 200, [
        'Content-Type' => $mime,
        'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
    ]);
})->where(['folder' => '[a-zA-Z0-9_\-]+', 'filename' => '[a-zA-Z0-9_\-\.]+'])->name('storage.image');

require __DIR__.'/auth.php';