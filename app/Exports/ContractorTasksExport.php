<?php

namespace App\Exports;

use App\Models\ContractorTask;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContractorTasksExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return ContractorTask::query()->with(['projectContractor', 'projectActivity']);
    }

    public function map($task): array
    {
        return [
            $task->task_code,                                      // كود المهمة
            $task->projectContractor->contract_code ?? 'N/A',      // كود العقد
            $task->projectActivity->activity_code ?? 'N/A',        // كود النشاط
            $task->description,                                    // وصف المهمة
            $task->quantity,                                       // الكمية
            $task->unit_measure,                                   // الواحدة
            $task->cost,                                           // الكلفة
            $task->notes,                                          // ملاحظات
            $task->is_discrepant ? 'نعم' : 'لا',                   // هل يوجد عدم تطابق
            $task->discrepancy_notes,                              // ملاحظات عدم التطابق
            $task->created_at->format('Y-m-d'),                    // تاريخ الإضافة
        ];
    }

    public function headings(): array
    {
        return [
            'كود المهمة',
            'كود العقد',
            'كود النشاط',
            'وصف المهمة',
            'الكمية',
            'الواحدة',
            'الكلفة',
            'ملاحظات',
            'غير مطابق؟',
            'ملاحظات عدم التطابق',
            'تاريخ الإضافة',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
