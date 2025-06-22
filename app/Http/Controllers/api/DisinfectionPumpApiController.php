<?php

namespace App\Http\Controllers\Api;

use App\Models\DisinfectionPump;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisinfectionPumpApiController extends Controller
{
    /**
     * عرض جميع مضخات التعقيم المرتبطة بمحطة المستخدم.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = DisinfectionPump::with('station');

        if ($user->role_id != 'admin') {
            // المستخدم العادي يمكنه رؤية مضخات محطته فقط
            $query->where('station_id', $user->station_id);
        }

        $disinfectionPumps = $query->paginate(10000);

        return response()->json([
            'disinfectionPumps' => $disinfectionPumps,
        ]);
    }

    /**
     * إنشاء مضخة تعقيم جديدة (تقييد المحطة للمستخدم العادي).
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // إذا لم يكن المستخدم مسؤولًا، يتم ربط المضخة بمحطته فقط
        if ($user->role_id != 'admin') {
            $request->merge(['station_id' => $user->station_id]);
        }

        $validatedData = $request->validate([
            'station_id'              => 'required|exists:stations,id',
            'disinfection_pump_status'=> 'nullable|in:يعمل,متوقف',
            'pump_brand_model'        => 'nullable|string|max:255',
            'pump_flow_rate'          => 'nullable|numeric|min:0',
            'operating_pressure'      => 'nullable|numeric|min:0',
            'technical_condition'     => 'nullable|string|max:255',
            'notes'                   => 'nullable|string',
        ]);

        $disinfectionPump = DisinfectionPump::create($validatedData);

        return response()->json([
            'message'         => 'تمت إضافة مضخة التعقيم بنجاح.',
            'disinfectionPump'=> $disinfectionPump,
        ], 201);
    }

    /**
     * عرض تفاصيل مضخة تعقيم معينة (تقييد الوصول لمحطة المستخدم فقط).
     */
    public function show(DisinfectionPump $disinfectionPump)
    {
        $user = auth()->user();

        if ($user->role_id == 'admin' || $disinfectionPump->station_id == $user->station_id) {
            return response()->json($disinfectionPump);
        }

        return response()->json(['message' => 'لا تملك صلاحية الوصول إلى هذه المضخة.'], 403);
    }

    /**
     * تعديل بيانات مضخة تعقيم (تقييد الوصول لمحطة المستخدم فقط).
     */
    public function update(Request $request, DisinfectionPump $disinfectionPump)
    {
        $user = auth()->user();

        if ($user->role_id != 'admin' && $disinfectionPump->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية التعديل على هذه المضخة.'], 403);
        }

        $validatedData = $request->validate([
            'station_id'              => 'required|exists:stations,id',
            'disinfection_pump_status'=> 'nullable|in:يعمل,متوقف',
            'pump_brand_model'        => 'nullable|string|max:255',
            'pump_flow_rate'          => 'nullable|numeric|min:0',
            'operating_pressure'      => 'nullable|numeric|min:0',
            'technical_condition'     => 'nullable|string|max:255',
            'notes'                   => 'nullable|string',
        ]);

        $disinfectionPump->update($validatedData);

        return response()->json([
            'message'         => 'تم تحديث بيانات مضخة التعقيم بنجاح.',
            'disinfectionPump'=> $disinfectionPump,
        ]);
    }

    /**
     * حذف مضخة تعقيم (تقييد الحذف لمحطة المستخدم فقط).
     */
    public function destroy(DisinfectionPump $disinfectionPump)
    {
        $user = auth()->user();

        if ($user->role_id != 'admin' && $disinfectionPump->station_id != $user->station_id) {
            return response()->json(['message' => 'لا تملك صلاحية حذف هذه المضخة.'], 403);
        }

        $disinfectionPump->delete();

        return response()->json(['message' => 'تم حذف مضخة التعقيم بنجاح.']);
    }
}
