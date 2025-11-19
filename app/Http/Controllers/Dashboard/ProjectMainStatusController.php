<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectMainStatus;
use Illuminate\Http\Request;

class ProjectMainStatusController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:project_main_statuses.view')->only(['index', 'show']);
        $this->middleware('permission:project_main_statuses.create')->only(['create', 'store']);
        $this->middleware('permission:project_main_statuses.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_main_statuses.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ProjectMainStatus::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $projectMainStatuses = $query->paginate(20);

        return view('dashboard.project-main-statuses.index', compact('projectMainStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.project-main-statuses.create');
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
            'name' => 'required|string|max:255|unique:project_main_statuses,name',
        ]);

        ProjectMainStatus::create($validatedData);

        return redirect()->route('dashboard.project-main-statuses.index')
                         ->with('success', 'تم إنشاء حالة المشروع الرئيسية بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectMainStatus  $projectMainStatus
     * @return \Illuminate\View\View
     */
    public function show(ProjectMainStatus $projectMainStatus)
    {
        return view('dashboard.project-main-statuses.show', compact('projectMainStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectMainStatus  $projectMainStatus
     * @return \Illuminate\View\View
     */
    public function edit(ProjectMainStatus $projectMainStatus)
    {
        return view('dashboard.project-main-statuses.edit', compact('projectMainStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectMainStatus  $projectMainStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProjectMainStatus $projectMainStatus)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:project_main_statuses,name,' . $projectMainStatus->id,
        ]);

        $projectMainStatus->update($validatedData);

        return redirect()->route('dashboard.project-main-statuses.index')
                         ->with('success', 'تم تحديث حالة المشروع الرئيسية بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectMainStatus  $projectMainStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectMainStatus $projectMainStatus)
    {
        // Add a 'projects' relationship to the ProjectMainStatus model to enable this check
        // if ($projectMainStatus->projects()->exists()) {
        //     return redirect()->route('dashboard.project-main-statuses.index')
        //                      ->with('error', 'لا يمكن حذف هذه الحالة لوجود مشاريع مرتبطة بها.');
        // }

        $projectMainStatus->delete();

        return redirect()->route('dashboard.project-main-statuses.index')
                         ->with('success', 'تم حذف حالة المشروع الرئيسية بنجاح.');
    }
}
