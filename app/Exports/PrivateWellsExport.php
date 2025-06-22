<?php

namespace App\Exports;

use App\Models\PrivateWell;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class PrivateWellsExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات الآبار الخاصة المرتبطة بهذه المحطات فقط
            $wells = PrivateWell::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات الآبار الخاصة إذا لم يكن هناك وحدة مرتبطة
            $wells = PrivateWell::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $wells->map(function ($well) {
            return [
                'id' => $well->id,
                'governorate_name' => $well->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $well->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $well->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $well->station->station_name ?? 'غير معروف', // اسم المحطة
                'well_name' => $well->well_name, // اسم البئر الخاص
                'well_count' => $well->well_count, // عدد الآبار
                'distance_from_nearest_well' => $well->distance_from_nearest_well, // بعده عن أقرب بئر
                'well_type' => $well->well_type, // نوع عمل البئر
                'created_at' => $well->created_at,
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
            'اسم البئر الخاص',
            'عدد الآبار',
            'بعده عن أقرب بئر',
            'نوع عمل البئر',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'الآبار الخاصة';
    }
}
