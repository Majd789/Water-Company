<?php

namespace App\Exports;

use App\Models\PumpingSector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class PumpingSectorsExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب قطاعات الضخ المرتبطة بهذه المحطات فقط
            $sectors = PumpingSector::whereIn('station_id', $stations)
                ->with(['station', 'town','station.town.unit.governorate']) // جلب بيانات المحطة والبلدة
                ->get();
        } else {
            // جلب جميع قطاعات الضخ إذا لم يكن هناك وحدة مرتبطة
            $sectors = PumpingSector::with(['station', 'town','station.town.unit.governorate'])->get();
        }

        // تعديل البيانات قبل تصديرها
        return $sectors->map(function ($sector) {
            return [
                'id' => $sector->id,
                'governorate_name' => $sector->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $sector->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $sector->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $sector->station->station_name ?? 'غير معروف', // اسم المحطة
                'sector_name' => $sector->sector_name, // اسم القطاع
                'town_name' => $sector->town->town_name ?? 'غير معروف', // اسم البلدة
                'notes' => $sector->notes ?? 'لا توجد ملاحظات',
                'created_at' => $sector->created_at,
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
            'اسم القطاع',
            'البلدة',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'قطاعات الضخ';
    }
}
