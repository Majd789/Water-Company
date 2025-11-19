<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectGeneralStatus;
use Illuminate\Http\Request;

class ProjectGeneralStatusController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:project_general_statuses.view')->only(['index', 'show']);
        $this->middleware('permission:project_general_statuses.create')->only(['create', 'store']);
        $this->middleware('permission:project_general_statuses.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_general_statuses.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ProjectGeneralStatus::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $projectGeneralStatuses = $query->paginate(20);

        return view('dashboard.project-general-statuses.index', compact('projectGeneralStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.project-general-statuses.create');
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
            'name' => 'required|string|max:255|unique:project_general_statuses,name',
        ]);

        ProjectGeneralStatus::create($validatedData);

        return redirect()->route('dashboard.project-general-statuses.index')
                         ->with('success', 'تم إنشاء حالة المشروع العامة بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectGeneralStatus  $projectGeneralStatus
     * @return \Illuminate\View\View
     */
    public function show(ProjectGeneralStatus $projectGeneralStatus)
    {
        return view('dashboard.project-general-statuses.show', compact('projectGeneralStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectGeneralStatus  $projectGeneralStatus
     * @return \Illuminate\View\View
     */
    public function edit(ProjectGeneralStatus $projectGeneralStatus)
    {
        return view('dashboard.project-general-statuses.edit', compact('projectGeneralStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectGeneralStatus  $projectGeneralStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProjectGeneralStatus $projectGeneralStatus)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:project_general_statuses,name,' . $projectGeneralStatus->id,
        ]);

        $projectGeneralStatus->update($validatedData);

        return redirect()->route('dashboard.project-general-statuses.index')
                         ->with('success', 'تم تحديث حالة المشروع العامة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectGeneralStatus  $projectGeneralStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectGeneralStatus $projectGeneralStatus)
    {
        // To enable this safety check, you must add the 'projects' relationship
        // to the ProjectGeneralStatus model:
        // public function projects() { return $this->hasMany(Project::class, 'general_status_id'); }

        // if ($projectGeneralStatus->projects()->exists()) {
        //     return redirect()->route('dashboard.project-general-statuses.index')
        //                      ->with('error', 'لا يمكن حذف هذه الحالة لوجود مشاريع مرتبطة بها.');
        // }

        $projectGeneralStatus->delete();

        return redirect()->route('dashboard.project-general-statuses.index')
                         ->with('success', 'تم حذف حالة المشروع العامة بنجاح.');
    }
}
