<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\MasterActivity;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Station;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProjectActivityController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:project_activities.view')->only(['index', 'show']);
        $this->middleware('permission:project_activities.create')->only(['create', 'store']);
        $this->middleware('permission:project_activities.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_activities.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager load relationships for efficiency
        $query = ProjectActivity::with(['project', 'masterActivity', 'unit', 'station']);

        // Filter by Project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('activity_code', 'like', "%{$searchTerm}%")
                  ->orWhere('village_name', 'like', "%{$searchTerm}%");
        }

        $projectActivities = $query->latest()->paginate(30);
        $projects = Project::orderBy('name')->get(); // For the filter dropdown

        return view('dashboard.project-activities.index', compact('projectActivities', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch data needed for dropdowns in the create form
        $projects = Project::orderBy('name')->get();
        $masterActivities = MasterActivity::orderBy('name')->get();
        $units = Unit::orderBy('unit_name')->get();
        $stations = Station::orderBy('station_name')->get();

        return view('dashboard.project-activities.create', compact('projects', 'masterActivities', 'units', 'stations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'activity_code' => 'required|string|max:100|unique:project_activities,activity_code',
            'project_id' => 'required|exists:projects,id',
            'master_activity_id' => 'required|exists:master_activities,id',
            'unit_id' => 'required|exists:units,id',
            'station_id' => 'required|exists:stations,id',
            'village_name' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'unit_capacity' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        ProjectActivity::create($validatedData);

        return redirect()->route('dashboard.project-activities.index')
                         ->with('success', 'تم إنشاء نشاط المشروع بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\View\View
     */
    public function show(ProjectActivity $projectActivity)
    {
        $projectActivity->load(['project', 'masterActivity', 'unit', 'station', 'tasks.projectContractor.contractor']);
        return view('dashboard.project-activities.show', compact('projectActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\View\View
     */
    public function edit(ProjectActivity $projectActivity)
    {
        // Fetch data needed for dropdowns, same as create method
        $projects = Project::orderBy('name')->get();
        $masterActivities = MasterActivity::orderBy('name')->get();
        $units = Unit::orderBy('unit_name')->get();
        $stations = Station::orderBy('station_name')->get();

        return view('dashboard.project-activities.edit', compact('projectActivity', 'projects', 'masterActivities', 'units', 'stations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProjectActivity $projectActivity)
    {
        $validatedData = $request->validate([
            'activity_code' => 'required|string|max:100|unique:project_activities,activity_code,' . $projectActivity->id,
            'project_id' => 'required|exists:projects,id',
            'master_activity_id' => 'required|exists:master_activities,id',
            'unit_id' => 'required|exists:units,id',
            'station_id' => 'required|exists:stations,id',
            'village_name' => 'nullable|string|max:255',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectActivity  $projectActivity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectActivity $projectActivity)
    {
        // The database schema's 'onDelete('cascade')' for tasks should handle this.
        // However, an application-level check can provide a better user experience.
        if ($projectActivity->tasks()->exists()) {
            return redirect()->route('dashboard.project-activities.index')
                             ->with('error', 'لا يمكن حذف النشاط لوجود مهام مقاولين مرتبطة به.');
        }

        $projectActivity->delete();

        return redirect()->route('dashboard.project-activities.index')
                         ->with('success', 'تم حذف نشاط المشروع بنجاح.');
    }
}
