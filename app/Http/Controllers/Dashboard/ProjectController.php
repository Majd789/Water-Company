<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Station;
use App\Models\Town;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:projects.view')->only(['index', 'show']);
        $this->middleware('permission:projects.create')->only(['create', 'store']);
        $this->middleware('permission:projects.edit')->only(['edit', 'update']);
        $this->middleware('permission:projects.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        // The corrected line:
        $projects = Project::latest()->paginate(15); // قم بإزالة ->with('organization')
        
        return view('dashboard.projects.index', compact('projects'));
    }
    /**
     * Show the form for creating a new resource.
     */
   public function create()
    {
        // نحتاج لجلب بيانات المواقع لملء القوائم المنسدلة في النموذج
        $units = Unit::all();
        $towns = Town::all();
        $stations = Station::all();

        return view('dashboard.projects.create', compact('units', 'towns', 'stations'));
    }

       /**
     * تخزين مشروع جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. قواعد التحقق من صحة البيانات
        $validatedData = $request->validate([
            // --- التحقق من بيانات المشروع الرئيسي ---
            'institution_ref_number' => 'required|string|max:255|unique:projects,institution_ref_number',
            'institution_ref_date'   => 'required|date',
            'hac_ref_number'         => 'nullable|string|max:255',
            'hac_ref_date'           => 'nullable|date',
            'name'                   => 'required|string|max:255',
            'type'                   => 'required|in:تقييم احتياج,تنفيذ,أخرى',
            'organization'           => 'required|string|max:255',
            'donor'                  => 'required|string|max:255',
            'total_cost'             => 'required|numeric|min:0',
            'duration_days'          => 'required|integer|min:1',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after_or_equal:start_date',
            'supervisor_name'        => 'required|string|max:255',
            'supervisor_contact'     => 'nullable|string|max:255',
            'status'                 => 'required|string|max:255',

            // --- التحقق من بيانات الأنشطة (كمصفوفة) ---
            'activities'             => 'present|array', // يجب أن تكون مصفوفة الأنشطة موجودة حتى لو كانت فارغة
            'activities.*.unit_id'   => 'nullable|exists:units,id',
            'activities.*.town_id'   => 'nullable|exists:towns,id',
            'activities.*.station_id'=> 'nullable|exists:stations,id',
            'activities.*.value'     => 'required|numeric|min:0',
            'activities.*.activity_name' => 'required|string|max:255',
            'activities.*.execution_status' => 'required|string|max:255',
            'activities.*.contractor_name' => 'nullable|string|max:255',
            // ... يمكنك إضافة باقي حقول الأنشطة هنا بنفس الطريقة
        ]);

        // 2. استخدام Transaction لضمان سلامة البيانات
        try {
            DB::beginTransaction();

            // 3. إنشاء سجل المشروع الرئيسي
            $project = Project::create([
                'institution_ref_number' => $validatedData['institution_ref_number'],
                'institution_ref_date'   => $validatedData['institution_ref_date'],
                'hac_ref_number'         => $validatedData['hac_ref_number'] ?? null,
                'hac_ref_date'           => $validatedData['hac_ref_date'] ?? null,
                'name'                   => $validatedData['name'],
                'type'                   => $validatedData['type'],
                'organization'           => $validatedData['organization'],
                'donor'                  => $validatedData['donor'],
                'total_cost'             => $validatedData['total_cost'],
                'duration_days'          => $validatedData['duration_days'],
                'start_date'             => $validatedData['start_date'],
                'end_date'               => $validatedData['end_date'],
                'supervisor_name'        => $validatedData['supervisor_name'],
                'supervisor_contact'     => $validatedData['supervisor_contact'] ?? null,
                'status'                 => $validatedData['status'],
            ]);

            // 4. إنشاء سجلات الأنشطة وربطها بالمشروع
            if (!empty($validatedData['activities'])) {
                foreach ($validatedData['activities'] as $activityData) {
                    // إضافة project_id يدوياً للبيانات قبل الإنشاء
                    $activityData['project_id'] = $project->id;
                    ProjectActivity::create($activityData);
                }
            }

            // 5. تأكيد العملية إذا تمت بنجاح
            DB::commit();

            return redirect()->route('dashboard.projects.index')->with('success', 'تم إنشاء المشروع وأنشطته بنجاح.');

        } catch (\Exception $e) {
            // 6. التراجع عن كل العمليات في حال حدوث أي خطأ
            DB::rollBack();
            // تسجيل الخطأ للمطورين وإظهار رسالة عامة للمستخدم
            // Log::error($e->getMessage()); 
            return back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء حفظ المشروع. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
     public function show(Project $project)
    {
        // جلب المشروع مع جميع أنشطته، ومع كل نشاط جلب اسم الوحدة والبلدة والمحطة
        $project->load('activities.unit', 'activities.town', 'activities.station');

        return view('dashboard.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // نحتاج لنفس بيانات نموذج الإنشاء
        $units = Unit::all();
        $towns = Town::all();
        $stations = Station::all();
        
        // جلب المشروع مع أنشطته لتعبئة الحقول
        $project->load('activities');

        return view('dashboard.projects.edit', compact('project', 'units', 'towns', 'stations'));
    }

   /**
     * تحديث بيانات مشروع في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Project $project)
    {
        // 1. قواعد التحقق من صحة البيانات (لاحظ تجاهل الرقم المرجعي الحالي للمشروع)
        $validatedData = $request->validate([
            // --- التحقق من بيانات المشروع الرئيسي ---
            'institution_ref_number' => 'required|string|max:255|unique:projects,institution_ref_number,' . $project->id,
            'institution_ref_date'   => 'required|date',
            'hac_ref_number'         => 'nullable|string|max:255',
            'hac_ref_date'           => 'nullable|date',
            'name'                   => 'required|string|max:255',
            'type'                   => 'required|in:تقييم احتياج,تنفيذ,أخرى',
            'organization'           => 'required|string|max:255',
            'donor'                  => 'required|string|max:255',
            'total_cost'             => 'required|numeric|min:0',
            'duration_days'          => 'required|integer|min:1',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after_or_equal:start_date',
            'supervisor_name'        => 'required|string|max:255',
            'supervisor_contact'     => 'nullable|string|max:255',
            'status'                 => 'required|string|max:255',

            // --- التحقق من بيانات الأنشطة (كمصفوفة) ---
            'activities'             => 'present|array',
            'activities.*.unit_id'   => 'nullable|exists:units,id',
            'activities.*.town_id'   => 'nullable|exists:towns,id',
            'activities.*.station_id'=> 'nullable|exists:stations,id',
            'activities.*.value'     => 'required|numeric|min:0',
            'activities.*.activity_name' => 'required|string|max:255',
            'activities.*.execution_status' => 'required|string|max:255',
        ]);

        // 2. استخدام Transaction لضمان سلامة البيانات
        try {
            DB::beginTransaction();

            // 3. تحديث سجل المشروع الرئيسي
            $project->update([
                'institution_ref_number' => $validatedData['institution_ref_number'],
                'institution_ref_date'   => $validatedData['institution_ref_date'],
                'hac_ref_number'         => $validatedData['hac_ref_number'] ?? null,
                'hac_ref_date'           => $validatedData['hac_ref_date'] ?? null,
                'name'                   => $validatedData['name'],
                'type'                   => $validatedData['type'],
                'organization'           => $validatedData['organization'],
                'donor'                  => $validatedData['donor'],
                'total_cost'             => $validatedData['total_cost'],
                'duration_days'          => $validatedData['duration_days'],
                'start_date'             => $validatedData['start_date'],
                'end_date'               => $validatedData['end_date'],
                'supervisor_name'        => $validatedData['supervisor_name'],
                'supervisor_contact'     => $validatedData['supervisor_contact'] ?? null,
                'status'                 => $validatedData['status'],
            ]);
            
            // 4. تحديث الأنشطة: حذف القديم وإضافة الجديد
            $project->activities()->delete(); // حذف جميع الأنشطة الحالية المرتبطة بالمشروع

            if (!empty($validatedData['activities'])) {
                foreach ($validatedData['activities'] as $activityData) {
                    $activityData['project_id'] = $project->id;
                    ProjectActivity::create($activityData);
                }
            }

            // 5. تأكيد العملية
            DB::commit();

            return redirect()->route('dashboard.projects.show', $project->id)->with('success', 'تم تحديث المشروع وأنشطته بنجاح.');

        } catch (\Exception $e) {
            // 6. التراجع عن كل العمليات في حال حدوث خطأ
            DB::rollBack();
            // Log::error($e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث المشروع. يرجى المحاولة مرة أخرى.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Project $project)
    {
        try {
            $project->delete(); // سيقوم بحذف الأنشطة المرتبطة تلقائياً بسبب onCascade
            return redirect()->route('dashboard.projects.index')->with('success', 'تم حذف المشروع بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف المشروع.');
        }
    }
}
