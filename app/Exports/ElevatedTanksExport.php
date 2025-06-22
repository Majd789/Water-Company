<?php

namespace App\Exports;

use App\Models\ElevatedTank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class ElevatedTanksExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب الخزانات العالية المرتبطة بهذه المحطات فقط
            $tanks = ElevatedTank::whereIn('station_id', $stations)->with('station.town.unit.governorate')->get();
        } else {
            // جلب جميع الخزانات العالية إذا لم يكن هناك وحدة مرتبطة
            $tanks = ElevatedTank::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $tanks->map(function ($tank) {
            return [
                'id' => $tank->id,
                'governorate_name' => $tank->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $tank->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $tank->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $tank->station->station_name ?? 'غير معروف', // اسم المحطة
                'tank_name' => $tank->tank_name,
                'building_entity' => $tank->building_entity, // الجهة المنشئة
                'construction_date' => $tank->construction_date, // جديد / قديم
                'capacity' => $tank->capacity, // سعة الخزان
                'readiness_percentage' => $tank->readiness_percentage, // نسبة الجاهزية
                'height' => $tank->height, // ارتفاع الخزان
                'tank_shape' => $tank->tank_shape, // شكل الخزان
                'feeding_station' => $tank->feeding_station, // المحطة المغذية
                'town_supply' => $tank->town_supply, // البلدة التي تشرب منه
                'in_pipe_diameter' => $tank->in_pipe_diameter, // قطر البوري الداخل
                'out_pipe_diameter' => $tank->out_pipe_diameter, // قطر البوري الخارج
                'latitude' => $tank->latitude ,
                'longitude' => $tank->longitude ,
                'altitude' => $tank->altitude ,
                'precision' => $tank->precision ,
                'notes' => $tank->notes ?? 'لا توجد ملاحظات',
             
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
            'اسم الخزان',
            'الجهة المنشئة',
            'تاريخ البناء',
            'سعة الخزان (م³)',
            'نسبة الجاهزية',
            'ارتفاع الخزان',
            'شكل الخزان',
            'المحطة المغذية',
            'البلدة المزودة',
            'قطر البوري الداخل',
            'قطر البوري الخارج',
            'خط العرض',
            'خط الطول',
            'الارتفاع',
            'دقة الموقع',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'خزانات عالية';
    }
}
