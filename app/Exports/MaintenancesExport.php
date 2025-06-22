<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithTitle;

class MaintenancesExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        $userUnitId = Auth::user()->unit_id;

        if ($userUnitId) {
            $towns = \App\Models\Town::where('unit_id', $userUnitId)->pluck('id');
            $stations = \App\Models\Station::whereIn('town_id', $towns)->pluck('id');
            $maintenances = Maintenance::whereIn('station_id', $stations)
                ->with('station.town.unit.governorate')
                ->get();
        } else {
            $maintenances = Maintenance::with('station.town.unit.governorate')->get();
        }

        return $maintenances->map(function ($maintenance) {
            return [
                'id' => $maintenance->id,
                'governorate_name' => $maintenance->station->town->unit->governorate->name ,
                'unit_name' => $maintenance->station->town->unit->unit_name ,
                'station_code' => $maintenance->station->station_code ,
                'station_name' => $maintenance->station->station_name,
                'maintenance_type' => $maintenance->maintenance_type,
                'total_parts' => $maintenance->total_parts,
                'execution_sites' => $maintenance->execution_sites,
                'total_cost' => $maintenance->total_cost,
                'contractor_name' => $maintenance->contractor_name  ,
                'technician_name' => $maintenance->technician_name ,
                'status' => $maintenance->status ,
                'maintenance_date' => $maintenance->maintenance_date ,
                'maintenance_details' => $maintenance->maintenance_details ,
                'notes' => $maintenance->notes,
    
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'اسم المحافظة',
            'اسم الوحدة',
            'كود المحطة',
            'اسم المحطة',
            'نوع الصيانة',
            'العدد الإجمالي للقطع',
            'مواقع التنفيذ',
            'الكلفة الإجمالية',
            'اسم المقاول',
            'اسم الفني',
            'حالة الصيانة',
            'تاريخ الصيانة',
            'تفاصيل الصيانة',
            'ملاحظات',
            
        ];
    }
    
    public function title(): string
    {
        return 'الصيانات';
    }
}