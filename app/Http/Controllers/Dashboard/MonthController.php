<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Month;
use Illuminate\Http\Request;

class MonthController extends Controller
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
        $query = Month::query();

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('name_ar', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
        }

        // It's logical to always order months by their number
        $months = $query->orderBy('month_number')->paginate(12);

        return view('dashboard.months.index', compact('months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.months.create');
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
            'month_number' => 'required|integer|min:1|max:12|unique:months,month_number',
            'name_ar' => 'required|string|max:50|unique:months,name_ar',
            'code' => 'required|string|max:10|unique:months,code',
        ]);

        Month::create($validatedData);

        return redirect()->route('dashboard.months.index')
                         ->with('success', 'تم إنشاء الشهر بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Month  $month
     * @return \Illuminate\View\View
     */
    public function show(Month $month)
    {
        return view('dashboard.months.show', compact('month'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Month  $month
     * @return \Illuminate\View\View
     */
    public function edit(Month $month)
    {
        return view('dashboard.months.edit', compact('month'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Month  $month
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Month $month)
    {
        $validatedData = $request->validate([
            'month_number' => 'required|integer|min:1|max:12|unique:months,month_number,' . $month->id,
            'name_ar' => 'required|string|max:50|unique:months,name_ar,' . $month->id,
            'code' => 'required|string|max:10|unique:months,code,' . $month->id,
        ]);

        $month->update($validatedData);

        return redirect()->route('dashboard.months.index')
                         ->with('success', 'تم تحديث الشهر بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Month  $month
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Month $month)
    {
        // Deleting a month is an unusual operation, but the functionality is here if needed.
        $month->delete();

        return redirect()->route('dashboard.months.index')
                         ->with('success', 'تم حذف الشهر بنجاح.');
    }
}
