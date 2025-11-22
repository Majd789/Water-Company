<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        // You can use specific permissions or a general one for lookup data
        $this->middleware('permission:project_types.view')->only(['index', 'show']);
        $this->middleware('permission:project_types.create')->only(['create', 'store']);
        $this->middleware('permission:project_types.edit')->only(['edit', 'update']);
        $this->middleware('permission:project_types.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ProjectType::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $projectTypes = $query->paginate(1000);

        return view('dashboard.project-types.index', compact('projectTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.project-types.create');
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
            'name' => 'required|string|max:255|unique:project_types,name',
        ]);

        ProjectType::create($validatedData);

        return redirect()->route('dashboard.project-types.index')
                         ->with('success', 'تم إنشاء نوع المشروع بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\View\View
     */
    public function show(ProjectType $projectType)
    {
        return view('dashboard.project-types.show', compact('projectType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\View\View
     */
    public function edit(ProjectType $projectType)
    {
        return view('dashboard.project-types.edit', compact('projectType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProjectType $projectType)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:project_types,name,' . $projectType->id,
        ]);

        $projectType->update($validatedData);

        return redirect()->route('dashboard.project-types.index')
                         ->with('success', 'تم تحديث نوع المشروع بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectType $projectType)
    {
        // Add a 'projects' relationship to the ProjectType model to enable this check
        // if ($projectType->projects()->exists()) {
        //     return redirect()->route('dashboard.project-types.index')
        //                      ->with('error', 'لا يمكن حذف هذا النوع لوجود مشاريع مرتبطة به.');
        // }

        $projectType->delete();

        return redirect()->route('dashboard.project-types.index')
                         ->with('success', 'تم حذف نوع المشروع بنجاح.');
    }
}
