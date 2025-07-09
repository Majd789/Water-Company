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
     public function __construct()
    {
        $this->middleware('permission:units.view')->only(['index', 'show']);
        $this->middleware('permission:units.create')->only(['create', 'store']);
        $this->middleware('permission:units.edit')->only(['edit', 'update']);
        $this->middleware('permission:units.delete')->only('destroy');
    }
    /**
     * عرض قائمة الوحدات
     */
   public function index()
    {
      $units = Unit::with('governorate')
        // 1. جلب العدد الإجمالي للمحطات
        ->withCount('stations')

        // 2. جلب عدد المحطات التي تم التحقق منها (is_verified = true)
        ->withCount(['stations as completed_stations_count' => function ($query) {
            // هنا التعديل: استخدمنا اسم العمود الصحيح (is_verified) والقيمة الصحيحة (true)
            $query->where('is_verified', true);
        }])
        ->paginate(1000);

    return view('dashboard.units.index', compact('units'));
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

        return redirect()->route('dashboard.units.index')->with('success', 'تم استيراد بيانات الوحدات بنجاح!');
    }

    /**
     * عرض نموذج إضافة وحدة جديدة
     */
    public function create()
    {
        $governorates = Governorate::all();
        return view('dashboard.units.create', compact('governorates'));
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

        return redirect()->route('dashboard.units.index')->with('success', 'تمت إضافة الوحدة بنجاح.');
    }

    /**
     * عرض تفاصيل وحدة
     */
    public function show(Unit $unit)
    {
        return view('dashboard.units.show', compact('unit'));
    }

    /**
     * عرض نموذج تعديل وحدة
     */
        public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        $governorates = Governorate::all();
        return view('dashboard.units.edit', compact('unit', 'governorates'));
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

        return redirect()->route('dashboard.units.index')->with('success', 'تم تحديث بيانات الوحدة بنجاح.');
    }

    /**
     * حذف وحدة
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('dashboard.units.index')->with('success', 'تم حذف الوحدة بنجاح.');
    }
}
