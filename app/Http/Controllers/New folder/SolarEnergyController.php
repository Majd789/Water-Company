<?php

namespace App\Http\Controllers\Api;

use App\Exports\SolarEnergiesExport;
use App\Http\Controllers\Controller;
use App\Imports\SolarEnergiesImport;
use App\Models\SolarEnergy;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SolarEnergyController extends Controller
{
    /**
     * عرض جميع بيانات الطاقة الشمسية مع الفلترة.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // الحصول على الوحدة المرتبطة بالمستخدم الحالي
        $userUnitId = $user->unit_id;
    
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();
    
        // استعلام للبحث عن بيانات الطاقة الشمسية
        $query = SolarEnergy::query();
    
        // تحديد الوحدة المختارة (إما من المستخدم أو من الفلترة)
        $selectedUnitId = $request->unit_id ?? $userUnitId;
    
        // تقييد النتائج للمستخدمين غير الإداريين بحيث تُسترجع بيانات الطاقة الشمسية الخاصة بمحطة المستخدم فقط
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }
    
        // جلب البيانات مع المحطات المرتبطة وترقيم النتائج
        $solarEnergies = $query->with('station')->paginate(10000);
    
        return response()->json([
            'data' => $solarEnergies,
            'units' => $units,
            'selectedUnitId' => $selectedUnitId,
        ]);
    }
    
    public function export()
    {
        return Excel::download(new SolarEnergiesExport, 'solar_energies.xlsx');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        // استيراد البيانات من الملف
        Excel::import(new SolarEnergiesImport, $request->file('file'));
    
        return response()->json([
            'message' => 'تم استيراد البيانات بنجاح'
        ]);
    }
    
    /**
     * تخزين بيانات الطاقة الشمسية الجديدة.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'         => 'required|exists:stations,id',
                'panel_size'         => 'required|numeric|min:0',
                'panel_count'        => 'required|integer|min:0',
                'manufacturer'       => 'required|string|max:255',
                'base_type'          => 'required|string|max:255',
                'technical_condition'=> 'required|string|max:255',
                'wells_supplied_count'=> 'required|integer|min:0',
                'general_notes'      => 'nullable|string',
                'latitude'           => 'nullable|numeric',
                'longitude'          => 'nullable|numeric',
            ]);
        } else {
            // للمستخدمين غير الإداريين تجاهل قيمة station_id في الطلب
            $validated = $request->validate([
                'panel_size'         => 'required|numeric|min:0',
                'panel_count'        => 'required|integer|min:0',
                'manufacturer'       => 'required|string|max:255',
                'base_type'          => 'required|string|max:255',
                'technical_condition'=> 'required|string|max:255',
                'wells_supplied_count'=> 'required|integer|min:0',
                'general_notes'      => 'nullable|string',
                'latitude'           => 'nullable|numeric',
                'longitude'          => 'nullable|numeric',
            ]);
            // فرض محطة المستخدم تلقائيًا
            $validated['station_id'] = $user->station_id;
        }
    
        $solarEnergy = SolarEnergy::create($validated);
    
        return response()->json([
            'message' => 'تمت إضافة بيانات الطاقة الشمسية بنجاح.',
            'data' => $solarEnergy,
        ]);
    }
    
    /**
     * عرض تفاصيل بيانات الطاقة الشمسية.
     */
    public function show(SolarEnergy $solarEnergy)
    {
        $user = auth()->user();
        // التحقق من انتماء سجل الطاقة الشمسية لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $solarEnergy->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذه البيانات'], 403);
        }
    
        return response()->json([
            'data' => $solarEnergy,
        ]);
    }
    
    /**
     * تحديث بيانات الطاقة الشمسية.
     */
    public function update(Request $request, SolarEnergy $solarEnergy)
    {
        $user = auth()->user();
        // التحقق من انتماء سجل الطاقة الشمسية لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $solarEnergy->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث بيانات الطاقة الشمسية'], 403);
        }
    
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'         => 'required|exists:stations,id',
                'panel_size'         => 'required|numeric|min:0',
                'panel_count'        => 'required|integer|min:0',
                'manufacturer'       => 'required|string|max:255',
                'base_type'          => 'required|string|max:255',
                'technical_condition'=> 'required|string|max:255',
                'wells_supplied_count'=> 'required|integer|min:0',
                'general_notes'      => 'nullable|string',
                'latitude'           => 'nullable|numeric',
                'longitude'          => 'nullable|numeric',
            ]);
        } else {
            $validated = $request->validate([
                'panel_size'         => 'required|numeric|min:0',
                'panel_count'        => 'required|integer|min:0',
                'manufacturer'       => 'required|string|max:255',
                'base_type'          => 'required|string|max:255',
                'technical_condition'=> 'required|string|max:255',
                'wells_supplied_count'=> 'required|integer|min:0',
                'general_notes'      => 'nullable|string',
                'latitude'           => 'nullable|numeric',
                'longitude'          => 'nullable|numeric',
            ]);
            // فرض محطة المستخدم تلقائيًا
            $validated['station_id'] = $user->station_id;
        }
    
        $solarEnergy->update($validated);
    
        return response()->json([
            'message' => 'تم تحديث بيانات الطاقة الشمسية بنجاح.',
            'data' => $solarEnergy,
        ]);
    }
    
    /**
     * حذف بيانات الطاقة الشمسية.
     */
    public function destroy(SolarEnergy $solarEnergy)
    {
        $user = auth()->user();
        // التحقق من انتماء سجل الطاقة الشمسية لمحطة المستخدم إذا لم يكن المستخدم إداريًا
        if ($user->role_id != 'admin' && $solarEnergy->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذه البيانات'], 403);
        }
    
        $solarEnergy->delete();
    
        return response()->json([
            'message' => 'تم حذف بيانات الطاقة الشمسية بنجاح.',
        ]);
    }
}
