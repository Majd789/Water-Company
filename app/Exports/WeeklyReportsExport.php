<?php

namespace App\Exports;

use App\Models\WeeklyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WeeklyReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WeeklyReport::with('unit')->get()->map(function ($report) {
            return [
                'الوحدة' => $report->unit->unit_name ?? '',
                'تاريخ التقرير' => $report->report_date,
                'اسم المرسل' => $report->sender_name,
                'الوضع التشغيلي' => $report->operational_status,
                'سبب التوقف' => $report->stop_reason,
                'أعمال الصيانة' => $report->maintenance_works,
                'الجهة المنفذة' => $report->maintenance_entity,
                'أعمال إدارية' => $report->administrative_works,
                'ملاحظات إضافية' => $report->additional_notes,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'الوحدة',
            'تاريخ التقرير',
            'اسم المرسل',
            'الوضع التشغيلي',
            'سبب التوقف',
            'أعمال الصيانة',
            'الجهة المنفذة',
            'أعمال إدارية',
            'ملاحظات إضافية',
        ];
    }
}
