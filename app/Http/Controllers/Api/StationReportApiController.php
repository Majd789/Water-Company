<?php

namespace App\Http\Controllers\Api;

use App\Enum\OperatingEntityName;
use App\Http\Controllers\Controller;
use App\Models\StationReport;
use App\Http\Requests\StationReportStoreRequest;
use App\Http\Requests\StationReportUpdateRequest; // [جديد]
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

        // 1. التحقق من الصلاحيات
        if ($user->cannot('station_reports.create')) {
            return $this->errorResponse('ليس لديك الصلاحية لإنشاء التقارير.', 403);
        }

        $validated = $request->validated();
        $validated['operator_id'] = $user->id;

        $newReportDate = Carbon::parse($validated['report_date']);

        // 2. التحقق من وجود تقرير لنفس التاريخ
        $existingReport = StationReport::where('operator_id', $user->id)
            ->whereDate('report_date', $newReportDate->toDateString())
            ->exists();
        if ($existingReport) {
            return $this->errorResponse('يوجد بالفعل تقرير مرسل لهذا التاريخ.', 403);
        }

        // 3. التحقق من تسلسل التقارير
        $lastReport = StationReport::where('operator_id', $user->id)
            ->latest('report_date')
            ->first();
        if ($lastReport) {
            $lastReportDate = Carbon::parse($lastReport->report_date);
            if (!$newReportDate->equalTo($lastReportDate->addDay())) {
                return $this->errorResponse('يجب عليك إرسال تقرير اليوم السابق أولاً.', 403);
            }
        }
    
        // إزالة الحقول التي قيمها فارغة أو صفرية
        $validated = collect($validated)->filter(function ($value) {
            return !is_null($value) && ($value !== '' || is_bool($value));
        })->toArray();
    
        // تعيين القيمة إلى null إذا كانت فارغة أو صفرية، بدلاً من إزالتها بالكامل
        if (isset($validated['operating_entity_name']) && empty($validated['operating_entity_name'])) {
            $validated['operating_entity_name'] = null;
        }

        $report = StationReport::create($validated);

        return $this->successResponse(
            new StationReportResource($report),
            'تم إنشاء التقرير بنجاح',
            201
        );
    }

    public function update(StationReportUpdateRequest $request, StationReport $stationReport)
    {
        $user = Auth::user();

        // 1. التحقق من الصلاحيات: هل يملك المستخدم صلاحية التعديل؟
        if ($user->cannot('station_reports.edit')) {
            return $this->errorResponse('ليس لديك الصلاحية لتعديل التقارير.', 403);
        }

        // 2. التحقق من أن المستخدم هو نفسه منشئ التقرير
        if ($stationReport->operator_id !== $user->id) {
            return $this->errorResponse('لا يمكنك تعديل هذا التقرير لأنه لا يخصك.', 403);
        }

        $validated = $request->validated();

        // 3. التحقق من تسلسل التقارير عند محاولة تعديل التاريخ
        if (isset($validated['report_date'])) {
            $newReportDate = Carbon::parse($validated['report_date']);
            $originalReportDate = Carbon::parse($stationReport->report_date);

            // جلب التقرير الذي يسبق التقرير الحالي
            $previousReport = StationReport::where('operator_id', $user->id)
                                          ->where('report_date', '<', $originalReportDate)
                                          ->latest('report_date')
                                          ->first();
            
            // إذا كان هناك تقرير سابق، تأكد أن التاريخ الجديد يتبعه مباشرة
            if ($previousReport && !$newReportDate->equalTo(Carbon::parse($previousReport->report_date)->addDay())) {
                return $this->errorResponse('تاريخ التقرير الجديد يجب أن يتبع التقرير السابق مباشرة.', 403);
            }
        }

        // إزالة الحقول التي قيمها فارغة أو صفرية لتجنب تحديثها
        $validated = collect($validated)->filter(function ($value) {
            return !is_null($value) && ($value !== '' || is_bool($value));
        })->toArray();
    
        // تحديث التقرير بالبيانات الجديدة
        $stationReport->update($validated);
    
        // إعادة تحميل التقرير مع العلاقات المحدثة
        $stationReport->load(['station', 'operator']);

        return $this->successResponse(
            new StationReportResource($stationReport),
            'تم تحديث التقرير بنجاح'
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

    public function getCreateReportData(Request $request)
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
