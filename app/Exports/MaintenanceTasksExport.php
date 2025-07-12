<?php

namespace App\Exports;

use App\Models\MaintenanceTask;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceTasksExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
     protected $unitId;

    public function __construct($unitId = null)
    {
        $this->unitId = $unitId;
    }

    public function collection()
    {
        $query = MaintenanceTask::with('unit');

        if ($this->unitId) {
            $query->where('unit_id', $this->unitId);
        }

        return $query->get();
    }

    /**
     * تحديد العناوين (أسماء الأعمدة) في ملف الإكسل.
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'الفني المسؤول',
            'تاريخ الصيانة',
            'الوحدة',
            'مكان العطل',
            'سبب العطل',
            'وصف العطل',
            'إجراءات الصيانة',
            'هل تم الإصلاح؟',
            'سبب عدم الإصلاح',
            'ملاحظات',
            'تاريخ الإنشاء',
        ];
    }

    /**
     * تنسيق كل صف من البيانات قبل كتابته في الملف.
     * @param mixed $task
     * @return array
     */
    public function map($task): array
    {
        return [
            $task->id,
            $task->technician_name,
            $task->maintenance_date,
            $task->unit->unit_name ?? 'N/A', // عرض اسم الوحدة بدلاً من الـ ID
            $task->location,
            $task->fault_cause,
            $task->fault_description,
            $task->maintenance_actions,
            $task->is_fixed ? 'نعم' : 'لا', // عرض "نعم" أو "لا" بدلاً من 1 أو 0
            $task->reason_not_fixed,
            $task->notes,
            $task->created_at->format('Y-m-d H:i'), // تنسيق تاريخ الإنشاء
        ];
    }
}