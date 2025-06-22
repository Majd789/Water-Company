<?php

namespace App\Http\Controllers;

use App\Exports\WeeklyReportsExport;
use App\Models\Unit;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class WeeklyReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = WeeklyReport::with('unit');

        if (!is_null($user->unit_id)) {
            $query->where('unit_id', $user->unit_id);
        }

        if (is_null($user->unit_id) && $request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sender_name', 'like', "%{$search}%")
                  ->orWhere('operational_status', 'like', "%{$search}%")
                  ->orWhere('maintenance_entity', 'like', "%{$search}%")
                  ->orWhere('administrative_works', 'like', "%{$search}%");
            });
        }

        if ($request->filled('report_date')) {
            $query->whereDate('report_date', $request->report_date);
        }

        $reports = $query->orderByDesc('report_date')
                         ->paginate(20)
                         ->appends($request->query());

        $units = is_null($user->unit_id)
            ? Unit::all()
            : Unit::where('id', $user->unit_id)->get();

        return view('weekly_reports.index', compact('reports', 'units'));
    }

    public function export()
    {
        return Excel::download(new WeeklyReportsExport, 'weekly_reports.xlsx');
    }

    public function show($id)
    {
        $report = WeeklyReport::with('unit')->findOrFail($id);
        return view('weekly_reports.show', compact('report'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('weekly_reports.create', compact('units'));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'unit_id'               => 'required|exists:units,id',
        'report_date'           => 'required|date',
        'sender_name'           => 'nullable|string|max:255',
        'operational_status'    => 'required|string',
        'stop_reason'           => 'nullable|string',
        'maintenance_works'     => 'nullable|string',
        'maintenance_entity'    => 'nullable|string|max:255',
        'maintenance_image'     => 'nullable|image|max:2048',
        'administrative_works'  => 'nullable|string',
        'administrative_image'  => 'nullable|image|max:2048',
        'additional_notes'      => 'nullable|string',
    ]);

    if ($request->hasFile('maintenance_image')) {
        $validated['maintenance_image'] = $request->file('maintenance_image')->store('maintenance_images', 'public');
    }

    if ($request->hasFile('administrative_image')) {
        $validated['administrative_image'] = $request->file('administrative_image')->store('administrative_images', 'public');
    }

    WeeklyReport::create($validated);

    return redirect()
        ->route('weekly_reports.index')
        ->with('success', 'تم إنشاء التقرير بنجاح');
}

    

    public function edit($id)
    {
        $report = WeeklyReport::findOrFail($id);
        $units = Unit::all();
        return view('weekly_reports.edit', compact('report', 'units'));
    }

   public function update(Request $request, $id)
{
    $report = WeeklyReport::findOrFail($id);

    $validated = $request->validate([
        'unit_id'               => 'required|exists:units,id',
        'report_date'           => 'required|date',
        'sender_name'           => 'nullable|string|max:255',
        'operational_status'    => 'required|string',
        'stop_reason'           => 'nullable|string',
        'maintenance_works'     => 'nullable|string',
        'maintenance_entity'    => 'nullable|string|max:255',
        'maintenance_image'     => 'nullable|image|max:2048',
        'administrative_works'  => 'nullable|string',
        'administrative_image'  => 'nullable|image|max:2048',
        'additional_notes'      => 'nullable|string',
    ]);

    if ($request->hasFile('maintenance_image')) {
        if ($report->maintenance_image && Storage::disk('public')->exists($report->maintenance_image)) {
            Storage::disk('public')->delete($report->maintenance_image);
        }
        $validated['maintenance_image'] = $request->file('maintenance_image')->store('maintenance_images', 'public');
    }

    if ($request->hasFile('administrative_image')) {
        if ($report->administrative_image && Storage::disk('public')->exists($report->administrative_image)) {
            Storage::disk('public')->delete($report->administrative_image);
        }
        $validated['administrative_image'] = $request->file('administrative_image')->store('administrative_images', 'public');
    }

    $report->update($validated);

    return redirect()
        ->route('weekly_reports.index')
        ->with('success', 'تم تحديث التقرير بنجاح');
}

    

   public function destroy($id)
{
    $report = WeeklyReport::findOrFail($id);

    if ($report->maintenance_image && Storage::disk('public')->exists($report->maintenance_image)) {
        Storage::disk('public')->delete($report->maintenance_image);
    }

    if ($report->administrative_image && Storage::disk('public')->exists($report->administrative_image)) {
        Storage::disk('public')->delete($report->administrative_image);
    }

    $report->delete();

    return redirect()
        ->route('weekly_reports.index')
        ->with('success', 'تم حذف التقرير بنجاح');
}


    public function news(Request $request)
    {
        $query = WeeklyReport::with('unit');

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sender_name', 'like', "%{$search}%")
                  ->orWhere('operational_status', 'like', "%{$search}%")
                  ->orWhere('maintenance_entity', 'like', "%{$search}%")
                  ->orWhere('administrative_works', 'like', "%{$search}%");
            });
        }

        if ($request->filled('report_date')) {
            $query->whereDate('report_date', $request->report_date);
        }

        $reports = $query->orderByDesc('report_date')
                         ->paginate(10)
                         ->appends($request->query());

        $units = Unit::all();

        return view('weekly_reports.news', compact('reports', 'units'));
    }
}
