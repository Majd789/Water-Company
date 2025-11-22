<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:organizations.view')->only(['index', 'show']);
        $this->middleware('permission:organizations.create')->only(['create', 'store']);
        $this->middleware('permission:organizations.edit')->only(['edit', 'update']);
        $this->middleware('permission:organizations.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Organization::query();

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
        }

        $organizations = $query->orderBy('name')->paginate(1000);
        return view('dashboard.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('dashboard.organizations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:organizations,code',
        ]);

        Organization::create($request->all());
        return redirect()->route('dashboard.organizations.index')->with('success', 'تم إنشاء المنظمة بنجاح.');
    }

    public function show(Organization $organization)
    {
        return view('dashboard.organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        return view('dashboard.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:organizations,code,' . $organization->id,
        ]);

        $organization->update($request->all());
        return redirect()->route('dashboard.organizations.index')->with('success', 'تم تحديث المنظمة بنجاح.');
    }

    public function destroy(Organization $organization)
    {
        // يفضل إضافة تحقق هنا لمنع حذف منظمة مرتبطة بمشاريع
        if ($organization->projects()->exists()) {
            return redirect()->route('dashboard.organizations.index')->with('error', 'لا يمكن حذف المنظمة لوجود مشاريع مرتبطة بها.');
        }

        $organization->delete();
        return redirect()->route('dashboard.organizations.index')->with('success', 'تم حذف المنظمة بنجاح.');
    }
}
