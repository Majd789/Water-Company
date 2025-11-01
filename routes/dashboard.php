<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Dashboard\{
    DashboardController, UserController, RoleController, UnitController, TownController, StationController,
    WellController, GenerationGroupController, HorizontalPumpController, GroundTankController,
    ElevatedTankController, FilterController, InfiltratorController, InstitutionPropertyController,

    ManholeController, NoteController, PrivateWellController, PumpingSectionController,
    SolarEnergyController, StationMapController, StationReportsController,
    WeeklyReportController, ActivityLogController, AssessmentController, DataExportController, DailyStationReportController,

    DieselTankController, DisinfectionPumpController, ElectricityHourController,
    ElectricityTransformerController, MaintenanceTaskController,
    MetricController,
    SafetyProfileController,
    StationTeamController,
    UnitMonthlyStatController,
    WaterQualityTestController,
    WellLicenseController,
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| تم تنظيم هذا الملف ليكون نظيفًا ويعتمد على الصلاحيات في المتحكمات.
*/

// --- 1. المسارات العامة (لا تتطلب تسجيل الدخول) ---
Route::get('/', fn() => redirect()->route('login'));

// --- 2. مسارات لوحة التحكم الرئيسية (محمية وتستخدم البادئة /dashboard) ---
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/activity-log/export', [ActivityLogController::class, 'export'])->name('activity-log.export');
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
    Route::get('/export/infiltrators', [InfiltratorController::class, 'export'])->name('infiltrators.export');
    Route::get('/export/filters', [FilterController::class, 'export'])->name('filters.export');
    Route::get('/export/manholes', [ManholeController::class, 'export'])->name('manholes.export');
    Route::get('/export/solar-energies', [SolarEnergyController::class, 'export'])->name('solar-energies.export');
    Route::get('/export/diesel-tanks', [DieselTankController::class, 'export'])->name('diesel-tanks.export');
    Route::get('water-quality-tests/export', [WaterQualityTestController::class, 'export'])->name('water-quality-tests.export');
    Route::get('station-teams/export', [StationTeamController::class, 'export'])->name('station-teams.export');
    Route::get('safety-profiles/export', [SafetyProfileController::class, 'export'])->name('safety-profiles.export');
    Route::get('well-licenses/export', [WellLicenseController::class, 'export'])->name('well-licenses.export');
    Route::post('well-licenses/import', [WellLicenseController::class, 'import'])->name('well-licenses.import');

    Route::resource('well-licenses', WellLicenseController::class);
    Route::get('/export/all-data', [DataExportController::class, 'exportAll'])->name('export.all');
    // داخل مجموعة الروابط الخاصة بـ dashboard
    Route::get('maintenance_tasks.export', [MaintenanceTaskController::class, 'export'])->name('maintenance_tasks.export');
    // === مسارات الاستيراد (Import) ===
    Route::post('/units/import', [UnitController::class, 'import'])->name('units.import');
    Route::post('/towns/import', [TownController::class, 'import'])->name('towns.import');
    Route::post('/stations/import', [StationController::class, 'import'])->name('stations.import');
    Route::post('wells/import', [WellController::class, 'import'])->name('wells.import');
    Route::post('water-quality-tests/import', [WaterQualityTestController::class, 'import'])->name('water-quality-tests.import');
    Route::post('station-teams/import', [StationTeamController::class, 'import'])->name('station-teams.import');
    Route::post('generation-groups/import', [GenerationGroupController::class, 'import'])->name('generation_groups.import');
    Route::post('disinfection-pumps/import', [DisinfectionPumpController::class, 'import'])->name('disinfection_pumps.import');
    Route::post('horizontal-pumps/import', [HorizontalPumpController::class, 'import'])->name('horizontal_pumps.import');
    Route::post('ground-tanks/import', [GroundTankController::class, 'import'])->name('ground_tanks.import');
    Route::post('elevated-tanks/import', [ElevatedTankController::class, 'import'])->name('elevated_tanks.import');
    Route::post('pumping-sectors/import', [PumpingSectionController::class, 'import'])->name('pumping_sectors.import');
    Route::post('electricity-hours/import', [ElectricityHourController::class, 'import'])->name('electricity_hours.import');
    Route::post('electricity-transformers/import', [ElectricityTransformerController::class, 'import'])->name('electricity_transformers.import');
    Route::post('infiltrators/import', [InfiltratorController::class, 'import'])->name('infiltrators.import');
    Route::post('filters/import', [FilterController::class, 'import'])->name('filters.import');
    Route::post('manholes/import', [ManholeController::class, 'import'])->name('manholes.import');
    Route::post('solar_energy/import', [SolarEnergyController::class, 'import'])->name('import.solar_energies');
    Route::post('diesel_tanks/import', [DieselTankController::class, 'import'])->name('import.diesel_tanks');
    Route::post('safety-profiles/import', [SafetyProfileController::class, 'import'])->name('import.safety_profiles');
    Route::post('/maintenance_tasks/import', [MaintenanceTaskController::class, 'import'])->name('maintenance_tasks.import');
    Route::get('/stations/{id}/export-card', [StationController::class, 'exportStationCard'])->name('stations.exportCard');    Route::resource('wells', WellController::class);
    Route::resource('generation-groups', GenerationGroupController::class);
    Route::resource('horizontal-pumps', HorizontalPumpController::class);

    Route::prefix('unit-stats')->name('unit-stats.')->group(function () {
        Route::get('/', [UnitMonthlyStatController::class, 'index'])->name('index');
        Route::get('/create', [UnitMonthlyStatController::class, 'create'])->name('create');
        Route::post('/', [UnitMonthlyStatController::class, 'store'])->name('store');
        Route::get('/{unitMonthlyStat}', [UnitMonthlyStatController::class, 'show'])->name('show');
        Route::delete('/{unitMonthlyStat}', [UnitMonthlyStatController::class, 'destroy'])->name('destroy');

        // مسارات التعديل المنفصلة
        Route::get('/{unitMonthlyStat}/edit-technical', [UnitMonthlyStatController::class, 'editTechnical'])->name('edit_technical');
        Route::patch('/{unitMonthlyStat}/edit-technical', [UnitMonthlyStatController::class, 'updateTechnical'])->name('update_technical');

        Route::get('/{unitMonthlyStat}/edit-subscribers', [UnitMonthlyStatController::class, 'editSubscribers'])->name('edit_subscribers');
        Route::patch('/{unitMonthlyStat}/edit-subscribers', [UnitMonthlyStatController::class, 'updateSubscribers'])->name('update_subscribers');
    });
    Route::resource('water-quality-tests', WaterQualityTestController::class);
    Route::get('station-teams', [StationTeamController::class, 'index'])->name('station-teams.index');
    Route::get('stations/{station}/team/edit', [StationTeamController::class, 'edit'])->name('station-teams.edit');
    Route::put('stations/{station}/team', [StationTeamController::class, 'update'])->name('station-teams.update');
    Route::delete('station-teams/{stationTeam}', [StationTeamController::class, 'destroy'])->name('station-teams.destroy');

    Route::get('safety-profiles', [SafetyProfileController::class, 'index'])->name('safety-profiles.index');
    Route::get('stations/{station}/safety-profile/edit', [SafetyProfileController::class, 'edit'])->name('safety-profiles.edit');
    Route::put('stations/{station}/safety-profile', [SafetyProfileController::class, 'update'])->name('safety-profiles.update');
    Route::get('stations/{station}/safety-profile', [SafetyProfileController::class, 'show'])->name('safety-profiles.show');
    Route::delete('safety-profiles/{safetyProfile}', [SafetyProfileController::class, 'destroy'])->name('safety-profiles.destroy');

    Route::resource('metrics', MetricController::class)->except(['show']);
    Route::resource('assessments', AssessmentController::class)->except(['show']);

    Route::resource('ground-tanks', GroundTankController::class);
    Route::resource('elevated-tanks', ElevatedTankController::class);
    Route::resource('pumping-sectors', PumpingSectionController::class);
    Route::resource('electricity-hours', ElectricityHourController::class);
    Route::resource('electricity-transformers', ElectricityTransformerController::class);
    Route::resource('infiltrators', InfiltratorController::class);
    Route::resource('filters', FilterController::class);
    Route::resource('manholes', ManholeController::class);
    Route::resource('solar_energy', SolarEnergyController::class); // تم الإبقاء على الاسم كما هو
    Route::resource('diesel_tanks', DieselTankController::class);
    Route::resource('disinfection_pumps', DisinfectionPumpController::class);
    Route::resource('station-reports', StationReportsController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('maintenance_tasks', MaintenanceTaskController::class);
    Route::resource('well-licenses', WellLicenseController::class);
    // === مسارات خاصة واستثنائية ===
    Route::get('stations-map', [StationMapController::class, 'index'])->name('stations.map');
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::patch('notes/{note}/status', [NoteController::class, 'updateStatus'])->name('notes.updateStatus');
    Route::get('station-reports/paper/{station}/{year}/{month}', [StationReportsController::class, 'showPaperReport'])
    ->name('station-reports.paper');
    Route::get('reports/submission-status', [StationReportsController::class, 'submissionStatusDashboard'])
        ->name('reports.submission-status');



    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('units', UnitController::class);
    Route::resource('towns', TownController::class);
    Route::resource('stations', StationController::class);
    Route::get('stations/global-information/{id}', [StationController::class, 'getStationGlobalInformation'])->name('stations.global-information');

    Route::get('/stations/{id}/export-card', [StationController::class, 'exportStationCard'])->name('stations.exportCard');    Route::resource('wells', WellController::class);
    Route::resource('generation-groups', GenerationGroupController::class);
    Route::resource('horizontal-pumps', HorizontalPumpController::class);
    Route::resource('ground-tanks', GroundTankController::class);
    Route::resource('elevated-tanks', ElevatedTankController::class);
    Route::resource('pumping-sectors', PumpingSectionController::class);
    Route::resource('electricity-hours', ElectricityHourController::class);
    Route::resource('electricity-transformers', ElectricityTransformerController::class);
    Route::resource('infiltrators', InfiltratorController::class);
    Route::resource('filters', FilterController::class);
    Route::resource('manholes', ManholeController::class);
    Route::resource('solar_energy', SolarEnergyController::class); // تم الإبقاء على الاسم كما هو
    Route::resource('diesel_tanks', DieselTankController::class);
    Route::resource('disinfection_pumps', DisinfectionPumpController::class);
    Route::resource('station-reports', StationReportsController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('maintenance_tasks', MaintenanceTaskController::class);

    // === مسارات خاصة واستثنائية ===
    Route::get('stations-map', [StationMapController::class, 'index'])->name('stations.map');
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::delete('/activity-log/delete-all', [ActivityLogController::class, 'deleteAll'])->name('activity-log.deleteAll');
    Route::patch('notes/{note}/status', [NoteController::class, 'updateStatus'])->name('notes.updateStatus');
    Route::get('station-reports/paper/{station}/{year}/{month}', [StationReportsController::class, 'showPaperReport'])
    ->name('station-reports.paper');
    Route::get('reports/submission-status', [StationReportsController::class, 'submissionStatusDashboard'])
        ->name('reports.submission-status');


});

// // --- 3. مسارات النظام (ملف التعريف، المصادقة) ---
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
// });

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
