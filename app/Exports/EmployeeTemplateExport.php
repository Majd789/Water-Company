<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeTemplateExport implements WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'اسم الموظف',
            'الكود الوظيفي',
            'رقم الوحدة', // سيوضع هنا ID الوحدة
            'الرصيد الكلي',
        ];
    }
}
