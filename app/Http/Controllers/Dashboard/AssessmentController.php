<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:assessments.view')->only('index');
        $this->middleware('permission:assessments.create')->only(['create', 'store']);
        $this->middleware('permission:assessments.edit')->only(['edit', 'update']);
        $this->middleware('permission:assessments.delete')->only('destroy');
    }

    /**
     * عرض جميع التقييمات، مع إمكانية الفلترة.
     */
    public function index(Request $request)
    {
        $assessments = Assessment::with('assessmentable')->latest();

        if ($request->has('type')) {
            $modelClass = 'App\\Models\\' . ucfirst($request->type);
            if (class_exists($modelClass)) {
                $assessments->where('assessmentable_type', $modelClass);
            }
        }

        $assessments = $assessments->paginate(1000);

        return view('dashboard.assessments.index', compact('assessments'));
    }

    /**
     * عرض نموذج إنشاء تقييم جديد.
     * يتطلب تحديد نوع العنصر (الأب) والـ ID الخاص به.
     */
    public function create(Request $request)
    {
        $request->validate([
            'assessmentable_type' => 'required|string',
            'assessmentable_id' => 'required|integer',
        ]);

        $assessmentable_type = 'App\\Models\\' . $request->assessmentable_type;
        $assessmentable_id = $request->assessmentable_id;

        // التأكد من أن العنصر (الأب) موجود
        if (!class_exists($assessmentable_type) || !$assessmentable_type::find($assessmentable_id)) {
            abort(404, 'العنصر المحدد غير موجود.');
        }

        return view('dashboard.assessments.create', compact('assessmentable_type', 'assessmentable_id'));
    }

    /**
     * تخزين تقييم جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'assessmentable_type' => 'required|string',
            'assessmentable_id' => 'required|integer',
            'assessment_key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $modelClass = $validatedData['assessmentable_type'];
        $modelId = $validatedData['assessmentable_id'];

        $parentModel = $modelClass::findOrFail($modelId);

        $parentModel->assessments()->create([
            'assessment_key' => $validatedData['assessment_key'],
            'value' => $validatedData['value'],
            'notes' => $validatedData['notes'],
        ]);

        return redirect()->back()->with('success', 'تمت إضافة التقييم بنجاح.');
    }

    /**
     * عرض نموذج تعديل تقييم معين.
     */
    public function edit(Assessment $assessment)
    {
        return view('dashboard.assessments.edit', compact('assessment'));
    }

    /**
     * تحديث بيانات تقييم معين.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validatedData = $request->validate([
            'assessment_key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $assessment->update($validatedData);

        return redirect()->back()->with('success', 'تم تحديث التقييم بنجاح.');
    }

    /**
     * حذف تقييم معين.
     */
    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح.');
    }
}
