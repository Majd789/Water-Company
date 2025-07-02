<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.view')->only('index', 'show');
        $this->middleware('permission:roles.create')->only('create', 'store');
        $this->middleware('permission:roles.edit')->only('edit', 'update');
        $this->middleware('permission:roles.delete')->only('destroy');
    }
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($request->only('name', 'display_name', 'description'));
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('group');
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('roles')->ignore($role->id)],
            'display_name' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update($request->only('name', 'display_name', 'description'));
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['admin', 'super-admin'])) {
            return redirect()->route('roles.index')->with('error', 'لا يمكن حذف هذا الدور.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح');
    }

}
