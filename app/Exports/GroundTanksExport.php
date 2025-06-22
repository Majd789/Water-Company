<?php


namespace App\Exports;

use App\Models\GroundTank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class GroundTanksExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب الخزانات الأرضية المرتبطة بهذه المحطات فقط
            $tanks = GroundTank::whereIn('station_id', $stations)->with('station.town.unit.governorate')->get();
        } else {
            // جلب جميع الخزانات الأرضية إذا لم يكن هناك وحدة مرتبطة
            $tanks = GroundTank::with('station.town.unit.governorate')->get();
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
                'building_entity' => $tank->building_entity,
                'construction_type' => $tank->construction_type, // قديم أو جديد
                'capacity' => $tank->capacity,
                'readiness_percentage' => $tank->readiness_percentage,
                'feeding_station' => $tank->feeding_station,
                'town_supply' => $tank->town_supply,
                'pipe_diameter_inside' => $tank->pipe_diameter_inside,
                'pipe_diameter_outside' => $tank->pipe_diameter_outside,
                'latitude' => $tank->latitude ?? 'غير متوفر',
                'longitude' => $tank->longitude ?? 'غير متوفر',
                'altitude' => $tank->altitude ?? 'غير متوفر',
                'precision' => $tank->precision ?? 'غير متوفر',
              
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
            'الجهة المنفذة',
            'نوع البناء',
            'السعة (م³)',
            'نسبة الجاهزية',
            'المحطة المغذية',
            'البلدة المزودة',
            'قطر الأنبوب الداخلي',
            'قطر الأنبوب الخارجي',
            'خط العرض',
            'خط الطول',
            'الارتفاع',
            'دقة الموقع',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'معلومات الخزانات الأرضية';
    }
}
