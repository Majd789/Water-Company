<?php

namespace App\Exports;

use App\Models\DieselTank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class DieselTanksExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات خزانات الديزل المرتبطة بهذه المحطات فقط
            $dieselTanks = DieselTank::whereIn('station_id', $stations)
                ->with(['station.town.unit.governorate']) // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات خزانات الديزل إذا لم يكن هناك وحدة مرتبطة
            $dieselTanks = DieselTank::with(['station.town.unit.governorate'])->get();
        }

        // تعديل البيانات قبل تصديرها
        return $dieselTanks->map(function ($dieselTank) {
            return [
                'id' => $dieselTank->id,
                'governorate_name' => $dieselTank->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $dieselTank->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $dieselTank->station->station_code ?? 'غير معروف', // كود المحطة
                'station_name' => $dieselTank->station->station_name ?? 'غير معروف', // اسم المحطة
                'tank_name' => $dieselTank->tank_name, // اسم الخزان
                'tank_capacity' => $dieselTank->tank_capacity, // سعة الخزان
                'readiness_percentage' => $dieselTank->readiness_percentage, // نسبة الجاهزية
                'type' => $dieselTank->type, // نوع الخزان
                'general_notes' => $dieselTank->general_notes, // الملاحظات
                'created_at' => $dieselTank->created_at,
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
            'سعة الخزان (لتر)',
            'نسبة الجاهزية (%)',
            'نوع الخزان',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'خزانات الديزل';
    }
}
