<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectContractorRequest;
use App\Models\Contractor;
use App\Models\ContractorStatus;
use App\Models\Project;
use App\Models\ProjectContractor;
use Illuminate\Http\Request;

class ProjectContractorController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:project_contractors.view')->only(['index', 'show']);
        $this->middleware('permission:project_contractors.create')->only(['create', 'store']);
        $this->middleware('permission:project_contractors.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_contractors.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ProjectContractor::with(['project', 'contractor', 'contractorStatus']);

        // Filter by Project or Contractor
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('contractor_id')) {
            $query->where('contractor_id', $request->contractor_id);
        }

        $projectContractors = $query->latest()->paginate(2500);
        $projects = Project::orderBy('name')->get(); // For filters
        $contractors = Contractor::orderBy('name')->get(); // For filters

        return view('dashboard.project-contractors.index', compact('projectContractors', 'projects', 'contractors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $contractors = Contractor::orderBy('name')->get();
        $contractorStatuses = ContractorStatus::all();

        return view('dashboard.project-contractors.create', compact('projects', 'contractors', 'contractorStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectContractorRequest  $request)
    {
        $validatedData = $request->validate([
            'contract_code' => 'required|string|max:100|unique:project_contractors,contract_code',
            'project_id' => 'required|exists:projects,id',
            'contractor_id' => 'nullable|exists:contractors,id',
            'contract_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'duration_days' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'contractor_status_id' => 'required|exists:contractor_statuses,id',
            'org_approval_number' => 'nullable|string|max:100',
            'org_approval_date' => 'nullable|date',
        ]);

        ProjectContractor::create($validatedData);

        return redirect()->route('dashboard.project-contractors.index')
                         ->with('success', 'تمت إضافة عقد المقاول بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectContractor  $projectContractor
     * @return \Illuminate\View\View
     */
    public function show(ProjectContractor $projectContractor)
    {
        $projectContractor->load(['project', 'contractor', 'contractorStatus', 'tasks.projectActivity']);
        return view('dashboard.project-contractors.show', compact('projectContractor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectContractor  $projectContractor
     * @return \Illuminate\View\View
     */
    public function edit(ProjectContractor $projectContractor)
    {
        $projects = Project::orderBy('name')->get();
        $contractors = Contractor::orderBy('name')->get();
        $contractorStatuses = ContractorStatus::all();

        return view('dashboard.project-contractors.edit', compact('projectContractor', 'projects', 'contractors', 'contractorStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectContractor  $projectContractor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProjectContractor $projectContractor)
    {
        $validatedData = $request->validate([
            'contract_code' => 'required|string|max:100|unique:project_contractors,contract_code,' . $projectContractor->id,
            'project_id' => 'required|exists:projects,id',
            'contractor_id' => 'nullable|exists:contractors,id',
            'contract_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'duration_days' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'contractor_status_id' => 'required|exists:contractor_statuses,id',
            'org_approval_number' => 'nullable|string|max:100',
            'org_approval_date' => 'nullable|date',
        ]);

        $projectContractor->update($validatedData);

        return redirect()->route('dashboard.project-contractors.index')
                         ->with('success', 'تم تحديث عقد المقاول بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectContractor  $projectContractor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectContractor $projectContractor)
    {
        if ($projectContractor->tasks()->exists()) {
            return redirect()->route('dashboard.project-contractors.index')
                             ->with('error', 'لا يمكن حذف العقد لوجود مهام مرتبطة به.');
        }

        $projectContractor->delete();

        return redirect()->route('dashboard.project-contractors.index')
                         ->with('success', 'تم حذف عقد المقاول بنجاح.');
    }
}
