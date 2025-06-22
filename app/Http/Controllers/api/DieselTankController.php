<?php

namespace App\Http\Controllers\API;

use App\Exports\DieselTanksExport;
use App\Imports\DieselTanksImport;
use App\Models\DieselTank;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DieselTankController extends Controller
{
    /**
     * عرض جميع خزانات الديزل مع الفلترة (استرجاع JSON)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = DieselTank::with('station');

        if ($user->role_id != 'admin') {
            // المستخدم العادي يمكنه رؤية مضخات محطته فقط
            $query->where('station_id', $user->station_id);
        }

        $dieselTanks = $query->paginate(10000);

        return response()->json([
            'dieselTanks' => $dieselTanks,
        ]);
     
    
    }
    
    public function create()
    {
    $user = auth()->user();
    $stations = Station::query();

    if ($user->unit) {
        // جلب المحطات المرتبطة بالوحدة فقط
        $stations->whereIn('town_id', $user->unit->towns->pluck('id'));
    }

    return response()->json(['stations' => $stations->get()]);
    }

    /**
     * تخزين خزان ديزل جديد
     */
    public function store(Request $request)
    {
        $user = auth()->user();
    
        if ($user->role_id != 'admin') {
            $request->merge(['station_id' => $user->station_id]);
        }
    
        $validatedData = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'tank_name' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string|max:255',
            'general_notes' => 'nullable|string',
        ]);
    
        $dieselTank = DieselTank::create($validatedData);
    
        return response()->json([
            'message' => 'تمت إضافة خزان الديزل بنجاح.',
            'dieselTank' => $dieselTank,
        ], 201);
    }
    
    
    /**
     * عرض تفاصيل خزان ديزل معين
     */
    public function show(DieselTank $dieselTank)
    {
        $user = auth()->user();
    
        if ($user->role_id == 'admin' || $dieselTank->station_id == $user->station_id) {
            return response()->json($dieselTank->load('station'));
        }
    
        return response()->json(['message' => 'لا تملك صلاحية الوصول إلى هذا الخزان.'], 403);
    }
    
    
    
    /**
     * عرض بيانات تحرير خزان ديزل (بيانات الخزان والمحطات)
     */
    public function edit(DieselTank $dieselTank)
    {
    $user = auth()->user();

    if ($user->role_id == 'admin' || $dieselTank->station_id == $user->station_id) {
        return response()->json([
            'dieselTank' => $dieselTank->load('station'),
        ]);
    }

    return response()->json(['message' => 'لا تملك صلاحية الوصول إلى هذا الخزان.'], 403);
    }

    
    /**
     * تحديث بيانات خزان ديزل
     */
    public function update(Request $request, DieselTank $dieselTank)
    {
        $user = auth()->user();
    
        if ($user->role_id != 'admin' && $dieselTank->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية التعديل على هذا الخزان.'], 403);
        }
    
        $validatedData = $request->validate([
            'tank_name' => 'required|string|max:255',
            'tank_capacity' => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string|max:255',
            'general_notes' => 'nullable|string',
        ]);
    
        $dieselTank->update($validatedData);
    
        return response()->json([
            'message' => 'تم تحديث بيانات خزان الديزل بنجاح.',
            'dieselTank' => $dieselTank->load('station'),
        ]);
    }
    

    
    /**
     * حذف خزان ديزل معين
     */
    public function destroy(DieselTank $dieselTank)
    {
        $user = auth()->user();
    
        // السماح للمسؤول بحذف جميع الخزانات
        if ($user->role_id != 'admin' && $dieselTank->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية حذف هذا الخزان.'], 403);
        }
    
        $dieselTank->delete();
    
        return response()->json(['message' => 'تم حذف خزان الديزل بنجاح.']);
    }
    
}
