<?php

use App\Http\Controllers\Api\DieselTankApiController;
use App\Http\Controllers\Api\DisinfectionPumpApiController;
use App\Http\Controllers\Api\ElectricityHourApiController;
use App\Http\Controllers\Api\ElectricityTransformerApiController;
use App\Http\Controllers\Api\ElevatedTankApiController;
use App\Http\Controllers\Api\FilterApiController;
use App\Http\Controllers\Api\GenerationGroupApiController;
use App\Http\Controllers\Api\GroundTankApiController;
use App\Http\Controllers\Api\HorizontalPumpApiController;
use App\Http\Controllers\Api\InfiltratorApiController;
use App\Http\Controllers\Api\ManholeApiController;
use App\Http\Controllers\Api\PumpingSectionApiController;
use App\Http\Controllers\Api\SolarEnergyApiController;
use App\Http\Controllers\Api\StationApiController;
use App\Http\Controllers\Api\TownApiController;
use App\Http\Controllers\Api\UnitApiController;
use App\Http\Controllers\Api\WellApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });



    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // تعريف مسارات API للوحدات
    Route::middleware('auth:sanctum')->group(function(){
         Route::apiResource('units', UnitApiController::class);
    });
    //Towns
    Route::middleware('auth:sanctum')->group(function(){
        Route::apiResource('towns', TownApiController::class);
    });

    //Staions
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/stations', [StationApiController::class, 'index']);
        Route::get('/stations/create', [StationApiController::class, 'create']); // بيانات لإنشاء محطة جديدة
        Route::post('/stations', [StationApiController::class, 'store']);
        Route::get('/stations/{id}', [StationApiController::class, 'show']);
        Route::get('/stations/{id}/edit', [StationApiController::class, 'edit']); // بيانات لتحرير محطة موجودة
        Route::put('/stations/{id}', [StationApiController::class, 'update']);
        Route::delete('/stations/{id}', [StationApiController::class, 'destroy']);
        Route::get('/stations-export', [StationApiController::class, 'export']);
        Route::post('/stations-import', [StationApiController::class, 'import']);
    });

    //diesel-tanks
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/diesel-tanks', [DieselTankApiController::class, 'index']);
        Route::get('/diesel-tanks/create', [DieselTankApiController::class, 'create']); // بيانات لإنشاء خزان جديد
        Route::post('/diesel-tanks', [DieselTankApiController::class, 'store']);
        Route::get('/diesel-tanks/{dieselTank}', [DieselTankApiController::class, 'show']);
        Route::get('/diesel-tanks/{dieselTank}/edit', [DieselTankApiController::class, 'edit']); // بيانات لتحرير خزان موجود\n
        Route::put('/diesel-tanks/{dieselTank}', [DieselTankApiController::class, 'update']);
        Route::delete('/diesel-tanks/{dieselTank}', [DieselTankApiController::class, 'destroy']);
        Route::get('/diesel-tanks-export', [DieselTankApiController::class, 'export']);
        Route::post('/diesel-tanks-import', [DieselTankApiController::class, 'import']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/disinfection-pumps', [DisinfectionPumpApiController::class, 'index']);
        Route::get('/disinfection-pumps/create', [DisinfectionPumpApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/disinfection-pumps', [DisinfectionPumpApiController::class, 'store']);
        Route::get('/disinfection-pumps/{id}', [DisinfectionPumpApiController::class, 'show']);
        Route::get('/disinfection-pumps/{disinfectionPump}/edit', [DisinfectionPumpApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/disinfection-pumps/{disinfectionPump}', [DisinfectionPumpApiController::class, 'update']);
        Route::delete('/disinfection-pumps/{disinfectionPump}', [DisinfectionPumpApiController::class, 'destroy']);
        Route::get('/disinfection-pumps-export', [DisinfectionPumpApiController::class, 'export']);
        Route::post('/disinfection-pumps-import', [DisinfectionPumpApiController::class, 'import']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/electricity-hours', [ElectricityHourApiController::class, 'index']);
        Route::get('/electricity-hours/create', [ElectricityHourApiController::class, 'create']); // بيانات لإنشاء ساعة كهرباء جديدة
        Route::post('/electricity-hours', [ElectricityHourApiController::class, 'store']);
        Route::get('/electricity-hours/{electricityHour}', [ElectricityHourApiController::class, 'show']);
        Route::get('/electricity-hours/{electricityHour}/edit', [ElectricityHourApiController::class, 'edit']); // بيانات لتحرير ساعة كهرباء موجودة
        Route::put('/electricity-hours/{electricityHour}', [ElectricityHourApiController::class, 'update']);
        Route::delete('/electricity-hours/{electricityHour}', [ElectricityHourApiController::class, 'destroy']);
        Route::get('/electricity-hours-export', [ElectricityHourApiController::class, 'export']);
        Route::post('/electricity-hours-import', [ElectricityHourApiController::class, 'import']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/elevated-tanks', [ElevatedTankApiController::class, 'index']);
        Route::get('/elevated-tanks/create', [ElevatedTankApiController::class, 'create']); // بيانات لإنشاء خزان جديد
        Route::post('/elevated-tanks', [ElevatedTankApiController::class, 'store']);
        Route::get('/elevated-tanks/{elevatedTank}', [ElevatedTankApiController::class, 'show']);
        Route::get('/elevated-tanks/{elevatedTank}/edit', [ElevatedTankApiController::class, 'edit']); // بيانات لتحرير خزان موجود
        Route::put('/elevated-tanks/{elevatedTank}', [ElevatedTankApiController::class, 'update']);
        Route::delete('/elevated-tanks/{elevatedTank}', [ElevatedTankApiController::class, 'destroy']);
        Route::get('/elevated-tanks-export', [ElevatedTankApiController::class, 'export']);
        Route::post('/elevated-tanks-import', [ElevatedTankApiController::class, 'import']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/filters', [FilterApiController::class, 'index']);
        Route::get('/filters/create', [FilterApiController::class, 'create']); // بيانات لإنشاء مرشح جديد
        Route::post('/filters', [FilterApiController::class, 'store']);
        Route::get('/filters/{filter}', [FilterApiController::class, 'show']);
        Route::get('/filters/{filter}/edit', [FilterApiController::class, 'edit']); // بيانات لتحرير مرشح موجود
        Route::put('/filters/{filter}', [FilterApiController::class, 'update']);
        Route::delete('/filters/{filter}', [FilterApiController::class, 'destroy']);
        Route::get('/filters-export', [FilterApiController::class, 'export']);
        Route::post('/filters-import', [FilterApiController::class, 'import']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/generation-groups', [GenerationGroupApiController::class, 'index']);
        Route::get('/generation-groups/create', [GenerationGroupApiController::class, 'create']); // بيانات لإنشاء مجموعة توليد جديدة
        Route::post('/generation-groups', [GenerationGroupApiController::class, 'store']);
        Route::get('/generation-groups/{generationGroup}', [GenerationGroupApiController::class, 'show']);
        Route::get('/generation-groups/{generationGroup}/edit', [GenerationGroupApiController::class, 'edit']); // بيانات لتحرير مجموعة توليد موجودة
        Route::put('/generation-groups/{generationGroup}', [GenerationGroupApiController::class, 'update']);
        Route::delete('/generation-groups/{generationGroup}', [GenerationGroupApiController::class, 'destroy']);
        Route::get('/generation-groups-export', [GenerationGroupApiController::class, 'exportGenerationGroups']);
        Route::post('/generation-groups-import', [GenerationGroupApiController::class, 'import']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/ground-tanks', [GroundTankApiController::class, 'index']);
        Route::get('/ground-tanks/create', [GroundTankApiController::class, 'create']); // بيانات لإنشاء خزان جديد
        Route::post('/ground-tanks', [GroundTankApiController::class, 'store']);
        Route::get('/ground-tanks/{id}', [GroundTankApiController::class, 'show']);
        Route::get('/ground-tanks/{id}/edit', [GroundTankApiController::class, 'edit']); // بيانات لتحرير خزان موجود
        Route::put('/ground-tanks/{id}', [GroundTankApiController::class, 'update']);
        Route::delete('/ground-tanks/{id}', [GroundTankApiController::class, 'destroy']);
        Route::get('/ground-tanks-export', [GroundTankApiController::class, 'export']);
        Route::post('/ground-tanks-import', [GroundTankApiController::class, 'import']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/horizontal-pumps', [HorizontalPumpApiController::class, 'index']);
        Route::get('/horizontal-pumps/create', [HorizontalPumpApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/horizontal-pumps', [HorizontalPumpApiController::class, 'store']);
        Route::get('/horizontal-pumps/{horizontalPump}', [HorizontalPumpApiController::class, 'show']);
        Route::get('/horizontal-pumps/{horizontalPump}/edit', [HorizontalPumpApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/horizontal-pumps/{horizontalPump}', [HorizontalPumpApiController::class, 'update']);
        Route::delete('/horizontal-pumps/{horizontalPump}', [HorizontalPumpApiController::class, 'destroy']);
        Route::get('/horizontal-pumps-export', [HorizontalPumpApiController::class, 'export']);
        Route::post('/horizontal-pumps-import', [HorizontalPumpApiController::class, 'import']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/manholes', [ManholeApiController::class, 'index']);
        Route::get('/manholes/create', [ManholeApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/manholes', [ManholeApiController::class, 'store']);
        Route::get('/manholes/{manhole}', [ManholeApiController::class, 'show']);
        Route::get('/manholes/{manhol}/edit', [ManholeApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/manholes/{manhole}', [ManholeApiController::class, 'update']);
        Route::delete('/manholes/{manhole}', [ManholeApiController::class, 'destroy']);
        Route::get('/manholes-export', [ManholeApiController::class, 'export']);
        Route::post('/manholes-import', [ManholeApiController::class, 'import']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/PumpingSectors', [PumpingSectionApiController::class, 'index']);
        Route::get('/PumpingSectors/create', [PumpingSectionApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/PumpingSectors', [PumpingSectionApiController::class, 'store']);
        Route::get('/PumpingSectors/{PumpingSector}', [PumpingSectionApiController::class, 'show']);
        Route::get('/PumpingSectors/{PumpingSector}/edit', [PumpingSectionApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/PumpingSectors/{PumpingSector}', [PumpingSectionApiController::class, 'update']);
        Route::delete('/PumpingSectors/{PumpingSector}', [PumpingSectionApiController::class, 'destroy']);
        Route::get('/PumpingSectors-export', [PumpingSectionApiController::class, 'export']);
        Route::post('/PumpingSectors-import', [PumpingSectionApiController::class, 'import']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/solar_energy', [SolarEnergyApiController::class, 'index']);
        Route::get('/solar_energy/create', [SolarEnergyApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/solar_energy', [SolarEnergyApiController::class, 'store']);
        Route::get('/solar_energy/{solar_energy}', [SolarEnergyApiController::class, 'show']);
        Route::get('/solar_energy/{solar_energy}/edit', [SolarEnergyApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/solar_energy/{solar_energy}', [SolarEnergyApiController::class, 'update']);
        Route::delete('/solar_energy/{solar_energy}', [SolarEnergyApiController::class, 'destroy']);
        Route::get('/solar_energy-export', [SolarEnergyApiController::class, 'export']);
        Route::post('/solar_energy-import', [SolarEnergyApiController::class, 'import']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/wells', [WellApiController::class, 'index']);
        Route::get('/wells/create', [WellApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/wells', [WellApiController::class, 'store']);
        Route::get('/wells/{well}', [WellApiController::class, 'show']);
        Route::get('/wells/{well}/edit', [WellApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/wells/{well}', [WellApiController::class, 'update']);
        Route::delete('/wells/{well}', [WellApiController::class, 'destroy']);
        Route::get('/wells-export', [WellApiController::class, 'export']);
        Route::post('/wells-import', [WellApiController::class, 'import']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/infiltrators', [InfiltratorApiController::class, 'index']);
        Route::get('/infiltrators/create', [InfiltratorApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/infiltrators', [InfiltratorApiController::class, 'store']);
        Route::get('/infiltrators/{infiltrator}', [InfiltratorApiController::class, 'show']);
        Route::get('/infiltrators/{infiltrator}/edit', [InfiltratorApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/infiltrators/{infiltrator}', [InfiltratorApiController::class, 'update']);
        Route::delete('/infiltrators/{infiltrator}', [InfiltratorApiController::class, 'destroy']);
        Route::get('/infiltrators-export', [InfiltratorApiController::class, 'export']);
        Route::post('/infiltrators-import', [InfiltratorApiController::class, 'import']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/electricityTransformers', [ElectricityTransformerApiController::class, 'index']);
        Route::get('/electricityTransformers/create', [ElectricityTransformerApiController::class, 'create']); // بيانات لإنشاء مضخة جديدة
        Route::post('/electricityTransformers', [ElectricityTransformerApiController::class, 'store']);
        Route::get('/electricityTransformers/{electricityTransformer}', [ElectricityTransformerApiController::class, 'show']);
        Route::get('/electricityTransformers/{electricityTransformer}/edit', [ElectricityTransformerApiController::class, 'edit']); // بيانات لتحرير مضخة موجودة
        Route::put('/electricityTransformers/{electricityTransformer}', [ElectricityTransformerApiController::class, 'update']);
        Route::delete('/electricityTransformers/{electricityTransformer}', [ElectricityTransformerApiController::class, 'destroy']);
        Route::get('/electricityTransformers-export', [ElectricityTransformerApiController::class, 'export']);
        Route::post('/electricityTransformers-import', [ElectricityTransformerApiController::class, 'import']);
    });


    // الاحصائيات
    Route::middleware(['auth:sanctum', 'AdminRole:admin,super,superA'])->group(function(){
        Route::get('/statistics',[StatisticsController::class,'index']);
    });


});
