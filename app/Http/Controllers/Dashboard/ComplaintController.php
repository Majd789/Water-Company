<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaints = Complaint::with(['town', 'complaintType'])->get();
        return view('dashboard.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $towns = Town::orderBy('town_name')->get();
        $complaintTypes = ComplaintType::orderBy('name')->get();
        
        return view('dashboard.complaints.create', compact('towns', 'complaintTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint_type_id' => 'required|exists:complaint_types,id',
            'town_id' => 'required|exists:towns,id',
            'complainant_name' => 'required|string|max:255',
            'building_code' => 'nullable|string|max:255',
            'details' => 'required|string',
            'location_type' => 'required|in:inside,outside',
            'is_repeated' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // image validation
            'status' => 'required|in:new,in_progress,resolved,closed',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // تخزين الصورة في storage/app/public/complaints
            $imagePath = $request->file('image')->store('complaints', 'public');
            $validated['image_path'] = $imagePath;
        }

        Complaint::create($validated);

        return redirect()->route('dashboard.complaints.index')
            ->with('success', 'تم تسجيل الشكوى بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
  
}
