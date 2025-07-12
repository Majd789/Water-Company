<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\MaintenanceTask;
use App\Models\Unit;
use Illuminate\Http\Request;
// use App\Http\Requests\UpdateMaintenanceTaskRequest; // Uncomment if you have a custom request

class MaintenanceTaskController extends Controller
{
    
    /**
     * إنشاء كونستركتور للتحقق من الصلاحيات
     */
    public function __construct(){
        $this->middleware(['permission:maintenance_tasks.view'])->only('index', 'show');
        $this->middleware(['permission:maintenance_tasks.create'])->only('create', 'store');
        $this->middleware(['permission:maintenance_tasks.edit'])->only('edit', 'update');
        $this->middleware(['permission:maintenance_tasks.delete'])->only('destroy');
    }
    /**
     * عرض قائمة بجميع مهام الصيانة.
     */
   public function index(Request $request) // أضف Request هنا
    {
        // ابدأ بالاستعلام الأساسي
        $query = MaintenanceTask::with('unit')->latest();

        // قم بالفلترة إذا تم إرسال unit_id
        if ($request->has('unit_id') && $request->unit_id != '') {
            $query->where('unit_id', $request->unit_id);
        }
        
        // نفذ الاستعلام مع الترقيم
        $maintenanceTasks = $query->paginate(15);
        
        // جلب جميع الوحدات لقائمة الفلترة
        $units = Unit::select('id', 'unit_name')->get(); 
        
        return view('dashboard.maintenance_tasks.index', compact('maintenanceTasks', 'units'));
    }

    /**
     * عرض نموذج إنشاء مهمة صيانة جديدة.
     */
    public function create()
    {
       
        $units = Unit::all();
        return view('dashboard.maintenance_tasks.create', compact('units'));
    }

    /**
     * تخزين مهمة الصيانة الجديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
       $validatedData = $request->validate([
            'technician_name'   => 'required|string|max:255',         // يجب أن يكون موجوداً، نصي، وأقصى طول 255 حرف
            'maintenance_date'  => 'required|date',                   // يجب أن يكون موجوداً ومن نوع تاريخ صحيح
            'unit_id'           => 'required|exists:units,id',        // يجب أن يكون موجوداً، وقيمته يجب أن تكون في جدول units عمود id
            'location'          => 'required|string|max:255',         // يجب أن يكون موجوداً، نصي، وأقصى طول 255 حرف
            'fault_description' => 'required|string',                 // يجب أن يكون موجوداً ونصي
            'fault_cause'       => 'nullable|string',                 // يمكن أن يكون فارغاً، وإذا وجد يجب أن يكون نصي
            'maintenance_actions' => 'required|string',                 // يجب أن يكون موجوداً ونصي
            'is_fixed'          => 'required|boolean',                // يجب أن يكون موجوداً، وقيمته (true/false, 1/0)
            'reason_not_fixed'  => 'nullable|string|required_if:is_fixed,0', // اختياري، ولكنه يصبح مطلوباً إذا كانت قيمة is_fixed تساوي 0 (false)
            'notes'             => 'nullable|string',                 // يمكن أن يكون فارغاً، وإذا وجد يجب أن يكون نصي
        ]);
        MaintenanceTask::create($validatedData);
        return redirect()->route('dashboard.maintenance_tasks.index')
                         ->with('success', 'تمت إضافة مهمة الصيانة بنجاح.');
    }

    /**
     * عرض تفاصيل مهمة صيانة محددة.
     */
    public function show(MaintenanceTask $maintenanceTask)
    {
        // بفضل Route Model Binding، المتغير $maintenanceTask يحتوي بالفعل على المهمة المطلوبة
        // يمكننا تحميل علاقات أخرى إذا احتجنا
        $maintenanceTask->load('unit');
        
        return view('dashboard.maintenance_tasks.show', compact('maintenanceTask'));
    }

    /**
     * عرض نموذج تعديل مهمة صيانة موجودة.
     */
    public function edit(MaintenanceTask $maintenanceTask)
    {
        // نحتاج قائمة الوحدات للسماح بتغيير الوحدة
        $units = Unit::all();
        
        return view('dashboard.maintenance_tasks.edit', compact('maintenanceTask', 'units'));
    }


    public function update(Request $request, MaintenanceTask $maintenanceTask)
    {
        // الخطوة 1: التحقق من صحة البيانات المدخلة من النموذج
        $validatedData = $request->validate([
            'technician_name'   => 'required|string|max:255',         // يجب أن يكون موجوداً، نصي، وأقصى طول 255 حرف
            'maintenance_date'  => 'required|date',                   // يجب أن يكون موجوداً ومن نوع تاريخ صحيح
            'unit_id'           => 'required|exists:units,id',        // يجب أن يكون موجوداً، وقيمته يجب أن تكون في جدول units عمود id
            'location'          => 'required|string|max:255',         // يجب أن يكون موجوداً، نصي، وأقصى طول 255 حرف
            'fault_description' => 'required|string',                 // يجب أن يكون موجوداً ونصي
            'fault_cause'       => 'nullable|string',                 // يمكن أن يكون فارغاً، وإذا وجد يجب أن يكون نصي
            'maintenance_actions' => 'required|string',               // يجب أن يكون موجوداً ونصي
            'is_fixed'          => 'required|boolean',                // يجب أن يكون موجوداً، وقيمته (true/false, 1/0)
            'reason_not_fixed'  => 'nullable|string|required_if:is_fixed,0', // اختياري، ولكنه يصبح مطلوباً إذا كانت قيمة is_fixed تساوي 0 (false)
            'notes'             => 'nullable|string',                 // يمكن أن يكون فارغاً، وإذا وجد يجب أن يكون نصي
        ]);

        $maintenanceTask->update($validatedData);

        return redirect()->route('dashboard.maintenance_tasks.index')
                         ->with('success', 'تم تحديث مهمة الصيانة بنجاح.');
    }
    

    /**
     * حذف مهمة الصيانة من قاعدة البيانات.
     */
    public function destroy(MaintenanceTask $maintenanceTask)
    {
        $maintenanceTask->delete();

        return redirect()->route('dashboard.maintenance_tasks.index')
                         ->with('success', 'تم حذف مهمة الصيانة بنجاح.');
    }

}
