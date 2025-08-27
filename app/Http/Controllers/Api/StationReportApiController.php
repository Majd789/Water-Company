<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StationReport;
use App\Http\Requests\StationReportStoreRequest;
use Illuminate\Validation\Rule;

class StationReportApiController extends Controller
{
    public function store(StationReportStoreRequest $request)
    {
        $validated = $request->validated();

        $report = StationReport::create($validated);

        return response()->json([
            'message' => 'Station report created successfully',
            'data' => $report,
        ], 201);
    }
}


