<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MetricController extends Controller
{
    public function __construct()
    {
        // استخدام صلاحيات عامة، يمكن تخصيصها أكثر إذا لزم الأمر
        $this->middleware('permission:metrics.view')->only('index');
        $this->middleware('permission:metrics.create')->only(['create', 'store']);
        $this->middleware('permission:metrics.edit')->only(['edit', 'update']);
        $this->middleware('permission:metrics.delete')->only('destroy');
    }

    /**
     * عرض جميع القياسات. يمكن فلترتها حسب النوع.
     */
    public function index(Request $request)
    {
        $metrics = Metric::with('metricable')->latest();

        // مثال لفلترة حسب النوع
        if ($request->has('type')) {
            // تحويل النوع النصي إلى مسار الموديل الكامل
            // e.g., 'station' -> 'App\Models\Station'
            $modelClass = 'App\\Models\\' . ucfirst($request->type);
            if (class_exists($modelClass)) {
                $metrics->where('metricable_type', $modelClass);
            }
        }

        $metrics = $metrics->paginate(50);

        return view('dashboard.metrics.index', compact('metrics'));
    }

    /**
     * عرض نموذج إنشاء قياس جديد.
     * يتطلب تحديد نوع العنصر (الأب) والـ ID الخاص به.
     */
    public function create(Request $request)
    {
        // يجب أن نرسل نوع وهوية العنصر الذي نريد إضافة مقياس له
        $request->validate([
            'metricable_type' => 'required|string',
            'metricable_id' => 'required|integer',
        ]);

        $metricable_type = 'App\\Models\\' . $request->metricable_type;
        $metricable_id = $request->metricable_id;

        // التأكد من أن العنصر (الأب) موجود
        if (!class_exists($metricable_type) || !$metricable_type::find($metricable_id)) {
            abort(404, 'العنصر المحدد غير موجود.');
        }

        return view('dashboard.metrics.create', compact('metricable_type', 'metricable_id'));
    }

    /**
     * تخزين قياس جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'metricable_type' => 'required|string',
            'metricable_id' => 'required|integer',
            'metric_key' => 'required|string|max:255',
            'value' => 'required|numeric',
            'unit' => 'nullable|string|max:50',
            'measured_at' => 'nullable|date',
        ]);

        $modelClass = $validatedData['metricable_type'];
        $modelId = $validatedData['metricable_id'];

        // البحث عن النموذج الأب
        $parentModel = $modelClass::findOrFail($modelId);

        // إنشاء المقياس وربطه بالنموذج الأب
        $parentModel->metrics()->create([
            'metric_key' => $validatedData['metric_key'],
            'value' => $validatedData['value'],
            'unit' => $validatedData['unit'],
            'measured_at' => $validatedData['measured_at'] ?? now(),
        ]);

        // العودة إلى صفحة العنصر الأب، أو صفحة مخصصة
        // هذا الجزء قد يحتاج إلى تعديل حسب مسار العودة المطلوب
        return redirect()->back()->with('success', 'تمت إضافة المقياس بنجاح.');
    }

    /**
     * عرض نموذج تعديل قياس معين.
     */
    public function edit(Metric $metric)
    {
        return view('dashboard.metrics.edit', compact('metric'));
    }

    /**
     * تحديث بيانات قياس معين.
     */
    public function update(Request $request, Metric $metric)
    {
        $validatedData = $request->validate([
            'metric_key' => 'required|string|max:255',
            'value' => 'required|numeric',
            'unit' => 'nullable|string|max:50',
            'measured_at' => 'nullable|date',
        ]);

        $metric->update($validatedData);

        return redirect()->back()->with('success', 'تم تحديث المقياس بنجاح.');
    }

    /**
     * حذف قياس معين.
     */
    public function destroy(Metric $metric)
    {
        $metric->delete();

        return redirect()->back()->with('success', 'تم حذف المقياس بنجاح.');
    }
}
