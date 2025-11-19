<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HandoverStatus;
use Illuminate\Http\Request;

class HandoverStatusController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        // Using a general permission for simple lookup data management
        $this->middleware('permission:lookup_data.view')->only(['index', 'show']);
        $this->middleware('permission:lookup_data.create')->only(['create', 'store']);
        $this->middleware('permission:lookup_data.edit')->only(['edit', 'update']);
        $this->middleware('permission:lookup_data.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = HandoverStatus::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $handoverStatuses = $query->paginate(20);

        return view('dashboard.handover-statuses.index', compact('handoverStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.handover-statuses.create');
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
            'name' => 'required|string|max:255|unique:handover_statuses,name',
        ]);

        HandoverStatus::create($validatedData);

        return redirect()->route('dashboard.handover-statuses.index')
                         ->with('success', 'تم إنشاء حالة التسليم بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HandoverStatus  $handoverStatus
     * @return \Illuminate\View\View
     */
    public function show(HandoverStatus $handoverStatus)
    {
        return view('dashboard.handover-statuses.show', compact('handoverStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HandoverStatus  $handoverStatus
     * @return \Illuminate\View\View
     */
    public function edit(HandoverStatus $handoverStatus)
    {
        return view('dashboard.handover-statuses.edit', compact('handoverStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HandoverStatus  $handoverStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, HandoverStatus $handoverStatus)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:handover_statuses,name,' . $handoverStatus->id,
        ]);

        $handoverStatus->update($validatedData);

        return redirect()->route('dashboard.handover-statuses.index')
                         ->with('success', 'تم تحديث حالة التسليم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HandoverStatus  $handoverStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(HandoverStatus $handoverStatus)
    {
        // Safety check to prevent deleting a status that is currently in use by projects
        if ($handoverStatus->projects()->exists()) {
            return redirect()->route('dashboard.handover-statuses.index')
                             ->with('error', 'لا يمكن حذف هذه الحالة لوجود مشاريع مرتبطة بها.');
        }

        $handoverStatus->delete();

        return redirect()->route('dashboard.handover-statuses.index')
                         ->with('success', 'تم حذف حالة التسليم بنجاح.');
    }
}
