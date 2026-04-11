<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\LeaveExport;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeavesExport; // تأكد من إنشاء هذا الملف لاحقاً
use App\Imports\LeavesImport; // تأكد من إنشاء هذا الملف لاحقاً

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leaves.view')->only(['index', 'show']);
        $this->middleware('permission:leaves.create')->only(['create', 'store']);
        $this->middleware('permission:leaves.delete')->only('destroy');
    }

    public function index(Request $request)
{
    $userUnitId = auth()->user()->unit_id;

    // جلب الوحدات المتاحة للفلترة (لوحدته فقط أو الجميع للمدير)
    $units = Unit::when($userUnitId, function($q) use ($userUnitId) {
        return $q->where('id', $userUnitId);
    })->get();

    $query = Leave::with(['employee.unit', 'leaveType', 'creator']);

    // تقييد البحث بناءً على الوحدة
    $selectedUnitId = $request->unit_id ?? $userUnitId;
    if (!empty($selectedUnitId)) {
        $query->whereHas('employee', function ($q) use ($selectedUnitId) {
            $q->where('unit_id', $selectedUnitId);
        });
    }

    if ($request->filled('search')) {
        $searchTerm = trim($request->search);
        $query->whereHas('employee', function ($q) use ($searchTerm) {
            $q->where('full_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('employee_code', 'like', '%' . $searchTerm . '%');
        });
    }

    $leaves = $query->latest()->paginate(100);
    return view('dashboard.leaves.index', compact('leaves', 'units'));
}

    public function export()
    {
        return Excel::download(new LeavesExport, 'leaves_report.xlsx');
    }



   public function create()
{
    $userUnitId = auth()->user()->unit_id;

    $employees = Employee::when($userUnitId, function($q) use ($userUnitId){
            return $q->where('unit_id', $userUnitId);
        })->where('is_active', true)->get();

    $leaveTypes = LeaveType::all();

    return view('dashboard.leaves.create', compact('employees', 'leaveTypes'));
}

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'nullable|string',
        ]);

        // حساب المدة
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $duration = $start->diffInDays($end) + 1;

        $employee = Employee::findOrFail($request->employee_id);
        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        // التحقق من الرصيد فقط إذا كان نوع الإجازة يؤثر على الرصيد
        if ($leaveType->affects_balance && $employee->remaining_days < $duration) {
            return back()->with('error', "رصيد الموظف لا يكفي. المتبقي: {$employee->remaining_days} يوم، والمدة المطلوبة: {$duration} يوم.");
        }

        DB::transaction(function () use ($request, $duration, $employee, $leaveType) {
            // تسجيل الإجازة
            Leave::create([
                'employee_id'   => $request->employee_id,
                'leave_type_id' => $request->leave_type_id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'duration'      => $duration,
                'reason'        => $request->reason,
                'created_by'    => auth()->id(),
            ]);

            // خصم الرصيد فقط إذا كان النوع يؤثر على الرصيد
            if ($leaveType->affects_balance) {
                $employee->decrement('remaining_days', $duration);
            }
        });

        return redirect()->route('dashboard.leaves.index')->with('success', 'تم تسجيل الإجازة بنجاح وتحديث الرصيد إن لزم الأمر.');
    }

    public function edit(Leave $leave)
{
    $leave->load(['employee', 'type']);
    $leaveTypes = LeaveType::all();

    // جلب الموظفين المتاحين للتعديل (حسب وحدة المستخدم أو الجميع)
    $userUnitId = auth()->user()->unit_id;
    $employees = Employee::when($userUnitId, function($q) use ($userUnitId){
            return $q->where('unit_id', $userUnitId);
        })->where('is_active', true)->get();

    return view('dashboard.leaves.edit', compact('leave', 'employees', 'leaveTypes'));
}
public function update(Request $request, Leave $leave)
{
    $request->validate([
        'employee_id'   => 'required|exists:employees,id',
        'leave_type_id' => 'required|exists:leave_types,id',
        'start_date'    => 'required|date',
        'end_date'      => 'required|date|after_or_equal:start_date',
        'reason'        => 'nullable|string',
        'attachment'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // 1. حساب المدة الجديدة
    $start = Carbon::parse($request->start_date);
    $end = Carbon::parse($request->end_date);
    $newDuration = $start->diffInDays($end) + 1;

    $employee = Employee::findOrFail($request->employee_id);
    $leaveType = LeaveType::findOrFail($request->leave_type_id);

    // 2. التحقق من الرصيد (مع مراعاة المدة المحجوزة مسبقاً لهذه الإجازة)
    if ($leaveType->affects_balance) {
        // الرصيد الفعلي المتاح حالياً + المدة القديمة التي كانت مخصومة
        $availableBalance = $employee->remaining_days + $leave->duration;

        if ($availableBalance < $newDuration) {
            return back()->with('error', "الرصيد لا يكفي بعد التعديل. المتاح: {$availableBalance} يوم، والمطلوب: {$newDuration} يوم.");
        }
    }

    DB::transaction(function () use ($request, $leave, $newDuration, $employee, $leaveType) {

        // 3. معالجة الرصيد (إعادة القديم ثم خصم الجديد)
        if ($leave->type->affects_balance) {
            $leave->employee->increment('remaining_days', $leave->duration);
        }

        // 4. معالجة الملف المرفق
        $attachmentPath = $leave->attachment_path;
        if ($request->hasFile('attachment')) {
            // حذف الملف القديم من السيرفر إذا أردت توفير مساحة
            // if ($attachmentPath) { \Storage::disk('public')->delete($attachmentPath); }
            $attachmentPath = $request->file('attachment')->store('leaves/attachments', 'public');
        }

        // 5. تحديث بيانات الإجازة
        $leave->update([
            'employee_id'   => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'duration'      => $newDuration,
            'reason'        => $request->reason,
            'attachment_path' => $attachmentPath, // تأكد من وجود العمود في قاعدة البيانات
        ]);

        // 6. خصم الرصيد الجديد إذا كان النوع يؤثر
        if ($leaveType->affects_balance) {
            $employee->decrement('remaining_days', $newDuration);
        }
    });

    return redirect()->route('dashboard.leaves.index')->with('success', 'تم تحديث بيانات الإجازة وتعديل الرصيد بنجاح.');
}
    public function show(Leave $leave)
    {
        $leave->load(['employee.unit', 'type', 'creator']);
        return view('dashboard.leaves.show', compact('leave'));
    }

    public function exportExcel($id)
{
    $leave = Leave::with(['employee', 'leaveType'])->findOrFail($id);
    $fileName = 'Leave_' . $leave->employee->full_name . '_' . date('Y-m-d') . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(new LeaveExport($leave), $fileName);
}
 public function destroy($id)
{
    // 1. جلب الإجازة يدوياً بالـ ID للتأكد من وجودها
    $leave = Leave::with('employee')->find($id);

    if (!$leave) {
        return redirect()->back()->with('error', 'الإجازة غير موجودة أصلاً.');
    }

    try {
        DB::beginTransaction();

        // 2. تحديث رصيد الموظف
        if ($leave->employee) {
            // استخدام update مباشرة للتأكد من الكتابة في قاعدة البيانات
            $newBalance = $leave->employee->remaining_days + $leave->duration;
            $leave->employee->update([
                'remaining_days' => $newBalance
            ]);
        }

        // 3. الحذف الفعلي
        $leave->delete();

        DB::commit();
        return redirect()->route('dashboard.leaves.index')->with('success', 'تم الحذف وتحديث الرصيد بنجاح.');

    } catch (\Exception $e) {
        DB::rollBack();
        // إظهار الخطأ الحقيقي إذا وجد
        return redirect()->back()->with('error', 'فشلت العملية: ' . $e->getMessage());
    }
}
}
