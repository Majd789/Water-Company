<?php

namespace App\Exports;

use App\Models\Filter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class FiltersExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات المرشحات المرتبطة بهذه المحطات فقط
            $filters = Filter::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات المرشحات إذا لم يكن هناك وحدة مرتبطة
            $filters = Filter::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $filters->map(function ($filter) {
            return [
                'ID' => $filter->id,
                'governorate_name' => $filter->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $filter->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $filter->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $filter->station->station_name ?? 'غير معروف', // اسم المحطة
                'filter_capacity' => $filter->filter_capacity, // استطاعة المرشح
                'readiness_status' => $filter->readiness_status, // جاهزية المرشح
                'filter_type' => $filter->filter_type, // نوع المرشح
                'created_at' => $filter->created_at,
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
            'استطاعة المرشح',
            'جاهزية المرشح',
            'نوع المرشح',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'مرشحات المياه';
    }
}
