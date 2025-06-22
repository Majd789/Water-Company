<?php

namespace App\Exports;

use App\Models\SolarEnergy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class SolarEnergiesExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات الطاقة الشمسية المرتبطة بهذه المحطات فقط
            $solarEnergies = SolarEnergy::whereIn('station_id', $stations)
                ->with(['station.town.unit.governorate']) // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات الطاقة الشمسية إذا لم يكن هناك وحدة مرتبطة
            $solarEnergies = SolarEnergy::with(['station.town.unit.governorate'])->get();
        }

        // تعديل البيانات قبل تصديرها
        return $solarEnergies->map(function ($solarEnergy) {
            return [
                'ID' => $solarEnergy->id,
                'governorate_name' => $solarEnergy->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $solarEnergy->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $solarEnergy->station->station_code ?? 'غير معروف', // كود المحطة
                'station_name' => $solarEnergy->station->station_name ?? 'غير معروف', // اسم المحطة
                'panel_size' => $solarEnergy->panel_size, // قياس اللوح
                'panel_count' => $solarEnergy->panel_count, // عدد الألواح
                'manufacturer' => $solarEnergy->manufacturer, // الجهة المنشئة
                'base_type' => $solarEnergy->base_type, // نوع القاعدة
                'technical_condition' => $solarEnergy->technical_condition, // الحالة الفنية
                'wells_supplied_count' => $solarEnergy->wells_supplied_count, // عدد الآبار المغذاة
                'general_notes' => $solarEnergy->general_notes, // الملاحظات
                'latitude' => $solarEnergy->latitude, // خط العرض
                'longitude' => $solarEnergy->longitude, // خط الطول
                'created_at' => $solarEnergy->created_at,
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
            'قياس اللوح (متر مربع)',
            'عدد الألواح',
            'الجهة المنشئة',
            'نوع القاعدة',
            'الحالة الفنية',
            'عدد الآبار المغذاة',
            'الملاحظات',
            'خط العرض',
            'خط الطول',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'الطاقة الشمسية';
    }
}
