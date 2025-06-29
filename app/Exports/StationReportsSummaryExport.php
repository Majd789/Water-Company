<?php

namespace App\Exports;

use Illuminate\Support\Collection; // أضف هذا
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StationReportsSummaryExport implements WithMultipleSheets
{
    use Exportable;

    protected $aggregatedWaterWellsData;

    /**
     * قم بتمرير بيانات المناهل المجمعة عند إنشاء الكائن
     */
    public function __construct(Collection $aggregatedWaterWellsData)
    {
        $this->aggregatedWaterWellsData = $aggregatedWaterWellsData;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        // بناء مصفوفة أوراق العمل
        $sheets = [];

        // ورقة العمل الأولى: إحصائيات الوحدات (كما كانت)
        $sheets[] = new UnitStatsSheetExport();
        
        // ورقة العمل الثانية: إحصائيات المحطات (كما كانت)
        $sheets[] = new StationStatsSheetExport();

        // ورقة العمل الثالثة الجديدة: ملخص المناهل المجمع
        // نمرر لها البيانات التي استقبلناها في الـ constructor
        $sheets[] = new AggregatedWaterWellsExport($this->aggregatedWaterWellsData);

        return $sheets;
    }
}