<?php

namespace App\Exports;

use App\Models\HorizontalPump;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class HorizontalPumpsExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب المضخات الأفقية المرتبطة بهذه المحطات فقط
            $pumps = HorizontalPump::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // تحميل علاقة المحافظة
                ->get();
        } else {
            // جلب جميع المضخات في حال عدم ارتباط المستخدم بوحدة محددة
            $pumps = HorizontalPump::with('station.town.unit.governorate')->get();
        }

        return $pumps->map(function ($pump) {
            return [
                'id' => $pump->id,
                'governorate_name' => $pump->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $pump->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $pump->station->station_code ?? 'غير معروف', 
                'station_name' => $pump->station->station_name ?? 'غير معروف', 
                'pump_name' => $pump->pump_name ?? 'غير معروف', 
                'pump_status' => $pump->pump_status ?? 'غير معروف', 
                'pump_capacity_hp' => $pump->pump_capacity_hp ?? 0, 
                'pump_flow_rate_m3h' => $pump->pump_flow_rate_m3h ?? 0, 
                'pump_head' => $pump->pump_head ?? 0, 
                'pump_brand_model' => $pump->pump_brand_model ?? 'غير معروف', 
                'technical_condition' => $pump->technical_condition ?? 'غير معروف', 
                'energy_source' => $pump->energy_source ?? 'غير معروف', 
                'notes' => $pump->notes ?? 'لا توجد ملاحظات', 
                'operator_entity' => $pump->station->operator_entity ?? 'غير معروف', 
                'operator_name' => $pump->station->operator_name ?? 'غير معروف', 
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
            'اسم المضخة',
            'الحالة التشغيلية',
            'استطاعة المضخة (حصان)',
            'تدفق المضخة (متر مكعب/ساعة)',
            'ارتفاع الضخ',
            'ماركة وطراز المضخة',
            'الحالة الفنية',
            'مصدر الطاقة',
            'ملاحظات',
            'جهة التشغيل',
            'اسم المشغل',
        ];
    }

    public function title(): string
    {
        return 'المضخات الأفقية';
    }
}
