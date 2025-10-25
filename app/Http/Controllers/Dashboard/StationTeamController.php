<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StationTeam;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;
// ملاحظة: ستحتاج لإنشاء هذه الملفات لاحقاً
// use App\Exports\StationTeamsExport;
// use App\Imports\StationTeamsImport;
use Maatwebsite\Excel\Facades\Excel;

class StationTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:station_teams.view')->only(['index', 'show']);
        // تم دمج صلاحيات الإنشاء والتعديل لأنها عملية واحدة (updateOrCreate)
        $this->middleware('permission:station_teams.edit')->only(['edit', 'update']);
        $this->middleware('permission:station_teams.delete')->only('destroy');
        $this->middleware('permission:station_teams.export')->only('export');
        $this->middleware('permission:station_teams.import')->only('import');
    }

    /**
     * عرض جميع فرق المحطات مع الفلترة والبحث.
     */
    public function index(Request $request)
    {
        $userUnitId = auth()->user()->unit_id;
        $units = Unit::all();

        // سنقوم بالاستعلام من جدول المحطات لأنه الجدول الرئيسي
        // وسنجلب علاقة الفريق معه
        $stations = Station::with('team');

        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // فلترة النتائج بناءً على الوحدة الإدارية
        if (!empty($selectedUnitId)) {
            $stations->whereHas('town', function ($townQuery) use ($selectedUnitId) {
                $townQuery->where('unit_id', $selectedUnitId);
            });
        }

        // فلترة بناءً على حقل البحث
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $stations->where('station_name', 'like', '%' . $searchTerm . '%');
        }

        $stations = $stations->paginate(25);

        // سنرسل المحطات إلى العرض، ومن خلالها سنصل إلى بيانات الفريق
        return view('dashboard.station_teams.index', compact('stations', 'units', 'selectedUnitId'));
    }

    /**
     * عرض نموذج تعديل/إنشاء بيانات فريق لمحطة معينة.
     * بما أنها علاقة 1-to-1، لا يوجد صفحة 'create' منفصلة.
     * سنقوم بالتعديل مباشرة من صفحة عرض المحطة أو صفحة تعديل مخصصة.
     */
    public function edit(Station $station)
    {
        // ابحث عن الفريق المرتبط بالمحطة، أو أنشئ كائن جديد فارغ إذا لم يكن موجوداً
        $team = $station->team ?? new StationTeam();

        return view('dashboard.station_teams.edit', compact('station', 'team'));
    }

    /**
     * تحديث أو إنشاء بيانات فريق لمحطة معينة.
     */
    public function update(Request $request, Station $station)
    {
        $validatedData = $request->validate([
            'maintenance_team_count' => 'nullable|integer|min:0',
            'water_quality_team_count' => 'nullable|integer|min:0',
            'admin_team_count' => 'nullable|integer|min:0',
            'contact_number' => 'nullable|string|max:20',
            'maintenance_team_skills' => 'nullable|string',
            'water_quality_team_skills' => 'nullable|string',
        ]);

        // استخدام updateOrCreate لتحديث السجل إن وجد، أو إنشائه إن لم يكن موجوداً
        // بناءً على station_id
        $station->team()->updateOrCreate(
            ['station_id' => $station->id], // الشرط للبحث
            $validatedData // البيانات للتحديث أو الإنشاء
        );

        return redirect()->route('dashboard.station_teams.index')->with('success', 'تم تحديث بيانات فريق المحطة بنجاح.');
    }

    /**
     * حذف بيانات فريق محطة معينة.
     */
    public function destroy(StationTeam $stationTeam)
    {
        $stationTeam->delete();
        return redirect()->route('dashboard.station_teams.index')->with('success', 'تم حذف بيانات الفريق بنجاح.');
    }

    // الدوال التالية اختيارية لهذا المتحكم ولكنها متوفرة للتوحيد

    public function create()
    {
        // ليس له استخدام عملي هنا، الأفضل التعديل من خلال المحطة
        return redirect()->route('dashboard.station_teams.index');
    }

    public function store(Request $request)
    {
        // ليس له استخدام، يتم التعامل مع كل شيء عبر update
        return redirect()->route('dashboard.station_teams.index');
    }

    public function show(StationTeam $stationTeam)
    {
        // يمكن عرض التفاصيل إذا لزم الأمر
        return view('dashboard.station_teams.show', compact('stationTeam'));
    }

    public function export()
    {
        // return Excel::download(new StationTeamsExport, 'station_teams.xlsx');
        return back()->with('info', 'ميزة التصدير قيد التطوير.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        // Excel::import(new StationTeamsImport, $request->file('file'));
        // return redirect()->route('dashboard.station_teams.index')->with('success', 'تم استيراد البيانات بنجاح');
        return back()->with('info', 'ميزة الاستيراد قيد التطوير.');
    }
}
