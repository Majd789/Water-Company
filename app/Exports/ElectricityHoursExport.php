<?php

namespace App\Exports;

use App\Models\ElectricityHour;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class ElectricityHoursExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات ساعات الكهرباء المرتبطة بهذه المحطات فقط
            $electricityHours = ElectricityHour::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات ساعات الكهرباء إذا لم يكن هناك وحدة مرتبطة
            $electricityHours = ElectricityHour::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $electricityHours->map(function ($electricityHour) {
            return [
                'id' => $electricityHour->id,
                'governorate_name' => $electricityHour->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $electricityHour->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $electricityHour->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $electricityHour->station->station_name ?? 'غير معروف', // اسم المحطة
                'electricity_hours' => $electricityHour->electricity_hours, // عدد ساعات الكهرباء
                'electricity_hour_number' => $electricityHour->electricity_hour_number, // رقم ساعة الكهرباء
                'meter_type' => $electricityHour->meter_type, // نوع العداد
                'operating_entity' => $electricityHour->operating_entity, // الجهة المشغلة
                'notes' => $electricityHour->notes ?? 'لا توجد ملاحظات',
                'created_at' => $electricityHour->created_at,
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
            'عدد ساعات الكهرباء',
            'رقم ساعة الكهرباء',
            'نوع العداد',
            'الجهة المشغلة',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'ساعات الكهرباء';
    }
}
