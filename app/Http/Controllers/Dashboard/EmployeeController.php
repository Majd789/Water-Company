<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\EmployeesExport;
use App\Exports\EmployeeTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\Unit; // التأكد من وجود مودل الوحدات
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employees.view')->only(['index', 'show']);
        $this->middleware('permission:employees.create')->only(['create', 'store']);
        $this->middleware('permission:employees.edit')->only(['edit', 'update']);
        $this->middleware('permission:employees.delete')->only('destroy');
    }

    /**
     * عرض قائمة الموظفين مع الربط مع جدول الوحدات
     */
   public function index(Request $request)
{
    // 1. استرجاع وحدة المستخدم الحالية (من سجل دخول المستخدم)
    $userUnitId = auth()->user()->unit_id;

    // 2. البدء ببناء الاستعلام مع التحميل المسبق للعلاقة
    $query = Employee::with('unit');

    // 3. تطبيق التقييد بناءً على الوحدة:
    // إذا كان للمستخدم وحدة محددة، نعرض موظفي وحدته فقط.
    // أما إذا كان selectedUnitId قادماً من طلب الفلترة، فيتم استخدامه (للمدراء).
    $selectedUnitId = $request->unit_id ?? $userUnitId;

    if (!empty($selectedUnitId)) {
        $query->where('unit_id', $selectedUnitId);
    }

    // 4. البحث بالنص (الاسم أو الكود)
    if ($request->filled('search')) {
        $searchTerm = trim($request->search);
        $query->where(function ($q) use ($searchTerm) {
            $q->where('full_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('employee_code', 'like', '%' . $searchTerm . '%');
        });
    }

    // 5. جلب البيانات النهائية
    $employees = $query->latest()->paginate(100);

    // 6. جلب الوحدات للفلترة (إذا كان المستخدم مديراً يرى الكل، وإلا يرى وحدته فقط)
    if (empty($userUnitId)) {
        $units = Unit::all();
    } else {
        $units = Unit::where('id', $userUnitId)->get();
    }

    return view('dashboard.employees.index', compact('employees', 'units'));
}

    /**
     * عرض صفحة الإضافة مع جلب الوحدات
     */
    public function create()
    {
         $unit = auth()->user()->unit;
            if ($unit) {
                $units = Unit::where('id', $unit->id)->get();
            } else {
                $units = Unit::all();
            }

        return view('dashboard.employees.create', compact('units'));
    }

    /**
     * حفظ البيانات بناءً على هيكلية الجدول الجديد
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name'           => 'required|string|max:255',
            'employee_code'       => 'required|string|unique:employees,employee_code',
            'unit_id'             => 'required|exists:units,id',
            'total_allowed_days'  => 'required|integer|min:0',
            'is_active'           => 'nullable', // سنعالجها كـ boolean
        ], [
            'full_name.required'     => 'اسم الموظف مطلوب',
            'employee_code.unique'   => 'هذا الكود الوظيفي مستخدم مسبقاً',
            'unit_id.required'       => 'يرجى اختيار الوحدة التنظيمية',
            'unit_id.exists'         => 'الوحدة المختارة غير موجودة',
        ]);

        // معالجة الرصيد المتبقي (عند الإضافة يكون مساوياً للإجمالي)
        $validatedData['remaining_days'] = $request->total_allowed_days;

        // معالجة حالة الموظف
        $validatedData['is_active'] = $request->has('is_active') ? true : false;

        Employee::create($validatedData);

        return redirect()
            ->route('dashboard.employees.index')
            ->with('success', 'تم تسجيل الموظف بنجاح.');
    }
public function show($id)
{
    $employee = Employee::with('unit')->findOrFail($id);
    // تأكد من أن المسار هنا يطابق مجلداتك في الـ views
    return view('dashboard.employees.show', compact('employee'));
}

    /**
     * صفحة التعديل
     */
    public function edit(Employee $employee)
    {
        $unit = auth()->user()->unit;
        if ($unit) {
            $units = Unit::where('id', $unit->id)->get();
        } else {
            $units = Unit::all();
        }
        return view('dashboard.employees.edit', compact('employee', 'units'));
    }

    /**
     * تحديث البيانات
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'full_name'           => 'required|string|max:255',
            'employee_code'       => 'required|string|unique:employees,employee_code,' . $employee->id,
            'unit_id'             => 'required|exists:units,id',
            'total_allowed_days'  => 'required|integer|min:0',
            'is_active'           => 'nullable',
        ]);

        // تحديث حالة الموظف
        $validatedData['is_active'] = $request->has('is_active') ? true : false;

        // ملاحظة: إذا قمت بتعديل total_allowed_days، قد تحتاج لمعادلة لتعديل الـ remaining_days
        // لكن حالياً سنكتفي بتحديث البيانات الأساسية

        $employee->update($validatedData);

        return redirect()
            ->route('dashboard.employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }
    public function export()
    {
        return Excel::download(new EmployeesExport, 'employees_' . date('Y-m-d') . '.xlsx');
    }
        public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'employees_template.xlsx');
    }
    // دالة الاستيراد
    public function import(Request $request)
    {
        $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);


        Excel::import(new EmployeesImport,  $request->file('file'));

            return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح.');
            }
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->back()->with('success', 'تم حذف الموظف بنجاح');
    }
}
