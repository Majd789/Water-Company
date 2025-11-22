<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    /**
     * Constructor to apply middleware for permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:contractors.view')->only(['index', 'show']);
        $this->middleware('permission:contractors.create')->only(['create', 'store']);
        $this->middleware('permission:contractors.edit')->only(['edit', 'update']);
        $this->middleware('permission:contractors.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Contractor::query();

        // Apply search filter if present
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('phone_number', 'like', "%{$searchTerm}%");
            });
        }

        $contractors = $query->orderBy('name')->paginate(1000);

        return view('dashboard.contractors.index', compact('contractors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        return view('dashboard.contractors.create');
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:contractors,code',
            'phone_number' => 'nullable|string|max:50',
        ]);

        Contractor::create($validatedData);

        return redirect()->route('dashboard.contractors.index')
                         ->with('success', 'تم إنشاء المقاول بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contractor  $contractor
     * @return \Illuminate\View\View
     */
    public function show(Contractor $contractor)
    {
        // You might want to load related project contracts
        $contractor->load('projectContracts.project');

        return view('dashboard.contractors.show', compact('contractor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contractor  $contractor
     * @return \Illuminate\View\View
     */
    public function edit(Contractor $contractor)
    {
        return view('dashboard.contractors.edit', compact('contractor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contractor  $contractor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contractor $contractor)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:contractors,code,' . $contractor->id,
            'phone_number' => 'nullable|string|max:50',
        ]);

        $contractor->update($validatedData);

        return redirect()->route('dashboard.contractors.index')
                         ->with('success', 'تم تحديث بيانات المقاول بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contractor  $contractor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contractor $contractor)
    {
        // Safety check to prevent deleting a contractor with active contracts
        if ($contractor->projectContracts()->exists()) {
            return redirect()->route('dashboard.contractors.index')
                             ->with('error', 'لا يمكن حذف المقاول لوجود عقود مرتبطة به.');
        }

        $contractor->delete();

        return redirect()->route('dashboard.contractors.index')
                         ->with('success', 'تم حذف المقاول بنجاح.');
    }
}
