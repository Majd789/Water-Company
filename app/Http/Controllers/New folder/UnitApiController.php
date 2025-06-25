<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitApiController extends Controller
{
    /**
     * إرجاع قائمة الوحدات بصيغة JSON
     */
    public function index()
    {
        $units = Unit::with('governorate')->paginate(100000);
        return response()->json(['units' => $units], 200);
    }

    /**
     * إرجاع بيانات وحدة محددة
     */
    public function show($id)
    {
        $unit = Unit::with('governorate')->find($id);

        if (!$unit) {
            return response()->json(['message' => 'الوحدة غير موجودة'], 404);
        }

        return response()->json(['unit' => $unit], 200);
    }

    /**
     * إضافة وحدة جديدة
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit_name' => 'required|unique:units,unit_name|max:255',
            'governorate_id' => 'nullable|exists:governorates,id',
            'general_notes' => 'nullable|string',
        ]);

        $unit = Unit::create($validatedData);

        return response()->json(['message' => 'تمت إضافة الوحدة بنجاح', 'unit' => $unit], 201);
    }

    /**
     * تحديث بيانات وحدة
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json(['message' => 'الوحدة غير موجودة'], 404);
        }

        $validatedData = $request->validate([
            'unit_name' => 'required|max:255|unique:units,unit_name,' . $unit->id,
            'governorate_id' => 'nullable|exists:governorates,id',
            'general_notes' => 'nullable|string',
        ]);

        $unit->update($validatedData);

        return response()->json(['message' => 'تم تحديث بيانات الوحدة بنجاح', 'unit' => $unit], 200);
    }

    /**
     * حذف وحدة
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json(['message' => 'الوحدة غير موجودة'], 404);
        }

        $unit->delete();

        return response()->json(['message' => 'تم حذف الوحدة بنجاح'], 200);
    }
}
