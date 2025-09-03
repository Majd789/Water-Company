<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StationReport;
use App\Http\Requests\StationReportStoreRequest;
use App\Enum\UserLevel;
use App\Models\Station;
use App\Http\Traits\ApiResponse;
use App\Http\Resources\StationReportResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\StationReportUpdateRequest;

class StationReportApiController extends Controller
{
    use ApiResponse;


    public function index(Request $request){
        $user = Auth::user();
        if ($user->cannot('station_reports.view')) {
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403); // 403 Forbidden
        }

        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $reports = StationReport::where('operator_id', $user->id)
                                ->with(['station', 'operator', 'unit'])
                                ->whereBetween('report_date', [$startDate, $endDate])
                                ->latest('report_date')
                                ->get();


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
            return $this->errorResponse('ليس لديك الصلاحية لانشاء التقارير  .', 403); // 403 Forbidden
        }
        $validated = $request->validated();
        $validated['operator_id'] = $user->id;
        $validated['unit_id']= $user->unit_id;
        $validated['station_id']= $user->station_id;


        if ($validated['operating_entity'] === 'water_company') {
            $validated['operating_entity_name'] = 'water_company';
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

     public function update(StationReportUpdateRequest $request, StationReport $stationReport)
    {

        $user = Auth::user();
        if ($user->cannot('station_reports.edit') && $user->level == UserLevel::STATION_OPERATOR) {

            return $this->errorResponse('ليس لديك الصلاحية لتعديل هذه البيانات.', 403); // 403 Forbidden
        }

        // 2. التحقق من أن المستخدم هو نفسه منشئ التقرير (الأمان)
        if ($stationReport->operator_id !== $user->id) {
            return $this->errorResponse('لا يمكنك تعديل هذا التقرير لأنه لا يخصك.', 403);
        }

        // الحصول على البيانات التي تم التحقق من صحتها من الـ Form Request
        $validated = $request->validated();
        $validated['updated_by'] = $user->id;
        // تنفيذ عملية التحديث
        $stationReport->update($validated);

        // إرجاع استجابة نجاح مع بيانات التقرير المحدثة
        return $this->successResponse(
            new StationReportResource($stationReport),
            'تم تحديث التقرير بنجاح'
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

}


