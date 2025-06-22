<?php
namespace App\Exports;

use App\Models\Station;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StationsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب المحطات الخاصة بوحدة المستخدم فقط
            $query = Station::whereHas('town', function ($q) use ($userUnitId) {
                $q->where('unit_id', $userUnitId);
            })->with('town.unit.governorate'); // تحميل الوحدة والمحافظة
        } else {
            // جلب جميع المحطات إذا لم يكن للمستخدم وحدة مرتبطة
            $query = Station::with('town.unit.governorate');
        }

        return $query->get()->map(function ($station) {
            return [
                'ID' => $station->id,
                'كود المحطة' => $station->station_code,
                'اسم المحطة' => $station->station_name,
                'اسم البلدة' => $station->town->town_name ?? 'غير محددة',
                'اسم الوحدة' => $station->town->unit->unit_name ?? 'غير محددة', // جلب اسم الوحدة
                'اسم المحافظة' => $station->town->unit->governorate->name ?? 'غير محددة', // جلب اسم المحافظة
                'حالة التشغيل' => $station->operational_status,
                'سبب التوقف' => $station->stop_reason ,
                'مصدر الطاقة' => $station->energy_source ,
                'جهة التشغيل' => $station->operator_entity ,
                'اسم المشغل' => $station->operator_name ,
                'ملاحظات عامة' => $station->general_notes ?? 'لا يوجد',
                'طريقة التوصيل' => $station->water_delivery_method ,
                'جاهزية الشبكة (%)' => $station->network_readiness_percentage ?? 0,
                'عدد الأسر المستفيدة' => $station->beneficiary_families_count ?? 0,
                'التعقيم متوفر' => $station->has_disinfection ? 'نعم' : 'لا',
                'سبب عدم التعقيم' => $station->disinfection_reason ,
                'المواقع المخدومة' => $station->served_locations ,
                'التدفق الفعلي' => $station->actual_flow_rate ?? 0,
                'نوع المحطة' => $station->station_type ,
                'العنوان التفصيلي' => $station->detailed_address ,
                'مساحة الأرض' => $station->land_area ?? 0,
                'نوع التربة' => $station->soil_type ,
                'ملاحظات البناء' => $station->building_notes ?? 'لا يوجد',
                'خط العرض' => $station->latitude ?? 0,
                'خط الطول' => $station->longitude ?? 0,
                'تم التحقق منها' => $station->is_verified ? 'نعم' : 'لا',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'id',
            'كود المحطة', 'اسم المحطة', 'اسم البلدة', 'اسم الوحدة', 'اسم المحافظة', 'حالة التشغيل', 'سبب التوقف', 'مصدر الطاقة',
            'جهة التشغيل', 'اسم المشغل', 'ملاحظات عامة', 'طريقة التوصيل', 'جاهزية الشبكة (%)',
            'عدد الأسر المستفيدة', 'التعقيم متوفر', 'سبب عدم التعقيم', 'المواقع المخدومة',
            'التدفق الفعلي', 'نوع المحطة', 'العنوان التفصيلي', 'مساحة الأرض', 'نوع التربة',
            'ملاحظات البناء', 'خط العرض', 'خط الطول', 'تم التحقق منها'
        ];
    }

    // ✅ تنسيق العناوين
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ];
    }

    // ✅ ضبط عرض الأعمدة
    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 20, 'C' => 20, 'D' => 15, 'E' => 20,
            'F' => 20, 'G' => 25, 'H' => 25, 'I' => 30, 'J' => 20,
            'K' => 20, 'L' => 20, 'M' => 15, 'N' => 25, 'O' => 30,
            'P' => 20, 'Q' => 20, 'R' => 30, 'S' => 15, 'T' => 20,
            'U' => 30, 'V' => 15, 'W' => 15, 'X' => 15,
        ];
    }

    public function title(): string
    {
        return 'المحطات';
    }
}
