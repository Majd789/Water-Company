<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;


class StationController extends Controller
{
    /**
     * عرض قائمة المحطات مع بيانات البلدات والوحدات بصيغة JSON
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $units = Unit::all();

        // إذا كان المستخدم مرتبط بوحدة معينة
        if ($user->unit_id) {
            $userUnitId = $user->unit_id;
            $towns = Town::where('unit_id', $userUnitId)->get();
            // تصفية المحطات بناءً على الوحدة المرتبطة بالمستخدم
            $stations = Station::where('id', $user->station_id)->whereHas('town', function ($query) use ($userUnitId) {
                $query->where('unit_id', $userUnitId);
            });
        } else {
            $towns = Town::all();
            $stations = Station::query();
        }

        // تصفية المحطات بناءً على الوحدة أو البلدة المحددة
        if ($request->has('unit_id') && $request->unit_id != '') {
            $stations->whereHas('town', function ($query) use ($request) {
                $query->where('unit_id', $request->unit_id);
            });
            $towns = Town::where('unit_id', $request->unit_id)->get();
        }

        // تصفية المحطات بناءً على البلدة المحددة
        if ($request->has('town_id') && $request->town_id != '') {
            $stations->where('town_id', $request->town_id);
        }

        // تصفية المحطات بناءً على كود المحطة
        if ($request->has('station_code') && $request->station_code != '') {
            $stations->where('station_code', 'like', '%' . $request->station_code . '%');
        }

        $stations = $stations->with('town')->paginate(50000);

        return response()->json([
            'stations' => $stations,
            'towns'    => $towns,
            'units'    => $units,
        ]);
    }

    /**
     * عرض تفاصيل محطة معينة
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $station = Station::with('town')->findOrFail($id);

        // التأكد أن المحطة التي يتم عرضها مرتبطة بحساب المستخدم
        if ($station->id !== $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية الوصول لهذه المحطة.'], 403);
        }

        return response()->json($station);
    }

    /**
     * إرجاع البيانات اللازمة لتحرير محطة (مثلاً بيانات المحطة والبلدات)
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $station = Station::findOrFail($id);

        // التأكد أن المحطة التي يتم تحريرها مرتبطة بحساب المستخدم
        if ($station->id !== $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية تعديل هذه المحطة.'], 403);
        }

        $towns = Town::all();
        return response()->json([
            'station' => $station,
            'towns'   => $towns,
        ]);
    }

    /**
     * تحديث بيانات محطة
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $station = Station::findOrFail($id);

        // التأكد أن المحطة التي يتم تحديثها مرتبطة بحساب المستخدم
        if ($station->id !== $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية تعديل هذه المحطة.'], 403);
        }

        $request->validate([
            'station_code' => 'required|unique:stations,station_code,' . $station->id . '|max:255',
            'station_name' => 'required|max:255',
            'operational_status' => 'required|in:خارج الخدمة,عاملة,متوقفة',
            'stop_reason' => 'nullable|max:255',
            'energy_source' => 'nullable|max:255',
            'operator_entity' => 'nullable|in:تشغيل تشاركي,المؤسسة العامة لمياه الشرب',
            'operator_name' => 'nullable|max:255',
            'general_notes' => 'nullable',
            'town_id' => 'required|exists:towns,id',
            'water_delivery_method' => 'nullable|max:255',
            'network_readiness_percentage' => 'nullable|numeric|min:0|max:100',
            'network_type' => 'nullable|max:255',
            'beneficiary_families_count' => 'nullable|integer|min:0',
            'has_disinfection' => 'required|boolean',
            'disinfection_reason' => 'nullable|max:255',
            'served_locations' => 'nullable',
            'actual_flow_rate' => 'nullable|numeric|min:0',
            'station_type' => 'nullable|max:255',
            'detailed_address' => 'nullable',
            'land_area' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|max:255',
            'building_notes' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_verified' => 'required|boolean',
        ]);

        $station->update($request->all());

        return response()->json([
            'message' => 'تم تحديث المحطة بنجاح',
            'station' => $station
        ]);
    }
}
