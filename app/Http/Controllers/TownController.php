<?php

namespace App\Http\Controllers;

use App\Exports\TownsExport;
use App\Imports\TownsImport;
use Illuminate\Http\Request;
use App\Models\Town;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = Unit::all();  // استرجاع جميع الوحدات
        $towns = Town::query();
        
        // التصفية حسب وحدة المستخدم المتصل (تحديد الوحدة المرتبطة بالمستخدم)
        $user = auth()->user();  // الحصول على المستخدم المتصل
        if ($user->unit_id) {
            // إذا كان للمستخدم وحدة مرتبطة به
            $towns->where('unit_id', $user->unit_id);
        }
    
        // التحقق إذا كان يوجد قيمة في الطلب للبحث
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
    
            // البحث في اسم البلدة أو كود البلدة أو اسم الوحدة
            $towns->where('town_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('town_code', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('unit', function ($q) use ($searchTerm) {
                      $q->where('unit_name', 'like', '%' . $searchTerm . '%');
                  });
        }
    
        // التصفية حسب الوحدة إذا تم اختيار وحدة معينة
        if ($request->has('unit_id') && $request->unit_id != '') {
            $towns->where('unit_id', $request->unit_id);
        }
    
        // استرجاع البلدات مع الوحدات، مع تحديد الصفحات
        $towns = $towns->with('unit')->paginate(10000);
    
        return view('towns.index', compact('towns', 'units'));
    }
    

    public function export(Request $request)
    {
        $unitId = auth()->user()->unit_id ?? null; // جلب وحدة المستخدم إذا كانت موجودة
        return Excel::download(new TownsExport($unitId), 'towns.xlsx');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new TownsImport, $request->file('file'));

        return redirect()->back()->with('success', 'تم استيراد البلدات بنجاح.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // الحصول على الوحدة الخاصة بالمستخدم الحالي
        $userUnitId = auth()->user()->unit_id;
        
        // إرسال الوحدة الحالية مع الوحدات الأخرى إلى العرض
        $units = Unit::all();
        return view('towns.create', compact('units', 'userUnitId'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'town_name' => 'required',
            'town_code' => 'required|unique:towns,town_code',
            'unit_id' => 'required|exists:units,id',
        ]);

        Town::create($request->all());

        return redirect()->route('towns.index')->with('success', 'تمت إضافة البلدة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $town = Town::with('unit')->findOrFail($id);
        return view('towns.show', compact('town'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $town = Town::findOrFail($id);
        $units = Unit::all();
        return view('towns.edit', compact('town', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $town = Town::findOrFail($id);

        $request->validate([
            'town_name' => 'required',
            'town_code' => 'required|unique:towns,town_code,' . $town->id,
            'unit_id' => 'required|exists:units,id',
        ]);

        $town->update($request->all());

        return redirect()->route('towns.index')->with('success', 'تم تحديث البلدة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $town = Town::findOrFail($id);
        $town->delete();

        return redirect()->route('towns.index')->with('success', 'تم حذف البلدة بنجاح.');
    }
}
