<?php

namespace App\Http\Controllers\Api;

use App\Exports\ElectricityHoursExport;
use App\Imports\ElectricityHoursImport;
use App\Models\ElectricityHour;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ElectricityHourApiController extends Controller
{
    /**
     * عرض جميع ساعات الكهرباء مع الفلترة وإرجاعها بصيغة JSON.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ElectricityHour::with('station');

        if ($user->role_id != 'admin') {
            $query->whereHas('station', function ($stationQuery) use ($user) {
                $stationQuery->where('id', $user->station_id);
            });
        }

        $electricityHours = $query->paginate(10000);

        return response()->json(['electricityHours' => $electricityHours]);
    }

 
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin') {
            $request->merge(['station_id' => $user->station_id]);
        }

        $validatedData = $request->validate([
            'station_id'               => 'required|exists:stations,id',
            'electricity_hours'        => 'required|integer|min:0',
            'electricity_hour_number'  => 'required|string|max:255',
            'meter_type'               => 'required|string|max:255',
            'operating_entity'         => 'required|string|max:255',
            'notes'                    => 'nullable|string',
        ]);

        $electricityHour = ElectricityHour::create($validatedData);
        return response()->json(['message' => 'تمت إضافة ساعة الكهرباء بنجاح.', 'electricityHour' => $electricityHour], 201);
    }

    /**
     * عرض تفاصيل ساعة كهرباء معينة.
     */
    public function show(ElectricityHour $electricityHour)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin' || $electricityHour->station_id == $user->station_id) {
            return response()->json($electricityHour);
        }
        return response()->json(['message' => 'لا تملك صلاحية الوصول إلى هذه الساعة.'], 403);
    }

    /**
     * تحديث بيانات ساعة كهرباء.
     */
    public function update(Request $request, ElectricityHour $electricityHour)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $electricityHour->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية التعديل على هذه الساعة.'], 403);
        }

        $validatedData = $request->validate([
            'station_id'               => 'required|exists:stations,id',
            'electricity_hours'        => 'required|integer|min:0',
            'electricity_hour_number'  => 'required|string|max:255',
            'meter_type'               => 'required|string|max:255',
            'operating_entity'         => 'required|string|max:255',
            'notes'                    => 'nullable|string',
        ]);

        $electricityHour->update($validatedData);
        return response()->json(['message' => 'تم تحديث ساعة الكهرباء بنجاح.', 'electricityHour' => $electricityHour]);
    }

    /**
     * حذف ساعة كهرباء معينة.
     */
    public function destroy(ElectricityHour $electricityHour)
    {
        $user = auth()->user();
        if ($user->role_id != 'admin' && $electricityHour->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية حذف هذه الساعة.'], 403);
        }

        $electricityHour->delete();
        return response()->json(['message' => 'تم حذف ساعة الكهرباء بنجاح.']);
    }
}