<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AggregatedWaterWellsExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // سنقوم بإضافة الأعمدة المحسوبة هنا قبل التصدير
        return $this->data->map(function ($well) {
            $difference = $well['total_measured_qty'] - $well['total_sold_qty'];
            
            $differencePercentage = 0;
            if ($well['total_sold_qty'] > 0) {
                // حساب نسبة الفرق لتحديد نسبة الهدر أو الزيادة
                $differencePercentage = round(($difference / $well['total_sold_qty']) * 100, 2) . '%';
            }
            
            return [
                'well_name'               => $well['well_name'],
                'total_measured_qty'      => $well['total_measured_qty'],
                'total_sold_qty'          => $well['total_sold_qty'],
                'quantity_difference'     => $difference, // الفرق بين المقاس والمباع
                'difference_percentage'   => $differencePercentage, // نسبة الفرق
                'total_free_qty'          => $well['total_free_qty'],
                'total_vehicle_qty'       => $well['total_vehicle_qty'],
                'water_price'             => $well['water_price'],
                'total_amount'            => $well['total_amount'],
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // رؤوس الأعمدة في ملف Excel
        return [
            'اسم المنهل',
            'إجمالي الكمية المقاسة (م3)',
            'إجمالي كمية البيع (م3)',
            'الفرق في الكمية (الهدر)',
            'نسبة الفرق',
            'إجمالي الكمية المجانية (م3)',
            'إجمالي تعبئة المركبات (م3)',
            'سعر المياه',
            'القيمة الإجمالية المحسوبة',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // اسم ورقة العمل (Sheet)
        return 'ملخص المناهل المجمع';
    }
}