<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        // استخدام نفس نمط الصلاحيات الموجود في ملفاتك
        $this->middleware('permission:leave_types.view')->only(['index', 'show']);
        $this->middleware('permission:leave_types.create')->only(['create', 'store']);
        $this->middleware('permission:leave_types.edit')->only(['edit', 'update']);
        $this->middleware('permission:leave_types.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = LeaveType::query();

        // البحث باسم نوع الإجازة
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where('type_name', 'like', '%' . $searchTerm . '%');
        }

        $leaveTypes = $query->paginate(50);

        return view('dashboard.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('dashboard.leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255|unique:leave_types,type_name',
            'affects_balance' => 'required|boolean', // 1 للخصم من الرصيد، 0 لعدم الخصم
        ]);

        LeaveType::create($request->all());

        return redirect()->route('dashboard.leave-types.index')
            ->with('success', 'تم إضافة نوع الإجازة بنجاح');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('dashboard.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'type_name' => 'required|string|max:255|unique:leave_types,type_name,' . $leaveType->id,
            'affects_balance' => 'required|boolean',
        ]);

        $leaveType->update($request->all());

        return redirect()->route('dashboard.leave-types.index')
            ->with('success', 'تم تحديث بيانات نوع الإجازة بنجاح');
    }

    public function destroy(LeaveType $leaveType)
    {
        // التحقق من عدم وجود إجازات مرتبطة بهذا النوع قبل الحذف
        if ($leaveType->leaves()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف هذا النوع لوجود إجازات مسجلة به');
        }

        $leaveType->delete();

        return redirect()->route('dashboard.leave-types.index')
            ->with('success', 'تم حذف نوع الإجازة بنجاح');
    }
}
