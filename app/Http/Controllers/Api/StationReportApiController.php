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

class StationReportApiController extends Controller
{
    use ApiResponse;


    public function index(Request $request){
        $user = Auth::user();
        if ($user->cannot('station_reports.view')) {
            // إذا لم يكن لدى المستخدم الصلاحية، نرجع رسالة خطأ باستخدام الـ Trait
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403); // 403 Forbidden
        }

        $reports = StationReport::where('operator_id',$user->id)->with(['station', 'operator'])
                         ->latest('report_date')
                         ->paginate(20);

        if ($reports->isEmpty()) {
            return $this->successResponse(
                [], // إرجاع مصفوفة فارغة بدلاً من null
                'لا توجد تقارير لعرضها.'
            );
        }

        return $this->successResponse(
            StationReportResource::collection($reports),
            'تم جلب التقارير بنجاح'
        );
    }
    public function store(StationReportStoreRequest $request)
    {
        $user = Auth::user();
        if ($user->cannot('station_reports.create')) {
            return $this->errorResponse('ليس لديك الصلاحية لانشاء التقارير  .', 403); // 403 Forbidden
        }
        $validated = $request->validated();
       $validated['operator_id'] = $user->id;
        // [إضافة] معالجة اسم الجهة المشغلة تلقائياً
        if ($validated['operating_entity'] === 'water_company') {
            $validated['operating_entity_name'] = 'water_company';
        }
        // إنشاء التقرير
        $report = StationReport::create($validated);
        // [تعديل] استخدام ApiResource لإرجاع استجابة JSON نظيفة ومنظمة
        return $this->successResponse(
            new StationReportResource($report),
            'تم إنشاء التقرير بنجاح',
            201 // Status code for resource creation
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

}


