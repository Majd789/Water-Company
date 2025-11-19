<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MasterActivity;
use Illuminate\Http\Request;

class MasterActivityController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:master_activities.view')->only(['index', 'show']);
        $this->middleware('permission:master_activities.create')->only(['create', 'store']);
        $this->middleware('permission:master_activities.edit')->only(['edit', 'update']);
        $this->middleware('permission:master_activities.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MasterActivity::query();

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('unit', 'like', "%{$searchTerm}%");
            });
        }

        $masterActivities = $query->orderBy('name')->paginate(50);

        return view('dashboard.master-activities.index', compact('masterActivities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.master-activities.create');
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
            'name' => 'required|string|max:255|unique:master_activities,name',
            'code' => 'required|string|max:50|unique:master_activities,code',
            'unit' => 'required|string|max:50',
        ]);

        MasterActivity::create($validatedData);

        return redirect()->route('dashboard.master-activities.index')
                         ->with('success', 'تم إنشاء النشاط الرئيسي بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterActivity  $masterActivity
     * @return \Illuminate\View\View
     */
    public function show(MasterActivity $masterActivity)
    {
        return view('dashboard.master-activities.show', compact('masterActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterActivity  $masterActivity
     * @return \Illuminate\View\View
     */
    public function edit(MasterActivity $masterActivity)
    {
        return view('dashboard.master-activities.edit', compact('masterActivity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterActivity  $masterActivity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MasterActivity $masterActivity)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:master_activities,name,' . $masterActivity->id,
            'code' => 'required|string|max:50|unique:master_activities,code,' . $masterActivity->id,
            'unit' => 'required|string|max:50',
        ]);

        $masterActivity->update($validatedData);

        return redirect()->route('dashboard.master-activities.index')
                         ->with('success', 'تم تحديث النشاط الرئيسي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterActivity  $masterActivity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MasterActivity $masterActivity)
    {
        // Safety check: Before deleting, ensure this activity is not used in any project.
        // This requires a relationship 'projectActivities' to be defined in the MasterActivity model.
        // public function projectActivities() { return $this->hasMany(ProjectActivity::class); }

        // if ($masterActivity->projectActivities()->exists()) {
        //     return redirect()->route('dashboard.master-activities.index')
        //                      ->with('error', 'لا يمكن حذف هذا النشاط لارتباطه بأنشطة مشاريع فعلية.');
        // }

        $masterActivity->delete();

        return redirect()->route('dashboard.master-activities.index')
                         ->with('success', 'تم حذف النشاط الرئيسي بنجاح.');
    }
}
