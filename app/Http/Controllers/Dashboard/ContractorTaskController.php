<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContractorTask;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\ProjectContractor;
use Illuminate\Http\Request;

class ContractorTaskController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:contractor_tasks.view')->only(['index', 'show']);
        $this->middleware('permission:contractor_tasks.create')->only(['create', 'store']);
        $this->middleware('permission:contractor_tasks.edit')->only(['edit', 'update']);
        $this->middleware('permission:contractor_tasks.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager load relationships to prevent N+1 query issues
        $query = ContractorTask::with([
            'projectActivity.project',
            'projectContractor.contractor'
        ]);

        // Add search functionality if needed
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('task_code', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        $contractorTasks = $query->latest()->paginate(30);

        return view('dashboard.contractor-tasks.index', compact('contractorTasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch necessary data for dropdowns
        $projectActivities = ProjectActivity::with('project')->get();
        $projectContractors = ProjectContractor::with('contractor', 'project')->get();
         $projects = Project::orderBy('name')->get();
        return view('dashboard.contractor-tasks.create', compact('projectActivities', 'projectContractors', 'projects'));
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
            'task_code' => 'required|string|max:100|unique:contractor_tasks,task_code',
            'project_activity_id' => 'required|exists:project_activities,id',
            'project_contractor_id' => 'required|exists:project_contractors,id',
            'description' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'is_discrepant' => 'nullable|boolean',
           'discrepancy_notes' => 'required_if:is_discrepant,1|nullable|string',
        ]);
        $validatedData['is_discrepant'] = $request->has('is_discrepant');
        ContractorTask::create($validatedData);

        return redirect()->route('dashboard.contractor-tasks.index')
                         ->with('success', 'تم إنشاء مهمة المقاول بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContractorTask  $contractorTask
     * @return \Illuminate\View\View
     */
    public function show(ContractorTask $contractorTask)
    {
        // Load all related data for the detailed view
        $contractorTask->load(['projectActivity.project', 'projectContractor.contractor', 'projectContractor.project']);

        return view('dashboard.contractor-tasks.show', compact('contractorTask'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContractorTask  $contractorTask
     * @return \Illuminate\View\View
     */
    public function edit(ContractorTask $contractorTask)
    {
        // Fetch necessary data for dropdowns, same as create method
        $projectActivities = ProjectActivity::with('project')->get();
        $projectContractors = ProjectContractor::with('contractor', 'project')->get();

        return view('dashboard.contractor-tasks.edit', compact('contractorTask', 'projectActivities', 'projectContractors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractorTask  $contractorTask
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ContractorTask $contractorTask)
    {
        $validatedData = $request->validate([
            'task_code' => 'required|string|max:100|unique:contractor_tasks,task_code,' . $contractorTask->id,
            'project_activity_id' => 'required|exists:project_activities,id',
            'project_contractor_id' => 'required|exists:project_contractors,id',
            'description' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'unit_measure' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'is_discrepant' => 'nullable|boolean',
              'discrepancy_notes' => 'required_if:is_discrepant,1|nullable|string',
        ]);

        $validatedData['is_discrepant'] = $request->has('is_discrepant');

        $contractorTask->update($validatedData);

        return redirect()->route('dashboard.contractor-tasks.index')
                         ->with('success', 'تم تحديث مهمة المقاول بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContractorTask  $contractorTask
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ContractorTask $contractorTask)
    {
        $contractorTask->delete();

        return redirect()->route('dashboard.contractor-tasks.index')
                         ->with('success', 'تم حذف مهمة المقاول بنجاح.');
    }
}
