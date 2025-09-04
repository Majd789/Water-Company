<?php

namespace App\Http\Controllers\Api;

use App\Enum\OperatingEntityName;
use App\Http\Controllers\Controller;
use App\Models\StationReport;
use App\Http\Requests\StationReportStoreRequest;
use App\Enum\UserLevel;
use App\Models\Station;
use App\Http\Traits\ApiResponse;
use App\Http\Resources\StationReportResource;
use App\Models\PumpingSector;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationReportApiController extends Controller
{
    use ApiResponse;


    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->cannot('station_reports.view')) {
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403); // 403 Forbidden
        }

        $reports = StationReport::where('operator_id',$user->id)->with(['station', 'operator'])
                         ->latest('report_date')
                         ->paginate(20);

        if ($reports->isEmpty()) {
            return $this->successResponse(
                new \stdClass(), // إرجاع كائن فارغ {} بدلاً من مصفوفة
                'لا توجد تقارير لعرضها في هذا النطاق الزمني.'
            );
        }

        $groupedReports = $reports->groupBy(function($report) {
            return Carbon::parse($report->report_date)->format('Y-m'); // سيقوم بالتجميع حسب "2025-09", "2025-08" etc.
        });

        $transformedData = $groupedReports->map(function ($monthlyReports) {
            return StationReportResource::collection($monthlyReports);
        });

        return $this->successResponse(
            $transformedData,
            'تم جلب التقارير  بنجاح'
        );
    }
    public function store(StationReportStoreRequest $request)
{
    $user = Auth::user();
    if ($user->cannot('station_reports.create') && $user->level == UserLevel::STATION_OPERATOR) {
        return $this->errorResponse('ليس لديك الصلاحية لانشاء التقارير .', 403);
    }
    $validated = $request->validated();
    $validated['operator_id'] = $user->id;

    // تعديل: لا تقم بتعيين قيمة لـ operating_entity_name إذا كانت الجهة water_company
    if ($validated['operating_entity'] !== 'shared') {
        unset($validated['operating_entity_name']);
    }

    $report = StationReport::create($validated);

    return $this->successResponse(
        new StationReportResource($report),
        'تم إنشاء التقرير بنجاح',
        201
    );
}

    public function show(StationReport $stationReport)
    {
        $user = Auth::user();

        // 1. التحقق من الصلاحية العامة للعرض
        if ($user->cannot('station_reports.view')) {
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403);
        }

        // 2. التحقق من أن المستخدم هو نفسه منشئ التقرير (الأمان)
        if ($stationReport->operator_id !== $user->id) {
            return $this->errorResponse('لا يمكنك عرض هذا التقرير لأنه لا يخصك.', 403);
        }

        // تحميل العلاقات لعرضها في الـ Resource
        $stationReport->load(['station', 'operator']);

        // إرجاع استجابة نجاح مع بيانات التقرير
        return $this->successResponse(
            new StationReportResource($stationReport),
            'تم جلب بيانات التقرير بنجاح'
        );
  }
  public function destroy(StationReport $stationReport)
  {
      $user = Auth::user();

        // 1. التحقق من الصلاحية العامة للحذف
        if ($user->cannot('station_reports.delete')) {
            return $this->errorResponse('ليس لديك الصلاحية لحذف التقارير.', 403);
        }

        // 2. التحقق من أن المستخدم هو نفسه منشئ التقرير (الأمان)
        if ($stationReport->operator_id !== $user->id) {
            return $this->errorResponse('لا يمكنك حذف هذا التقرير لأنه لا يخصك.', 403);
        }

        // تنفيذ عملية الحذف
        $stationReport->delete();

        // إرجاع رسالة نجاح بدون بيانات
        return $this->successResponse(
            null, // لا توجد بيانات لإرجاعها بعد الحذف
            'تم حذف التقرير بنجاح'
        );
    }

      public function getCreateReportData()
    {
        $user = auth()->user();
        $units = Unit::all();
        $userUnitId = $user->unit_id;
        $query = PumpingSector::query()->where('station_id', $user->station_id);
        $selectedUnitId = $request->unit_id ?? $userUnitId;
        $PumpingSectors = $query->with(['station', 'station.town'])->get();
          
        // جلب قائمة المنظمات
        $organizations = collect(OperatingEntityName::cases())->map(function ($case) {
            return [
                'value' => $case->value, // e.g., 'islamic_relief'
                'label' => $case->getLabel(), // e.g., 'الإغاثة الإسلامية'
            ];
        })->sortBy('label')->values(); // نرتبها أبجدياً حسب التسمية

        // تجميع كل البيانات في مصفوفة واحدة
        $data = [
            'organizations' => $organizations,
             'PumpingSectors' => $PumpingSectors,
             'units' => $units,
             'station_id' => $user->station_id,
             'unit_id' => $user->unit_id,
          
        ];
        
        return $this->successResponse($data, 'تم جلب البيانات اللازمة لإنشاء التقرير بنجاح.');
    }

}
