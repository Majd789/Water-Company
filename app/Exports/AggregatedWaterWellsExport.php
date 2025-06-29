<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping; // <-- أضف هذا

class AggregatedWaterWellsExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithMapping
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
        // نمرر البيانات كما هي لأن المعالجة تمت في الـ Controller
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // رؤوس الأعمدة الجديدة بالترتيب المطلوب
        return [
            'وحدة المياه',
            'البلدة',
            'المحطة',
            'كود المحطة',
            'اسم المنهل',
            'أيام العمل',
            'أيام التوقف',
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
     * @param mixed $well
     * @return array
     */
    public function map($well): array
    {
        // نقوم بتنسيق كل صف هنا
        $difference = $well['total_measured_qty'] - $well['total_sold_qty'];
        
        $differencePercentage = '0%';
        if ($well['total_sold_qty'] > 0) {
            $differencePercentage = round(($difference / $well['total_sold_qty']) * 100, 2) . '%';
        }

        return [
            $well['unit_name'],
            $well['town_name'],
            $well['station_name'],
            $well['station_code'],
            $well['well_name'],
            $well['days_working'],
            $well['days_stopped'],
            $well['total_measured_qty'],
            $well['total_sold_qty'],
            $difference, // الفرق المحسوب
            $differencePercentage, // النسبة المحسوبة
            $well['total_free_qty'],
            $well['total_vehicle_qty'],
            $well['water_price'],
            $well['total_amount'],
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'ملخص المناهل المجمع';
    }
}