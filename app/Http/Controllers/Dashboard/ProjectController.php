<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ProjectsExport;
use App\Http\Controllers\Controller;
use App\Models\HandoverStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectGeneralStatus;
use App\Models\ProjectMainStatus;
use App\Models\ProjectType;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Imports\ProjectsImport;
use Maatwebsite\Excel\Facades\Excel;
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
    public function index(Request $request)
    {
        $userUnitId = auth()->user()->unit_id;
        $units = Unit::all();
        $organizations = Organization::all();

        $query = Project::with([
            'organization',
            'projectType',
            'mainStatus',
            'generalStatus',
            'handoverStatus'
        ]);

        $selectedUnitId = $request->unit_id ?? $userUnitId;

        // تصفية المشاريع التي لديها أنشطة في الوحدة المحددة
        if (!empty($selectedUnitId)) {
            $query->whereHas('activities', function ($activityQuery) use ($selectedUnitId) {
                $activityQuery->where('unit_id', $selectedUnitId);
            });
        }

        // تصفية حسب المنظمة
        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        // بحث
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('project_code', 'like', "%{$searchTerm}%");
            });
        }

        $projects = $query->latest()->paginate(1000);

        return view('dashboard.projects.index', compact('projects', 'units', 'organizations', 'selectedUnitId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        $projectTypes = ProjectType::all();
        $mainStatuses = ProjectMainStatus::all();
        $generalStatuses = ProjectGeneralStatus::all();
        $handoverStatuses = HandoverStatus::all();

        return view('dashboard.projects.create', compact('organizations', 'projectTypes', 'mainStatuses', 'generalStatuses', 'handoverStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData =$request->validate([
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'donor_name' => 'nullable|string|max:255',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:50',
            'project_type_id' => 'required|exists:project_types,id',
            'main_status_id' => 'required|exists:project_main_statuses,id',
            'general_status_id' => 'required|exists:project_general_statuses,id',
            'handover_status_id' => 'required|exists:handover_statuses,id',
            'total_value' => 'nullable|numeric',
            'contract_date' => 'nullable|date',
            'total_duration_days' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hac_issue_number' => 'nullable|string|max:100',
            'hac_issue_date' => 'nullable|date',
            'hac_received_date' => 'nullable|date|after_or_equal:hac_issue_date',
            'approval_number' => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        // 4. إنشاء المشروع
        Project::create($validatedData);

        return redirect()->route('dashboard.projects.index')->with('success', 'تم إنشاء المشروع بنجاح.');
    }
    public function export()
    {
    return Excel::download(new ProjectsExport, 'projects_' . date('Y-m-d') . '.xlsx');
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['organization', 'projectType', 'mainStatus', 'generalStatus', 'handoverStatus', 'activities', 'projectContracts.contractor']);
        return view('dashboard.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $organizations = Organization::orderBy('name')->get();
        $projectTypes = ProjectType::all();
        $mainStatuses = ProjectMainStatus::all();
        $generalStatuses = ProjectGeneralStatus::all();
        $handoverStatuses = HandoverStatus::all();

        return view('dashboard.projects.edit', compact('project', 'organizations', 'projectTypes', 'mainStatuses', 'generalStatuses', 'handoverStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'donor_name' => 'nullable|string|max:255',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:50',
            'project_type_id' => 'required|exists:project_types,id',
            'main_status_id' => 'required|exists:project_main_statuses,id',
            'general_status_id' => 'required|exists:project_general_statuses,id',
            'handover_status_id' => 'required|exists:handover_statuses,id',
            'total_value' => 'nullable|numeric',
            'contract_date' => 'nullable|date',
            'total_duration_days' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hac_issue_number' => 'nullable|string|max:100',
            'hac_issue_date' => 'nullable|date',
            'hac_received_date' => 'nullable|date|after_or_equal:hac_issue_date',
            'approval_number' => 'nullable|string|max:100',
            'approval_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $project->update($request->except('project_code'));

        return redirect()->route('dashboard.projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('dashboard.projects.index')->with('success', 'تم حذف المشروع بنجاح.');
    }

    /**
     * Fetch related activities and contractors for a specific project.
     * Used for cascading dropdowns via AJAX.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRelatedData(Project $project)
    {
        // تحميل الأنشطة المتعلقة بهذا المشروع مع اسم النشاط الرئيسي
        $activities = $project->activities()->with('masterActivity')->get()->map(function ($activity) {
            return [
                'id' => $activity->id,
                'text' => ($activity->masterActivity->name ?? 'N/A') . ' (' . $activity->activity_code . ')',
            ];
        });

        // تحميل عقود المقاولين المتعلقة بهذا المشروع مع اسم المقاول
        $contractors = $project->projectContracts()->with('contractor')->get()->map(function ($contract) {
            return [
                'id' => $contract->id,
                'text' => ($contract->contractor->name ?? 'N/A') . ' (' . $contract->contract_code . ')',
            ];
        });

        return response()->json([
            'activities' => $activities,
            'contractors' => $contractors,
        ]);
    }

}
