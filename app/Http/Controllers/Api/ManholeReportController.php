<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\ApiResponse;
use App\Models\ManholeReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enum\OperatingEntityName;
use App\Models\StationReport;
use App\Http\Requests\StationReportStoreRequest;
use App\Http\Requests\StationReportUpdateRequest; // [جديد]
use App\Enum\UserLevel;
use App\Models\Station;
use App\Http\Resources\StationReportResource;
use App\Models\PumpingSector;
use App\Models\Unit;
use App\Http\Resources\ManholeReportResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ManholeReportStoreRequest;
use App\Http\Requests\ManholeReportUpdateRequest;

class ManholeReportController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
            $user = Auth::user();
        if ($user->cannot('manhole_reports.view')) {
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403); // 403 Forbidden
        }

        $reports = ManholeReport::where('operator_id',$user->id)->with(['manhole', 'operator', 'station','unit'])
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
            return ManholeReportResource::collection($monthlyReports);
        });

        return $this->successResponse(
            $transformedData,
            'تم جلب التقارير  بنجاح'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManholeReportStoreRequest $request)
    {
         $user = Auth::user();

        // 1. التحقق من الصلاحيات
        if ($user->cannot('manhole_reports.create')) {
            return $this->errorResponse(' ليس لديك الصلاحية لإنشاء تقارير مناهل .', 403);
        }

        $validated = $request->validated();
        $validated['operator_id'] = $user->id;

        $newReportDate = Carbon::parse($validated['report_date']);

        // 2. التحقق من وجود تقرير لنفس التاريخ
        $existingReport = ManholeReport::where('operator_id', $user->id)
            ->whereDate('report_date', $newReportDate->toDateString())
            ->exists();
        if ($existingReport) {
            return $this->errorResponse('يوجد بالفعل تقرير مرسل لهذا التاريخ.', 403);
        }

        // 3. التحقق من تسلسل التقارير
        $lastReport = ManholeReport::where('operator_id', $user->id)
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

        $report = ManholeReport::create($validated);

        return $this->successResponse(
            new ManholeReportResource($report),
            'تم إنشاء التقرير بنجاح',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ManholeReport $manholeReport)
    {
        //
        $user = Auth::user();
        if ($user->cannot('manhole_reports.view')) {
            return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403); // 403 Forbidden
        }

        $manholeReport->load(['manhole', 'station', 'operator', 'unit']);
        return $this->successResponse(
            new ManholeReportResource($manholeReport),
            'تم جلب التقرير بنجاح'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ManholeReport $manholeReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(ManholeReportUpdateRequest $request, ManholeReport $manholeReport)
    {
        $user = Auth::user();

        // 1. التحقق من الصلاحية العامة للتعديل
        if ($user->cannot('manhole_reports.update')) {
            return $this->errorResponse('ليس لديك الصلاحية لتعديل تقارير المناهل.', 403);
        }

        // 2. التحقق من ملكية التقرير (المشغل لا يمكنه تعديل تقارير غيره)
        // يمكنك تعديل هذا الشرط للسماح للمدراء بالتعديل مثلاً:
        // if ($user->id !== $manholeReport->operator_id && !$user->hasRole('admin'))
        if ($user->id !== $manholeReport->operator_id) {
            return $this->errorResponse('لا يمكنك تعديل تقرير لا يخصك.', 403);
        }

        // 3. التحقق من البيانات المدخلة وتحديث التقرير
        // سيتم التحقق تلقائياً بواسطة ManholeReportUpdateRequest
        $validated = $request->validated();

        // تحديث التقرير بالبيانات التي تم التحقق منها
        $manholeReport->update($validated);

        // 4. إرجاع الاستجابة مع البيانات المحدثة
        return $this->successResponse(
            new ManholeReportResource($manholeReport),
            'تم تحديث التقرير بنجاح'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManholeReport $manholeReport)
    {
        //
    }
}
