<?php

namespace App\Exports;

use App\Models\GenerationGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class GenerationGroupsExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
{
    $userUnitId = Auth::user()->unit_id;

    if ($userUnitId) {
        // جلب البلدات المرتبطة بالوحدة
        $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

        // جلب المحطات المرتبطة بهذه البلدات
        $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

        // جلب مجموعات التوليد المرتبطة بهذه المحطات فقط مع تحميل العلاقات
        return GenerationGroup::whereIn('station_id', $stations)
            ->with([
                'station.town.unit', // جلب الوحدة المرتبطة بالمحطة
            ])
            ->get()
            ->map(function ($generationGroup) {
                return [
                    'unit_name' => $generationGroup->station->town->unit->unit_name ?? 'غير محددة',
                   'governorate_name' => $generationGroup->station->town->unit->governorate->name ?? 'غير محددة',  // إضافة اسم المحافظة
                    'station_code' => $generationGroup->station->station_code,
                    'station_name' => $generationGroup->station->station_name ?? 'غير معروف',
                    'operational_status' => $generationGroup->operational_status,
                    'generator_name' => $generationGroup->generator_name,
                    'generation_capacity' => $generationGroup->generation_capacity,
                    'actual_operating_capacity' => $generationGroup->actual_operating_capacity,
                    'generation_group_readiness_percentage' => $generationGroup->generation_group_readiness_percentage ?? 'غير محددة',
                    'fuel_consumption' => $generationGroup->fuel_consumption,
                    'oil_usage_duration' => $generationGroup->oil_usage_duration,
                    'oil_quantity_for_replacement' => $generationGroup->oil_quantity_for_replacement,
                    'notes' => $generationGroup->notes ?? 'غير محددة',
                    'stop_reason' => $generationGroup->stop_reason ?? 'غير محددة',
                    'created_at' => $generationGroup->created_at,
                ];
            });
    }

    // إذا لم يكن هناك رقم وحدة، جلب جميع مجموعات التوليد
    return GenerationGroup::with([
        'station.town.unit', // جلب الوحدة المرتبطة بالمحطة
    ])
        ->get()
        ->map(function ($generationGroup) {
            return [
                'unit_name' => $generationGroup->station->town->unit->unit_name ?? 'غير محددة',
               'governorate_name' => $generationGroup->station->town->unit->governorate->name ?? 'غير محددة',  // إضافة اسم المحافظة
                'station_code' => $generationGroup->station->station_code,
                'station_name' => $generationGroup->station->station_name ?? 'غير معروف',
                'operational_status' => $generationGroup->operational_status,
                'generator_name' => $generationGroup->generator_name,
                'generation_capacity' => $generationGroup->generation_capacity,
                'actual_operating_capacity' => $generationGroup->actual_operating_capacity,
                'generation_group_readiness_percentage' => $generationGroup->generation_group_readiness_percentage ?? 'غير محددة',
                'fuel_consumption' => $generationGroup->fuel_consumption,
                'oil_usage_duration' => $generationGroup->oil_usage_duration,
                'oil_quantity_for_replacement' => $generationGroup->oil_quantity_for_replacement,
                'notes' => $generationGroup->notes ?? 'غير محددة',
                'stop_reason' => $generationGroup->stop_reason ?? 'غير محددة',
                'created_at' => $generationGroup->created_at,
            ];
        });
}

    public function headings(): array
    {
        return [
            'اسم الوحدة',  // إضافة اسم الوحدة
            'اسم المحافظة',  // إضافة اسم المحافظة
            'كود المحطة',
             'اسم المحطة',
            'الوضع التشغيلي',
            'اسم المولدة',
            'استطاعة التوليد (KVA)',
            'الاستطاعة الفعلية',
            'نسبة الجاهزية',
            'استهلاك الوقود (لتر/ساعة)',
            'مدة استخدام الزيت',
            'كمية الزيت في التبديل',
            'الملاحظات',
            'سبب التوقف',
            'تاريخ الإنشاء',
        ];
    }
    
    public function title(): string
    {
        return 'مجموعات التوليد';
    }
}
