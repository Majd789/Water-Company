<?php

namespace App\Imports;

use App\Models\Station;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StationsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد المحطات
        foreach ($rows as $row) {
            Station::create([
                'station_code' => $row[0],
                'station_name' => $row[1],
                'operational_status' => $row[2],
                'stop_reason' => $row[3] ?? null,
                'energy_source' => $row[4] ?? null,
                'operator_entity' => $row[5] ?? null,
                'operator_name' => $row[6] ?? null,
                'general_notes' => $row[7] ?? null,
                'town_id' => $row[8], // تأكد من أن town_id موجود في العمود الصحيح
                'water_delivery_method' => $row[9] ?? null,
                'network_readiness_percentage' => $row[10] ?? null,
                'network_type' => $row[11] ?? null,
                'beneficiary_families_count' => $row[12] ?? null,
                'has_disinfection' => $row[13] ?? false,
                'disinfection_reason' => $row[14] ?? null,
                'served_locations' => $row[15] ?? null,
                'actual_flow_rate' => $row[16] ?? null,
                'station_type' => $row[17] ?? null,
                'detailed_address' => $row[18] ?? null,
                'land_area' => $row[19] ?? null,
                'soil_type' => $row[20] ?? null,
                'building_notes' => $row[21] ?? null,
                'latitude' => $row[22] ?? null,
                'longitude' => $row[23] ?? null,
                'is_verified' => $row[24] ?? false,
            ]);
        }
    }
    /**
     * قواعد التحقق من البيانات
     */
    public function rules(): array
    {
        return [
            '*.station_code' => 'required|unique:stations,station_code', // التحقق من عدم وجود قيمة مكررة في `station_code`
            '*.station_name' => 'required|string',
            '*.operational_status' => 'required|in:عاملة,متوقفة,خارج الخدمة',
            '*.town_id' => 'required|exists:towns,id', // التحقق من وجود town_id في جدول towns
        ];
    }
    
}
