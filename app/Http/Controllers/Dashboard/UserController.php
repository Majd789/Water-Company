<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Enum\UserStatusEnum;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = UserStatusEnum::cases();
        $roles = Role::pluck('name');
        return view('dashboard.users.create', compact('statuses', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'unit_id' => 'nullable|integer',
            'role' => 'required|string',
            'staion_id' => 'nullable|integer',
            'status' => 'nullable|in:' . implode(',', array_map(fn($e) => $e->value, UserStatusEnum::cases())),
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? UserStatusEnum::ACTIVE->value;
        $role = $validated['role'];
        unset($validated['role']);
        $user = User::create($validated);
        $user->assignRole($role);
        return redirect()->route('dashboard.users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $statuses = UserStatusEnum::cases();
        $roles = \Spatie\Permission\Models\Role::pluck('name');
        return view('dashboard.users.edit', compact('user', 'statuses', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'unit_id' => 'nullable|integer',
            'role' => 'required|string',
            'staion_id' => 'nullable|integer',
            'status' => 'nullable|in:' . implode(',', array_map(fn($e) => $e->value, UserStatusEnum::cases())),
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['status'] = $validated['status'] ?? UserStatusEnum::ACTIVE->value;
        $role = $validated['role'];
        unset($validated['role']);
        $user->update($validated);
        $user->syncRoles([$role]);
        return redirect()->route('dashboard.users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('dashboard.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
