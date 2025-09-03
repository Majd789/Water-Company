<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PumpingSector;
use App\Models\Unit;
use Illuminate\Http\Request;

class PumpingSectorsApiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $units = Unit::all();
        $userUnitId = $user->unit_id;
        $query = PumpingSector::query()->where('station_id', $user->station_id);
        $selectedUnitId = $request->unit_id ?? $userUnitId;
        $PumpingSectors = $query->with(['station', 'station.town'])->get();
    
        return response()->json([
            'PumpingSectors' => $PumpingSectors,
            'units' => $units,
            'selectedUnitId' => $selectedUnitId
        ]);
    }
}
