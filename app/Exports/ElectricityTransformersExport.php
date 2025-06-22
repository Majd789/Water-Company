<?php

namespace App\Exports;

use App\Models\ElectricityTransformer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class ElectricityTransformersExport implements FromCollection, WithHeadings , WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            // جلب البلدات المرتبطة بالوحدة
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');

            // جلب المحطات المرتبطة بهذه البلدات
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');

            // جلب بيانات المحولات الكهربائية المرتبطة بهذه المحطات فقط
            $transformers = ElectricityTransformer::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate') // جلب بيانات المحطة المرتبطة
                ->get();
        } else {
            // جلب جميع بيانات المحولات الكهربائية إذا لم يكن هناك وحدة مرتبطة
            $transformers = ElectricityTransformer::with('station.town.unit.governorate')->get();
        }

        // تعديل البيانات قبل تصديرها
        return $transformers->map(function ($transformer) {
            return [
                'id' => $transformer->id,
                'governorate_name' => $transformer->station->town->unit->governorate->name ?? 'غير محددة', // اسم المحافظة
                'unit_name' => $transformer->station->town->unit->unit_name ?? 'غير محددة', // اسم الوحدة
                'station_code' => $transformer->station->station_code ?? 'غير معروف', // ✅ كود المحطة
                'station_name' => $transformer->station->station_name ?? 'غير معروف', // اسم المحطة
                'operational_status' => $transformer->operational_status, // الوضع التشغيلي
                'transformer_capacity' => $transformer->transformer_capacity, // استطاعة المحولة
                'distance_from_station' => $transformer->distance_from_station, // بعد المحولة عن المحطة
                'is_station_transformer' => $transformer->is_station_transformer ? 'نعم' : 'لا', // هل المحولة خاصة بالمحطة
                'talk_about_station_transformer' => $transformer->talk_about_station_transformer ?? 'لا يوجد', // وصف المحولة
                'is_capacity_sufficient' => $transformer->is_capacity_sufficient ? 'نعم' : 'لا', // هل الاستطاعة كافية
                'how_much_capacity_need' => $transformer->how_mush_capacity_need, // كم الاستطاعة المحتاجة
                'notes' => $transformer->notes ?? 'لا توجد ملاحظات',
                'created_at' => $transformer->created_at,
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
            'الوضع التشغيلي',
            'استطاعة المحولة',
            'بعد المحولة عن المحطة',
            'هل المحولة خاصة بالمحطة',
            'وصف المحولة',
            'هل الاستطاعة كافية',
            'كم الاستطاعة المحتاجة',
            'الملاحظات',
            'تاريخ الإنشاء',
        ];
    }
    public function title(): string
    {
        return 'محولات الكهرباء';
    }
}
