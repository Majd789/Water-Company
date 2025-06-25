<?php

namespace App\Http\Controllers\Api;

use App\Exports\GroundTanksExport;
use App\Imports\GroundTanksImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GroundTank;
use App\Models\Station;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;

class GroundTankApiController extends Controller
{
    /**
     * عرض جميع الخزانات الأرضية مع الفلترة وإرجاعها بصيغة JSON.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // استرجاع جميع الوحدات لاستخدامها في الفلترة
        $units = Unit::all();

        // استرجاع وحدة المستخدم الحالية (إن وجدت)
        $userUnitId = $user->unit_id;

        // إنشاء استعلام لجلب الخزانات الأرضية مع تحميل المحطات
        $query = GroundTank::with('station');

        // تقييد النتائج إذا كان المستخدم ليس admin
        if ($user->role_id != 'admin') {
            $query->where('station_id', $user->station_id);
        }

        // جلب البيانات مع التصفية والصفحات
        $groundTanks = $query->paginate(10000);

        return response()->json([
            'groundTanks' => $groundTanks,
            'units'       => $units,
        ]);
    }

    /**
     * تصدير الخزانات الأرضية إلى ملف Excel.
     */
    public function export()
    {
        return Excel::download(new GroundTanksExport, 'ground_tanks.xlsx');
    }

    /**
     * استيراد بيانات الخزانات الأرضية من ملف Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new GroundTanksImport, $request->file('file'));

        return response()->json(['message' => 'تم استيراد الخزانات الأرضية بنجاح.']);
    }

    /**
     * عرض بيانات لإنشاء خزان جديد (مثلاً بيانات المحطات).
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role_id != 'admin') {
            // للمستخدمين غير الإداريين: جلب محطة المستخدم فقط
            $stations = Station::where('id', $user->station_id)->get();
        } else {
            // للمستخدم الإداري: إذا كانت الوحدة موجودة، جلب المحطات المرتبطة بالبلدات الخاصة بها
            $unit = $user->unit;
            if ($unit) {
                $stations = Station::whereIn('town_id', $unit->towns->pluck('id'))->get();
            } else {
                $stations = Station::all();
            }
        }

        return response()->json(['stations' => $stations]);
    }

    /**
     * تخزين خزان جديد.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id == 'admin') {
            $validated = $request->validate([
                'station_id'           => 'required|exists:stations,id',
                'tank_name'            => 'required|string|max:255',
                'building_entity'      => 'required|string|max:255',
                'construction_type'    => 'required|in:قديم,جديد',
                'capacity'             => 'required|numeric|min:0',
                'readiness_percentage' => 'required|numeric|min:0|max:100',
                'feeding_station'      => 'required|string|max:255',
                'town_supply'          => 'required|string|max:255',
                'pipe_diameter_inside' => 'nullable|numeric|min:0',
                'pipe_diameter_outside'=> 'nullable|numeric|min:0',
                'latitude'             => 'nullable|numeric',
                'longitude'            => 'nullable|numeric',
                'altitude'             => 'nullable|numeric',
                'precision'            => 'nullable|numeric',
            ]);
        } else {
            $validated = $request->validate([
                'tank_name'            => 'required|string|max:255',
                'building_entity'      => 'required|string|max:255',
                'construction_type'    => 'required|in:قديم,جديد',
                'capacity'             => 'required|numeric|min:0',
                'readiness_percentage' => 'required|numeric|min:0|max:100',
                'feeding_station'      => 'required|string|max:255',
                'town_supply'          => 'required|string|max:255',
                'pipe_diameter_inside' => 'nullable|numeric|min:0',
                'pipe_diameter_outside'=> 'nullable|numeric|min:0',
                'latitude'             => 'nullable|numeric',
                'longitude'            => 'nullable|numeric',
                'altitude'             => 'nullable|numeric',
                'precision'            => 'nullable|numeric',
            ]);
            // تعيين محطة المستخدم تلقائياً
            $validated['station_id'] = $user->station_id;
        }

        $groundTank = GroundTank::create($validated);

        return response()->json([
            'message'    => 'تم إضافة الخزان بنجاح',
            'groundTank' => $groundTank,
        ], 201);
    }

    /**
     * عرض بيانات تحرير خزان (الخزان والمحطات).
     */
    public function edit($id)
    {
        $user = auth()->user();
        $groundTank = GroundTank::findOrFail($id);
        // التحقق من انتماء الخزان لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $groundTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتعديل هذا الخزان'], 403);
        }
        $stations = $user->role_id != 'admin'
            ? Station::where('id', $user->station_id)->get()
            : Station::all();

        return response()->json([
            'groundTank' => $groundTank,
            'stations'   => $stations,
        ]);
    }

    /**
     * تحديث بيانات الخزان.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $groundTank = GroundTank::findOrFail($id);
        // التحقق من انتماء الخزان لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $groundTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بتحديث هذا الخزان'], 403);
        }

        $validated = $request->validate([
            'station_id'           => 'required|exists:stations,id',
            'tank_name'            => 'required|string|max:255',
            'building_entity'      => 'required|string|max:255',
            'construction_type'    => 'required|in:قديم,جديد',
            'capacity'             => 'required|numeric|min:0',
            'readiness_percentage' => 'required|numeric|min:0|max:100',
            'feeding_station'      => 'required|string|max:255',
            'town_supply'          => 'required|string|max:255',
            'pipe_diameter_inside' => 'nullable|numeric|min:0',
            'pipe_diameter_outside'=> 'nullable|numeric|min:0',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'altitude'             => 'nullable|numeric',
            'precision'            => 'nullable|numeric',
        ]);

        // إذا لم يكن المستخدم admin، تأكيد أن station_id هو محطة المستخدم
        if ($user->role_id != 'admin') {
            $validated['station_id'] = $user->station_id;
        }

        $groundTank->update($validated);

        return response()->json([
            'message'    => 'تم تحديث الخزان بنجاح',
            'groundTank' => $groundTank,
        ]);
    }

    /**
     * عرض تفاصيل خزان معين.
     */
    public function show($id)
    {
        $user = auth()->user();
        $groundTank = GroundTank::with('station')->findOrFail($id);
        // التحقق من انتماء الخزان لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $groundTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بالوصول إلى هذا الخزان'], 403);
        }
        return response()->json($groundTank);
    }

    /**
     * حذف خزان.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $groundTank = GroundTank::findOrFail($id);
        // التحقق من انتماء الخزان لمحطة المستخدم إذا لم يكن المستخدم admin
        if ($user->role_id != 'admin' && $groundTank->station_id !== $user->station_id) {
            return response()->json(['message' => 'غير مسموح بحذف هذا الخزان'], 403);
        }
        $groundTank->delete();

        return response()->json(['message' => 'تم حذف الخزان بنجاح']);
    }
}
