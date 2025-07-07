<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\InstitutionProperty;
use App\Models\Station;
use Illuminate\Http\Request;

class InstitutionPropertyController extends Controller
{
    /**
     * عرض جميع العقارات المؤسسية.
     */
    public function index()
    {
        $institutionProperties = InstitutionProperty::with('station')->get();
        return view('institution_properties.index', compact('institutionProperties'));
    }

    /**
     * عرض نموذج إنشاء عقار مؤسسي جديد.
     */
    public function create()
    {
        $stations = Station::all();
        return view('institution_properties.create', compact('stations'));
    }

    /**
     * تخزين عقار مؤسسي جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'department_name' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'property_use' => 'required|string|max:255',
            'property_nature' => 'required|string|max:255',
            'rental_value' => 'required|numeric|min:0',
            'general_notes' => 'nullable|string',
        ]);

        InstitutionProperty::create($request->all());

        return redirect()->route('institution_properties.index')->with('success', 'تمت إضافة العقار بنجاح.');
    }

    /**
     * عرض تفاصيل عقار معين.
     */
    public function show(InstitutionProperty $institutionProperty)
    {
        return view('institution_properties.show', compact('stations', 'property'));
    }

    /**
     * عرض نموذج تعديل عقار.
     */
    public function edit($id)
    {
        $stations = Station::all(); // جلب جميع المحطات
        $property = InstitutionProperty::findOrFail($id); // جلب بيانات العقار المطلوب
        return view('institution_properties.edit', compact('stations', 'property'));
    }

    /**
     * تحديث بيانات العقار.
     */
    public function update(Request $request, InstitutionProperty $institutionProperty)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'department_name' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'property_use' => 'required|string|max:255',
            'property_nature' => 'required|string|max:255',
            'rental_value' => 'required|numeric|min:0',
            'general_notes' => 'nullable|string',
        ]);

        $institutionProperty->update($request->all());

        return redirect()->route('institution_properties.index')->with('success', 'تم تحديث العقار بنجاح.');
    }

    /**
     * حذف عقار معين.
     */
    public function destroy(InstitutionProperty $institutionProperty)
    {
        $institutionProperty->delete();

        return redirect()->route('institution_properties.index')->with('success', 'تم حذف العقار بنجاح.');
    }
}
