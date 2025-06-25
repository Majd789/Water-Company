<?php

namespace App\Http\Controllers\API;

use App\Models\ElevatedTank;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElevatedTankController extends Controller
{
    /**
     * عرض جميع الخزانات المرتبطة بمحطة المستخدم (باستثناء admin).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
        
        // إذا لم يكن المستخدم "admin"، استرجاع فقط الخزانات المرتبطة بمحطته
        $query = ElevatedTank::with('station');
        
        if ($user->role_id != 'admin') {
            $query->whereHas('station', function ($q) use ($user) {
                $q->where('id', $user->station_id);
            });
        }

        // جلب البيانات مع التصفية والصفحات
        $elevatedTanks = $query->paginate(10000);

        return response()->json([
            'elevatedTanks' => $elevatedTanks,
            'units'         => $units,
        ]);
    }

    /**
     * تخزين خزان مرتفع جديد، فقط للمحطة المرتبطة بالمستخدم.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // السماح فقط بإنشاء خزانات لمحطة المستخدم
        $validatedData = $request->validate([
            'tank_name'         => 'required|string|max:255',
            'building_entity'   => 'required|string|max:255',
            'construction_date' => 'required|in:جديد,قديم',
            'capacity'          => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'height'            => 'required|numeric|min:0',
            'tank_shape'        => 'required|string|max:255',
            'feeding_station'   => 'required|string|max:255',
            'town_supply'       => 'required|string|max:255',
            'in_pipe_diameter'  => 'required|numeric|min:0',
            'out_pipe_diameter' => 'required|numeric|min:0',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'altitude'          => 'nullable|numeric',
            'precision'         => 'nullable|numeric',
            'notes'             => 'nullable|string',
        ]);

        // تحديد المحطة تلقائياً من بيانات المستخدم
        $validatedData['station_id'] = $user->station_id;

        $elevatedTank = ElevatedTank::create($validatedData);

        return response()->json([
            'message'       => 'تم إضافة الخزان بنجاح',
            'elevatedTank'  => $elevatedTank,
        ], 201);
    }

    /**
     * تحديث بيانات الخزان، فقط إذا كان مرتبطًا بمحطة المستخدم.
     */
    public function update(Request $request, ElevatedTank $elevatedTank)
    {
        $user = auth()->user();

        // منع التعديل إذا كان الخزان لا ينتمي لمحطة المستخدم
        if ($user->role_id != 'admin' && $elevatedTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا الخزان'], 403);
        }

        $validatedData = $request->validate([
            'tank_name'         => 'required|string|max:255',
            'building_entity'   => 'required|string|max:255',
            'construction_date' => 'required|in:جديد,قديم',
            'capacity'          => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'height'            => 'required|numeric|min:0',
            'tank_shape'        => 'required|string|max:255',
            'feeding_station'   => 'required|string|max:255',
            'town_supply'       => 'required|string|max:255',
            'in_pipe_diameter'  => 'required|numeric|min:0',
            'out_pipe_diameter' => 'required|numeric|min:0',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'altitude'          => 'nullable|numeric',
            'precision'         => 'nullable|numeric',
            'notes'             => 'nullable|string',
        ]);

        $elevatedTank->update($validatedData);

        return response()->json([
            'message'       => 'تم تحديث الخزان بنجاح',
            'elevatedTank'  => $elevatedTank,
        ]);
    }

    /**
     * حذف خزان، فقط إذا كان مرتبطًا بمحطة المستخدم.
     */
    public function destroy(ElevatedTank $elevatedTank)
    {
        $user = auth()->user();

        // منع الحذف إذا كان الخزان لا ينتمي لمحطة المستخدم
        if ($user->role_id != 'admin' && $elevatedTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا الخزان'], 403);
        }

        $elevatedTank->delete();

        return response()->json(['message' => 'تم حذف الخزان بنجاح']);
    }
}
