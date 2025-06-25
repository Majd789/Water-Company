<?php

namespace App\Http\Controllers\Api;

use App\Exports\ElectricityTransformersExport;
use App\Imports\ElectricityTransformersImport;
use App\Models\Station;
use App\Models\ElectricityTransformer;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ElectricityTransformerApiController extends Controller
{
    /**
     * عرض جميع المحولات الكهربائية مع الفلترة (JSON)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ElectricityTransformer::with('station');
        if ($user->role_id != 'admin') {  $query->where('station_id', $user->station_id);}
        
        $transformers = $query->paginate(100);

        return response()->json([
            'electricityTransformers' => $transformers,        
        ]);
    }
    /**
     * تخزين محولة جديدة (JSON)
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin') {
            $request->merge(['station_id' => $user->station_id]);
        }
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'operational_status' => 'required|in:تعمل,متوقفة',
            'transformer_capacity' => 'required|numeric',
            'distance_from_station' => 'required|numeric',
            'is_station_transformer' => 'required|boolean',
            'talk_about_station_transformer' => 'nullable|string',
            'is_capacity_sufficient' => 'required|boolean',
            'how_mush_capacity_need' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $electricityTransformer = ElectricityTransformer::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المحولة بنجاح.',
            'electricityTransformer' => $electricityTransformer,
        ], 201);
    }

    /**
     * عرض تفاصيل محولة معينة (JSON)
     */
    public function show(ElectricityTransformer $electricityTransformer)
    {
        return response()->json([
            'electricityTransformer' => $electricityTransformer->load('station'),
        ]);
    }

    /**
     * عرض بيانات تحرير المحولة (JSON)
     */
    public function edit(ElectricityTransformer $electricityTransformer)
    {
        $stations = Station::all();
        return response()->json([
            'electricityTransformer' => $electricityTransformer->load('station'),
            'stations' => $stations,
        ]);
    }

    /**
     * تحديث بيانات المحولة (JSON)
     */
    public function update(Request $request, ElectricityTransformer $electricityTransformer)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'operational_status' => 'required|in:تعمل,متوقفة',
            'transformer_capacity' => 'required|numeric',
            'distance_from_station' => 'required|numeric',
            'is_station_transformer' => 'required|boolean',
            'talk_about_station_transformer' => 'nullable|string',
            'is_capacity_sufficient' => 'required|boolean',
            'how_mush_capacity_need' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $electricityTransformer->update($validated);

        return response()->json([
            'message' => 'تم تحديث المحولة بنجاح.',
            'electricityTransformer' => $electricityTransformer->load('station'),
        ]);
    }

    /**
     * حذف محولة معينة (JSON)
     */
    public function destroy(ElectricityTransformer $electricityTransformer)
    {
        $electricityTransformer->delete();

        return response()->json([
            'message' => 'تم حذف المحولة بنجاح.'
        ]);
    }
}
