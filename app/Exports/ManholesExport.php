<?php

namespace App\Exports;

use App\Models\Manhole;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class ManholesExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات المناهل المرتبطة بهذه المحطات فقط
            $manholes = Manhole::whereIn('station_id', $stations)
                ->with(['station', 'unit', 'town','station.town.unit.governorate']) // جلب بيانات المحطة، الوحدة، والبلدة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات المناهل إذا لم يكن هناك وحدة مرتبطة
            $manholes = Manhole::with(['station', 'unit', 'town','station.town.unit.governorate'])->get();
        }

        // تعديل البيانات قبل تصديرها
        return $manholes->map(function ($manhole) {
            return [
                'id' => $manhole->id,
                'governorate_name' => $manhole->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $manhole->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $manhole->station->station_code ?? 'غير معروف', // كود المحطة
                'station_name' => $manhole->station->station_name ?? 'غير معروف', // اسم المحطة
                'unit_name' => $manhole->unit->unit_name ?? 'غير معروف', // اسم الوحدة
                'town_name' => $manhole->town->town_name ?? 'غير معروف', // اسم البلدة
                'manhole_name' => $manhole->manhole_name, // اسم المنهل
                'status' => $manhole->status, // الوضع التشغيلي
                'stop_reason' => $manhole->stop_reason, // سبب التوقف
                'has_flow_meter' => $manhole->has_flow_meter ? 'نعم' : 'لا', // هل يوجد عداد غزارة
                'chassis_number' => $manhole->chassis_number, // رقم الشاسيه
                'meter_diameter' => $manhole->meter_diameter, // قطر العداد
                'meter_status' => $manhole->meter_status, // وضع العداد
                'meter_operation_method_in_meter' => $manhole->meter_operation_method_in_meter, // طريقة عمل العداد
                'has_storage_tank' => $manhole->has_storage_tank ? 'نعم' : 'لا', // هل يوجد خزان تجميعي
                'tank_capacity' => $manhole->tank_capacity, // سعة الخزان
                'general_notes' => $manhole->general_notes, // الملاحظات
                'created_at' => $manhole->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'اسم المحافظة',  // اسم المحافظة
            'اسم الوحدة',     // اسم الوحدة
            'كود المحطة',
            'اسم المحطة',
            'اسم الوحدة',
            'اسم البلدة',
            'اسم المنهل',
            'الوضع التشغيلي',
            'سبب التوقف',
            'هل يوجد عداد غزارة',
            'رقم الشاسيه',
            'قطر العداد',
            'وضع العداد',
            'طريقة عمل العداد بالمتر',
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
