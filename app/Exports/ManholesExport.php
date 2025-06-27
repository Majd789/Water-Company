<?php

namespace App\Exports;

use App\Models\Manhole;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class ManholesExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب المناهل المرتبطة بوحدة المستخدم فقط
            $manholes = Manhole::where('unit_id', $userUnitId)
                ->with(['station.town.unit.governorate', 'unit', 'town'])
                ->get();
        } else {
            // جلب جميع بيانات المناهل إذا لم يكن هناك وحدة مرتبطة
            $manholes = Manhole::with(['station.town.unit.governorate', 'unit', 'town'])->get();
        }

        // تعديل البيانات قبل تصديرها لتطابق الترويسات تمامًا
        return $manholes->map(function ($manhole) {
            return [
                'id' => $manhole->id,
                'governorate_name' => $manhole->station->town->unit->governorate->name ?? 'غير محددة',
                'unit_name' => $manhole->unit->unit_name ?? 'غير معروف', // استخدام العلاقة المباشرة
                'town_name' => $manhole->town->town_name ?? 'غير معروف', // استخدام العلاقة المباشرة
                'station_code' => $manhole->station->station_code ?? 'غير معروف',
                'station_name' => $manhole->station->station_name ?? 'غير معروف',
                'manhole_name' => $manhole->manhole_name,
                'status' => $manhole->status,
                'stop_reason' => $manhole->stop_reason,
                'has_flow_meter' => $manhole->has_flow_meter ? 'نعم' : 'لا',
                'chassis_number' => $manhole->chassis_number,
                'meter_diameter' => $manhole->meter_diameter,
                'meter_status' => $manhole->meter_status,
                'meter_operation_method_in_meter' => $manhole->meter_operation_method_in_meter,
                'has_storage_tank' => $manhole->has_storage_tank ? 'نعم' : 'لا',
                'tank_capacity' => $manhole->tank_capacity,
                'general_notes' => $manhole->general_notes,
                 'created_at' => $manhole->created_at,            ];
        });
    }

    public function headings(): array
    {
        // تم تصحيح الترويسات وإزالة التكرار
        return [
            'ID',
            'اسم المحافظة',
            'اسم الوحدة',
            'اسم البلدة',
            'كود المحطة',
            'اسم المحطة',
            'اسم المنهل',
            'الوضع التشغيلي',
            'سبب التوقف',
            'هل يوجد عداد غزارة',
            'رقم الشاسيه',
            'قطر العداد',
            'وضع العداد',
            'طريقة عمل العداد',
            'هل يوجد خزان تجميعي',
            'سعة الخزان',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }

    public function title(): string
    {
        return 'المناهل';
    }
}