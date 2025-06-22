<?php

namespace App\Exports;

use App\Models\Well;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Town;    // Added for clarity, though already aliased in current code
use App\Models\Station; // Added for clarity

class WellsExport implements FromCollection, WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            $towns = Town::where('unit_id', $userUnitId)->pluck('id');
            $stations = Station::whereIn('town_id', $towns)->pluck('id');

            return Well::whereIn('station_id', $stations)
                ->with([
                    'station:id,station_name,town_id,station_code', // Added station_code here
                    'station.town:id,town_name,unit_id',
                    'station.town.unit:id,unit_name,governorate_id',
                    'station.town.unit.governorate:id,name',
                ])
                ->get()
                ->map(function ($well) {
                    return [
                        'id' => $well->id,
                        'governorate_name' => $well->station->town->unit->governorate->name ?? 'غير محددة',
                        'unit_name' => $well->station->town->unit->unit_name ?? 'غير محددة',
                        'station_name' => $well->station->station_name ?? 'غير معروف',
                        'station_code' => $well->station->station_code ?? 'غير محدد', // Added station_code here
                        'town_code' => $well->town_code,
                        'well_name' => $well->well_name,
                        'well_status' => $well->well_status,
                        'stop_reason' => $well->stop_reason,
                        'distance_from_station' => $well->distance_from_station,
                        'well_type' => $well->well_type,
                        'well_flow' => $well->well_flow,
                        'static_depth' => $well->static_depth,
                        'dynamic_depth' => $well->dynamic_depth,
                        'drilling_depth' => $well->drilling_depth,
                        'well_diameter' => $well->well_diameter,
                        'pump_installation_depth' => $well->pump_installation_depth,
                        'pump_capacity' => $well->pump_capacity,
                        'actual_pump_flow' => $well->actual_pump_flow,
                        'pump_lifting' => $well->pump_lifting,
                        'pump_brand_model' => $well->pump_brand_model,
                        'energy_source' => $well->energy_source,
                        'well_address' => $well->well_address,
                        'general_notes' => $well->general_notes,
                        'well_location' => $well->well_location,
                        'created_at' => $well->created_at,
                        'updated_at' => $well->updated_at,
                    ];
                });
        }

        // This block will be executed if $userUnitId is null (e.g., admin user)
        return Well::with([
            'station:id,station_name,town_id,station_code', // Added station_code here
            'station.town:id,town_name,unit_id',
            'station.town.unit:id,unit_name,governorate_id',
            'station.town.unit.governorate:id,name',
        ])
            ->get()
            ->map(function ($well) {
                return [
                    'id' => $well->id,
                    'governorate_name' => $well->station->town->unit->governorate->name ?? 'غير محددة',
                    'unit_name' => $well->station->town->unit->unit_name ?? 'غير محددة',
                    'station_name' => $well->station->station_name ?? 'غير معروف',
                    'station_code' => $well->station->station_code ?? 'غير محدد', // Added station_code here
                    'town_code' => $well->town_code,
                    'well_name' => $well->well_name,
                    'well_status' => $well->well_status,
                    'stop_reason' => $well->stop_reason,
                    'distance_from_station' => $well->distance_from_station,
                    'well_type' => $well->well_type,
                    'well_flow' => $well->well_flow,
                    'static_depth' => $well->static_depth,
                    'dynamic_depth' => $well->dynamic_depth,
                    'drilling_depth' => $well->drilling_depth,
                    'well_diameter' => $well->well_diameter,
                    'pump_installation_depth' => $well->pump_installation_depth,
                    'pump_capacity' => $well->pump_capacity,
                    'actual_pump_flow' => $well->actual_pump_flow,
                    'pump_lifting' => $well->pump_lifting,
                    'pump_brand_model' => $well->pump_brand_model,
                    'energy_source' => $well->energy_source,
                    'well_address' => $well->well_address,
                    'general_notes' => $well->general_notes,
                    'well_location' => $well->well_location,
                    'created_at' => $well->created_at,
                    'updated_at' => $well->updated_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'id',
            'اسم المحافظة',
            'اسم الوحدة',
            'اسم المحطة',
            'كود المحطة', // Added "Station Code" heading
            'كود البلدة',
            'اسم البئر',
            'الوضع التشغيلي',
            'سبب التوقف',
            'المسافة من المحطة',
            'نوع البئر',
            'تدفق البئر',
            'العمق الساكن',
            'العمق الديناميكي',
            'عمق الحفر',
            'قطر البئر',
            'عمق تركيب المضخة',
            'استطاعة المضخة',
            'التدفق الفعلي للمضخة',
            'رفع المضخة',
            'ماركة ونموذج المضخة',
            'مصدر الطاقة',
            'عنوان البئر',
            'ملاحظات عامة',
            'موقع البئر',
            'تاريخ الإنشاء',
            'تاريخ التحديث'
        ];
    }

    public function title(): string
    {
        return 'الآبار';
    }
}