<?php

namespace App\Exports;

use App\Models\WellLicense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WellLicensesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // جلب البيانات مع العلاقة لتجنب استعلامات N+1
        return WellLicense::with('station')->get();
    }

    /**
     * تحديد عناوين الأعمدة في ملف الإكسل
     */
    public function headings(): array
    {
        return [
            'ID',
            'كود الأرشفة',
            'رقم العقار',
            'المنطقة العقارية',
            'اسم مقدم الطلب',
            'نوع الطلب',
            'تاريخ كتاب المؤسسة',
            'رقم كتاب الموارد',
            'تاريخ كتاب الموارد',
            'المسافة المصرح بها (متر)',
            'أقرب محطة (ID)',
            'أقرب محطة (اسم)',
            'خط العرض',
            'خط الطول',
            'الخزانة',
            'الرف',
            'رقم الملف',
            'ملاحظات',
            'تاريخ الإنشاء',
        ];
    }

    /**
     * تحديد البيانات التي ستوضع في كل صف
     */
    public function map($license): array
    {
        return [
            $license->id,
            $license->archive_code,
            $license->property_number,
            $license->property_zone,
            $license->applicant_name,
            $license->request_type,
            $license->institution_letter_date?->format('Y-m-d'),
            $license->directorate_letter_number,
            $license->directorate_letter_date?->format('Y-m-d'),
            $license->declared_distance_meters,
            $license->station_id,
            $license->station?->station_name, // استخدام العلاقة لجلب اسم المحطة
            $license->latitude,
            $license->longitude,
            $license->physical_cabinet,
            $license->physical_shelf,
            $license->physical_file_id,
            $license->notes,
            $license->created_at->format('Y-m-d H:i'),
        ];
    }
}
