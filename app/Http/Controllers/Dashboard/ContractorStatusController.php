<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContractorStatus;
use Illuminate\Http\Request;

class ContractorStatusController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        // Adjust permission names as needed for your system
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
        $query = ContractorStatus::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        }

        $contractorStatuses = $query->paginate(20);

        return view('dashboard.contractor-statuses.index', compact('contractorStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.contractor-statuses.create');
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
            'name' => 'required|string|max:255|unique:contractor_statuses,name',
        ]);

        ContractorStatus::create($validatedData);

        return redirect()->route('dashboard.contractor-statuses.index')
                         ->with('success', 'تم إنشاء حالة المقاول بنجاح.');
    }

    /**
     * Display the specified resource.
     * This method might not be necessary for simple lookup tables.
     *
     * @param  \App\Models\ContractorStatus  $contractorStatus
     * @return \Illuminate\View\View
     */
    public function show(ContractorStatus $contractorStatus)
    {
        return view('dashboard.contractor-statuses.show', compact('contractorStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContractorStatus  $contractorStatus
     * @return \Illuminate\View\View
     */
    public function edit(ContractorStatus $contractorStatus)
    {
        return view('dashboard.contractor-statuses.edit', compact('contractorStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractorStatus  $contractorStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ContractorStatus $contractorStatus)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:contractor_statuses,name,' . $contractorStatus->id,
        ]);

        $contractorStatus->update($validatedData);

        return redirect()->route('dashboard.contractor-statuses.index')
                         ->with('success', 'تم تحديث حالة المقاول بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContractorStatus  $contractorStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ContractorStatus $contractorStatus)
    {
        // Note: You should add a check here to see if this status is being used
        // by any project_contractors before allowing deletion.
        // For simplicity, this is omitted, but it's crucial for data integrity.

        $contractorStatus->delete();

        return redirect()->route('dashboard.contractor-statuses.index')
                         ->with('success', 'تم حذف حالة المقاول بنجاح.');
    }
}
