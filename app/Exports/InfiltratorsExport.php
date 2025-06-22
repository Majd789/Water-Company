<?php

namespace App\Exports;

use App\Models\Infiltrator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class InfiltratorsExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات الانفلترات المرتبطة بهذه المحطات فقط
            $infiltrators = Infiltrator::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات الانفلترات إذا لم يكن هناك وحدة مرتبطة
            $infiltrators = Infiltrator::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $infiltrators->map(function ($infiltrator) {
            return [
                'id' => $infiltrator->id,
                'governorate_name' => $infiltrator->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $infiltrator->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $infiltrator->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $infiltrator->station->station_name ?? 'غير معروف', // اسم المحطة
                'infiltrator_capacity' => $infiltrator->infiltrator_capacity, // استطاعة الانفلتر
                'readiness_status' => $infiltrator->readiness_status, // جاهزية الانفلتر
                'infiltrator_type' => $infiltrator->infiltrator_type, // نوع الانفلتر
                'notes' => $infiltrator->notes, // ملاحظات
                'created_at' => $infiltrator->created_at,
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
            'استطاعة الانفلتر',
            'جاهزية الانفلتر',
            'نوع الانفلتر',
            'ملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return ' الانفلترات';
    }
}
