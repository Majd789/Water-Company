<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectComprehensiveExport implements WithMultipleSheets
{
    use Exportable;

    protected $filters;

    /**
     * نستقبل الفلاتر (مثل البحث، المنظمة، الوحدة) لتمريرها للصفحات الداخلية
     * لضمان أن التقرير يطابق ما يراه المستخدم في الجدول
     */
    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * تعريف الصفحات (Sheets) الموجودة داخل ملف الإكسل
     */
    public function sheets(): array
    {
        $sheets = [];

        // الورقة الأولى: ملخص الإحصائيات (الأعداد والملخصات)
        $sheets[] = new ProjectSummarySheet($this->filters);

        // الورقة الثانية: التفاصيل الكاملة (الجدول الكبير)
        $sheets[] = new ProjectDetailsSheet($this->filters);

        return $sheets;
    }
}
