<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\ApiResponse;
use App\Models\ManholeReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enum\OperatingEntityName;
use App\Enum\StationOperationStatus;
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
use App\Models\Manhole;

class ManholeReportController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $user = Auth::user();
    if ($user->cannot('manhole_reports.view')) {
        return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403);
    }

    // [تعديل] بناء الاستعلام بشكل ديناميكي
    $query = ManholeReport::where('operator_id', $user->id)
                          ->with(['manhole', 'operator']);

    // التحقق من وجود فلتر للمنهول في الطلب
    if ($request->has('manhole_id') && $request->input('manhole_id') != '') {
        $query->where('manhole_id', $request->input('manhole_id'));
    }
    // تنفيذ الاستعلام مع الترتيب والتصفح
    $reports = $query->latest('report_date')->paginate(20);

    if ($reports->isEmpty()) {
        return $this->successResponse(
            new \stdClass(),
            'لا توجد تقارير مناهل تطابق معايير البحث.'    
        );
    }

    $groupedReports = $reports->groupBy(function($report) {
        return Carbon::parse($report->report_date)->format('Y-m');
    });

    $transformedData = $groupedReports->map(function ($monthlyReports) {
        return ManholeReportResource::collection($monthlyReports);
    });

    return $this->successResponse(
        $transformedData,
        'تم جلب تقارير المناهل بنجاح'
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
        return $this->errorResponse('ليس لديك الصلاحية لإنشاء تقارير مناهل.', 403);
    }

    $validated = $request->validated();
    $validated['operator_id'] = $user->id;

    $manholeId = $validated['manhole_id'];
    $newReportDate = Carbon::parse($validated['report_date']);

    // 2. التحقق من وجود تقرير لنفس المنهل في نفس التاريخ
    $existingReport = ManholeReport::where('operator_id', $user->id)
        ->where('manhole_id', $manholeId) //التحقق من المنهل المحدد
        ->whereDate('report_date', $newReportDate->toDateString())
        ->exists();

    if ($existingReport) {
        // رسالة خطأ محدثة للتوضيح
        return $this->errorResponse('يوجد بالفعل تقرير مرسل لهذا المنهل في هذا التاريخ.', 403);
    }

    // 3. التحقق من تسلسل التقارير لنفس المنهل المحدد فقط
    $lastReport = ManholeReport::where('operator_id', $user->id)
        ->where('manhole_id', $manholeId) // البحث عن آخر تقرير لهذا المنهل فقط
        ->latest('report_date')
        ->first();

    if ($lastReport) {
        $lastReportDate = Carbon::parse($lastReport->report_date);
        // المنطق هنا يبقى كما هو، ولكنه الآن يعمل في السياق الصحيح
        if (!$newReportDate->isSameDay($lastReportDate->addDay())) {
             return $this->errorResponse('يجب عليك إرسال تقرير اليوم السابق أولاً لهذا المنهل.', 403);
        }
    }
    $validated = collect($validated)->filter(function ($value) {
        return !is_null($value) && ($value !== '' || is_bool($value));
    })->toArray();
    
    // 'operating_entity_name' من متحكم آخر ملاحظة: هذا الشرط الخاص بـ 
    // هو ليس موجوداً في $fillable لنموذج ManholeReport،   إزالته إذا لم تكن هناك حاجة له.
    // if (isset($validated['operating_entity_name']) && empty($validated['operating_entity_name'])) {
    //     $validated['operating_entity_name'] = null;
    // }

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
    $user = Auth::user();

    // التحقق من الصلاحية العامة
    if ($user->cannot('manhole_reports.view')) {
        return $this->errorResponse('ليس لديك الصلاحية لعرض هذه البيانات.', 403);
    }
    
    // التحقق من ملكية التقرير (المشغل لا يمكنه عرض تقارير غيره)
    if ($manholeReport->operator_id !== $user->id) {
         return $this->errorResponse('لا يمكنك عرض تقرير لا يخصك.', 403);
    }

    // تحميل العلاقات المطلوبة لعرضها في التطبيق
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

public function getCreateData()
{
    $user = Auth::user();
    $station = $user->station()->select('id', 'station_name')->first();
    $unit = $user->unit()->select('id', 'unit_name as name')->first();
    $manholes = Manhole::where('station_id', $user->station_id)
                       ->select('id', 'manhole_name as name')
                       ->get();

    // جلب قائمة الحالات من الـ Enum وتحويلها إلى الهيكل المطلوب
    $statuses = collect(StationOperationStatus::cases())->map(function ($status) {
        return [
            'value' => $status->value,
            'label' => $status->getLabel(), 
        ];
    });
    // تجميع كل البيانات في مصفوفة واحدة، بما في ذلك الحالات
    $data = [
        'station' => $station,
        'unit' => $unit,
        'manholes' => $manholes,
        'statuses' => $statuses,
    ];

    return $this->successResponse($data, 'تم جلب بيانات إنشاء تقرير المنهل بنجاح.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManholeReport $manholeReport)
    {
        //
    }
}
