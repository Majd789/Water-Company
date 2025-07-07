<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Imports\UnitsImport;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Governorate;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{
    /**
     * عرض قائمة الوحدات
     */
    public function index()
    {
        $units = Unit::with('governorate')->paginate(1000);
        return view('units.index', compact('units'));
    }

    /**
     * استيراد بيانات الوحدات من ملف Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048'
        ]);

        Excel::import(new UnitsImport, $request->file('file'));

        return redirect()->route('units.index')->with('success', 'تم استيراد بيانات الوحدات بنجاح!');
    }

    /**
     * عرض نموذج إضافة وحدة جديدة
     */
    public function create()
    {
        $governorates = Governorate::all();
        return view('units.create', compact('governorates'));
    }

    /**
     * تخزين وحدة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|unique:units,unit_name|max:255',
            'governorate_id' => 'nullable|exists:governorates,id',
            'general_notes' => 'nullable|string',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'تمت إضافة الوحدة بنجاح.');
    }

    /**
     * عرض تفاصيل وحدة
     */
    public function show(Unit $unit)
    {
        return view('units.show', compact('unit'));
    }

    /**
     * عرض نموذج تعديل وحدة
     */
        public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        $governorates = Governorate::all();
        return view('units.edit', compact('unit', 'governorates'));
    }


    /**
     * تحديث بيانات الوحدة
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name' => 'required|max:255|unique:units,unit_name,' . $unit->id,
            'governorate_id' => 'nullable|exists:governorates,id',
            'general_notes' => 'nullable|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'تم تحديث بيانات الوحدة بنجاح.');
    }

    /**
     * حذف وحدة
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'تم حذف الوحدة بنجاح.');
    }
}
