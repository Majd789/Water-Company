<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;


class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
          $userUnitId = Auth::user()->unit_id;
            if ($userUnitId) {
                return Employee::with('unit')->where('unit_id', $userUnitId)->get();
            } else {
                return Employee::with('unit')->get();
            }
        return Employee::with('unit')->where('unit_id', $userUnitId)->get();
    }

    // تحديد العناوين في ملف الإكسل
    public function headings(): array
    {
        return [
            'اسم الموظف',
            'الكود الوظيفي',
            'الوحدة',
            'الرصيد الكلي',
            'الرصيد المتبقي',
            'الحالة',
        ];
    }

    // تحديد البيانات وكيفية ظهورها
    public function map($employee): array
    {
        return [
            $employee->full_name,
            $employee->employee_code,
            $employee->unit->name ?? 'غير محدد',
            $employee->total_allowed_days,
            $employee->remaining_days,
            $employee->is_active ? 'نشط' : 'غير نشط',
        ];
    }
}
