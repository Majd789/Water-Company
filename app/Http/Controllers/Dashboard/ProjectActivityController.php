<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ProjectActivitiesExport;
use App\Http\Controllers\Controller;
use App\Imports\ProjectActivitiesImport;
use App\Models\MasterActivity;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Town;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProjectActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:project_activities.view')->only(['index', 'show']);
        $this->middleware('permission:project_activities.create')->only(['create', 'store']);
        $this->middleware('permission:project_activities.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_activities.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = ProjectActivity::with(['project', 'masterActivity', 'town.unit']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('activity_code', 'like', "%{$searchTerm}%")
                  ->orWhere('station_name', 'like', "%{$searchTerm}%");
            });
        }

        $projectActivities = $query->latest()->paginate(3000);
        $projects = Project::orderBy('name')->get();

        return view('dashboard.project-activities.index', compact('projectActivities', 'projects'));
    }

    /*
    // تم تعليق دالة التوليد التلقائي
    private function generateNextCode()
    {
        $lastActivity = ProjectActivity::where('activity_code', 'like', 'ACT-%')
                                       ->orderBy('id', 'desc')
                                       ->first();

        if (!$lastActivity) {
            return 'ACT-00001';
        }

        $number = (int) substr($lastActivity->activity_code, 4);
        return 'ACT-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
    }
    */

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $masterActivities = MasterActivity::orderBy('name')->get();
        $towns = Town::with('unit')->orderBy('town_name')->get();

        // $nextCode = $this->generateNextCode(); // تم التعليق
        $projectActivity = new ProjectActivity();

        // تم إزالة nextCode من الـ compact
        return view('dashboard.project-activities.create', compact('projects', 'masterActivities', 'towns', 'projectActivity'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // تمت إضافة كود النشاط هنا ليصبح إدخالاً يدوياً واجباً وفريداً
            'activity_code' => 'required|string|unique:project_activities,activity_code|max:50',

            'project_id' => 'required|exists:projects,id',
            'master_activity_id' => 'required|exists:master_activities,id',
            'town_id' => 'required|exists:towns,id',
            'station_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'unit_capacity' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        /*
        // تم تعليق كود التوليد التلقائي قبل الحفظ
        $code = $this->generateNextCode();
        while(ProjectActivity::where('activity_code', $code)->exists()) {
             $number = (int) substr($code, 4);
             $code = 'ACT-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
        }
        $validatedData['activity_code'] = $code;
        */

        ProjectActivity::create($validatedData);

        // تم تعديل رسالة النجاح
        return redirect()->route('dashboard.project-activities.index')
                          ->with('success', 'تم إنشاء نشاط المشروع بنجاح.');
    }

    public function show(ProjectActivity $projectActivity)
    {
        $projectActivity->load(['project', 'masterActivity', 'town.unit', 'tasks.projectContractor.contractor']);
        return view('dashboard.project-activities.show', compact('projectActivity'));
    }

    public function edit(ProjectActivity $projectActivity)
    {
        $projects = Project::orderBy('name')->get();
        $masterActivities = MasterActivity::orderBy('name')->get();
        $towns = Town::with('unit')->orderBy('town_name')->get();

        return view('dashboard.project-activities.edit', compact('projectActivity', 'projects', 'masterActivities', 'towns'));
    }

    public function update(Request $request, ProjectActivity $projectActivity)
    {
        $validatedData = $request->validate([
            // عند التعديل يجب استثناء الآيدي الحالي من فحص التكرار
            'activity_code' => 'required|string|max:50|unique:project_activities,activity_code,' . $projectActivity->id,

            'project_id' => 'required|exists:projects,id',
            'master_activity_id' => 'required|exists:master_activities,id',
            'town_id' => 'required|exists:towns,id',
            'station_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'unit_capacity' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $projectActivity->update($validatedData);

        return redirect()->route('dashboard.project-activities.index')
                        ->with('success', 'تم تحديث نشاط المشروع بنجاح.');
    }
    public function export(Request $request)
    {
        // نمرر الـ request للكلاس لكي يصدر البيانات المفلترة (إن وجدت)
        return Excel::download(new ProjectActivitiesExport($request), 'project_activities.xlsx');
    }
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    try {
        Excel::import(new ProjectActivitiesImport, $request->file('file'));
        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح وتمت معالجة البلدات!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
    }
}
    public function destroy(ProjectActivity $projectActivity)
    {
        if ($projectActivity->tasks()->exists()) {
            return redirect()->route('dashboard.project-activities.index')
                             ->with('error', 'لا يمكن حذف النشاط لوجود مهام مقاولين مرتبطة به.');
        }

        $projectActivity->delete();

        return redirect()->route('dashboard.project-activities.index')
                         ->with('success', 'تم حذف نشاط المشروع بنجاح.');
    }
}
