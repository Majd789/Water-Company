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
use App\Http\Controllers\API\StationController;
use App\Http\Controllers\Api\TownApiController;
use App\Http\Controllers\Api\UnitApiController;
use App\Http\Controllers\Api\WellApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\StationReportApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {


   Route::prefix('auth')->group(function () {
        Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
            Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        });
    });




    //Staions
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/stations', [StationController::class, 'index']);
        Route::get('/stations/create', [StationController::class, 'create']); // بيانات لإنشاء محطة جديدة
        Route::post('/stations', [StationController::class, 'store']);
        Route::get('/stations/{id}', [StationController::class, 'show']);
        Route::get('/stations/{id}/edit', [StationController::class, 'edit']); // بيانات لتحرير محطة موجودة
        Route::put('/stations/{id}', [StationController::class, 'update']);
        Route::delete('/stations/{id}', [StationController::class, 'destroy']);
        Route::get('/stations-export', [StationController::class, 'export']);
        Route::post('/stations-import', [StationController::class, 'import']);
    });
    // Station Reports
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/stations-reports', [StationReportApiController::class, 'store']);
    });




    // الاحصائيات
    Route::middleware(['auth:sanctum', 'AdminRole:admin,super,superA'])->group(function(){
        Route::get('/statistics',[StatisticsController::class,'index']);
    });


});
