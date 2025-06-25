<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Town;


class TownApiController extends Controller
{
    /**
     * عرض جميع البلدات مع الوحدات المرتبطة بها
     */
    public function index()
    {
        $towns = Town::with('unit')->paginate(10000); // إرجاع البيانات على شكل JSON مع التصفّح
        return response()->json($towns);
    }

    /**
     * إرجاع بيانات بلدة معينة
     */
    public function show($id)
    {
        $town = Town::with('unit')->find($id);

        if (!$town) {
            return response()->json(['message' => 'البلدة غير موجودة'], 404);
        }

        return response()->json($town);
    }

    /**
     * إضافة بلدة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'town_name' => 'required|string|max:255',
            'town_code' => 'required|unique:towns,town_code',
            'unit_id' => 'required|exists:units,id',
        ]);

        $town = Town::create($request->all());

        return response()->json([
            'message' => 'تمت إضافة البلدة بنجاح',
            'town' => $town
        ], 201);
    }

    /**
     * تحديث بيانات البلدة
     */
    public function update(Request $request, $id)
    {
        $town = Town::find($id);

        if (!$town) {
            return response()->json(['message' => 'البلدة غير موجودة'], 404);
        }

        $request->validate([
            'town_name' => 'required|string|max:255',
            'town_code' => 'required|unique:towns,town_code,' . $town->id,
            'unit_id' => 'required|exists:units,id',
        ]);

        $town->update($request->all());

        return response()->json([
            'message' => 'تم تحديث البلدة بنجاح',
            'town' => $town
        ]);
    }

    /**
     * حذف بلدة
     */
    public function destroy($id)
    {
        $town = Town::find($id);

        if (!$town) {
            return response()->json(['message' => 'البلدة غير موجودة'], 404);
        }

        $town->delete();

        return response()->json(['message' => 'تم حذف البلدة بنجاح']);
    }
}
