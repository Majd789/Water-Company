<?php

namespace App\Imports;

use App\Models\StationReport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StationReportsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة العناوين إذا كانت موجودة
        if ($rows->first() && $this->isHeaderRow($rows->first())) {
            $rows->shift();
        }

        foreach ($rows as $row) {
            // تحويل الكائن إلى مصفوفة وإضافة القيم الافتراضية للعناصر الناقصة
            $row = array_pad($row->toArray(), 44, null);
            StationReport::create([
                'start' => $row[0],
                'end' => $row[1],
                'date' => $row[2],
                'إسم المُشغل المناوب في المنهل' => $row[3],
                'وحدة المياه' => $row[4],
                'البلدة' => $row[5],
                'المحطات' => $row[6],
                'station_code' => $row[7],
                'الوضع التشغيلي' => $row[8],
                'سبب التوقف' => $row[9],
                'operator_entity' => $row[10],
                'operator_company' => $row[11],
                'operating_wells_count' => (double) $row[12],
              
                'well_1_hours' => is_numeric(trim($row[13])) ? (double) trim($row[13]) : null,
                'well_2_hours' => is_numeric(trim($row[14])) ? (double) trim($row[14]) : null,
                'well_3_hours' => is_numeric(trim($row[15])) ? (double) trim($row[15]) : null,
                'well_4_hours' => is_numeric(trim($row[16])) ? (double) trim($row[16]) : null,
                'well_5_hours' => is_numeric(trim($row[17])) ? (double) trim($row[17]) : null,
                'well_6_hours' => is_numeric(trim($row[18])) ? (double) trim($row[18]) : null,
                'well_7_hours' => is_numeric(trim($row[19])) ? (double) trim($row[19]) : null,

                'total_well_hours' => (double) $row[20],
                'has_horizontal_pump' => $this->convertBoolean($row[21]),
                'horizontal_pump_hours' => (double) $row[22],
                'station_operation_method' => $row[23],
                'target_sector' => $row[24],
                'has_disinfection' => $this->convertBoolean($row[25]),
                'no_disinfection_reason' => $row[26],
                'energy_source' => $row[27],
                'solar_electricity_hours' => (double) $row[28],
                'solar_generator_hours' => (double) $row[29],
                'solar_only_hours' => (double) $row[30],
                'electricity_hours' => (double) $row[31],
                'electricity_consumption_kwh' => (double) $row[32],
                'electric_meter_before' => (double) $row[33],
                'electric_meter_after' => (double) $row[34],
                'generator_hours' => (double) $row[35],
                'diesel_consumption' => (double) $row[36],
                'oil_replacement' => $this->convertBoolean($row[37]),
                'oil_quantity' => (double) $row[38],
                'water_pumped_m3' => (double) $row[39],
                'total_diesel_stock' => (double) $row[40],
                'diesel_received' => $this->convertBoolean($row[41]),
                'new_diesel_quantity' => (double) $row[42],
                'diesel_provider' => $row[43],
                'station_modification' => $this->convertBoolean($row[44]),
                'modification_location' => $row[45],
                'modification_details' => $row[46],
                'transfer_destination' => $row[47],
                'electric_meter_charged' => $this->convertBoolean($row[48]),
                'charged_electricity_kwh' => (double) $row[49],
                'operator_notes' => $row[50],
            ]);
            
        }
    }

    /**
     * التحقق مما إذا كان الصف الأول يحتوي على العناوين
     */
    private function isHeaderRow($row)
    {
        return is_string($row[0]) && str_contains($row[0], 'start');
    }

    /**
     * تحويل القيم النصية إلى قيم منطقية (boolean) أو null
     */
    private function convertBoolean($value)
    {
        if (in_array($value, ['يوجد', 'نعم'])) {
            return true;
        } elseif (in_array($value, ['لا يوجد', 'لا'])) {
            return false;
        }
        return null;
    }
}
