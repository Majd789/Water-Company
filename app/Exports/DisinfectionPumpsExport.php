<?php

namespace App\Exports;

use App\Models\DisinfectionPump;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class DisinfectionPumpsExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');
    
            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب مضخات التعقيم المرتبطة بهذه المحطات فقط مع تحميل العلاقات
            return DisinfectionPump::whereIn('station_id', $stations)
                ->with([
                    'station.town.unit', // جلب الوحدة والمحافظة المرتبطة بالمحطة
                ])
                ->get()
                ->map(function ($disinfectionPump) {
                    return [
                        'id'=>$disinfectionPump->id,
                        'unit_name' => $disinfectionPump->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                        'governorate_name' => $disinfectionPump->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                        'station_code' => $disinfectionPump->station->station_code,
                        'disinfection_pump_status' => $disinfectionPump->disinfection_pump_status,
                        'pump_brand_model' => $disinfectionPump->pump_brand_model,
                        'pump_flow_rate' => $disinfectionPump->pump_flow_rate,
                        'operating_pressure' => $disinfectionPump->operating_pressure,
                        'technical_condition' => $disinfectionPump->technical_condition,
                        'notes' => $disinfectionPump->notes ?? 'غير محددة',
                        'created_at' => $disinfectionPump->created_at,
                    ];
                });
        }

        // في حال عدم وجود وحدة، يتم جلب جميع المضخات
        return DisinfectionPump::with([
            'station.town.unit', // جلب الوحدة والمحافظة المرتبطة بالمحطة
        ])
            ->get()
            ->map(function ($disinfectionPump) {
                return [
                    'id'=>$disinfectionPump->id,
                    'unit_name' => $disinfectionPump->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                    'governorate_name' => $disinfectionPump->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                    'station_code' => $disinfectionPump->station->station_code,
                    'disinfection_pump_status' => $disinfectionPump->disinfection_pump_status,
                    'pump_brand_model' => $disinfectionPump->pump_brand_model,
                    'pump_flow_rate' => $disinfectionPump->pump_flow_rate,
                    'operating_pressure' => $disinfectionPump->operating_pressure,
                    'technical_condition' => $disinfectionPump->technical_condition,
                    'notes' => $disinfectionPump->notes ?? 'غير محددة',
                    'created_at' => $disinfectionPump->created_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'id',
            'اسم الوحدة',  // إضافة اسم الوحدة
            'اسم المحافظة',  // إضافة اسم المحافظة
            'كود المحطة',
            'الوضع التشغيلي',
            'ماركة وطراز المضخة',
            'غزارة المضخة (لتر/ساعة)',
            'ضغط العمل',
            'الحالة الفنية',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }

    public function title(): string
    {
        return 'مضخات التعقيم';
    }
}
