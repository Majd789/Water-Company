<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MasterActivity;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Town; // استدعاء موديل القرية
use Illuminate\Http\Request;

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
        // تم تحديث العلاقات (town بدلاً من unit و station)
        $query = ProjectActivity::with(['project', 'masterActivity', 'town.unit']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('activity_code', 'like', "%{$searchTerm}%")
                  ->orWhere('station_name', 'like', "%{$searchTerm}%"); // البحث باسم المحطة النصي
            });
        }

        $projectActivities = $query->latest()->paginate(3000);
        $projects = Project::orderBy('name')->get();

        return view('dashboard.project-activities.index', compact('projectActivities', 'projects'));
    }

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

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $masterActivities = MasterActivity::orderBy('name')->get();

        // جلب القرى بدلاً من الوحدات والمحطات
        // نقوم بتحميل علاقة الوحدة مع القرية لعرضها في القائمة (مثلاً: "القرية - الوحدة")
        $towns = Town::with('unit')->orderBy('town_name')->get();

        $nextCode = $this->generateNextCode();
        $projectActivity = new ProjectActivity();

        return view('dashboard.project-activities.create', compact('projects', 'masterActivities', 'towns', 'nextCode', 'projectActivity'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'master_activity_id' => 'required|exists:master_activities,id',

            // التحقق من القرية
            'town_id' => 'required|exists:towns,id',

            // اسم المحطة نصي اختياري (أو إجباري حسب رغبتك)
            'station_name' => 'nullable|string|max:255',

            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'unit_capacity' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $code = $this->generateNextCode();
        while(ProjectActivity::where('activity_code', $code)->exists()) {
             $number = (int) substr($code, 4);
             $code = 'ACT-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
        }
        $validatedData['activity_code'] = $code;

        ProjectActivity::create($validatedData);

        return redirect()->route('dashboard.project-activities.index')
                          ->with('success', 'تم إنشاء نشاط المشروع بنجاح والكود هو: ' . $code);
    }

    public function show(ProjectActivity $projectActivity)
    {
        // تحديث العلاقات
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
