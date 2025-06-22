<?php

namespace App\Imports;

use App\Models\WaterWell;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class WaterWellImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        // استيراد الآبار
        foreach ($rows as $row) {
            // التأكد من أن قيمة has_flow_meter هي "نعم" أو "لا"
            $has_flow_meter = in_array($row[2], ['نعم', 'لا']) ? $row[2] : 'نعم';
        
            // التأكد من أن قيمة flow_meter_start هي عدد صحيح أو صفر إذا كانت غير صالحة
            $flow_meter_start = is_numeric($row[3]) ? $row[3] : 0;
            $flow_meter_end = is_numeric($row[4]) ? $row[4] : 0;
            $water_sold_quantity = is_numeric($row[5]) ? $row[5] : 0;
            $water_price = is_numeric($row[6]) ? $row[6] : 0;
            $total_amount = is_numeric($row[7]) ? $row[7] : 0;
            $vehicle_filling_quantity = is_numeric($row[9]) ? $row[9] : 0;
            $free_filling_quantity = is_numeric($row[11]) ? $row[11] : 0;
        
            WaterWell::create([
                'station_code' => $row[0],
                'well_name' => $row[1],
                'has_flow_meter' => $has_flow_meter,
                'flow_meter_start' => $flow_meter_start,
                'flow_meter_end' => $flow_meter_end,
                'water_sold_quantity' => $water_sold_quantity,
                'water_price' => $water_price,
                'total_amount' => $total_amount,
                'has_vehicle_filling' => $row[8], // تأكد من أن هذه الخانة تحتوي على القيمة الصحيحة
                'vehicle_filling_quantity' => $vehicle_filling_quantity,
                'has_free_filling' => $row[10], // تأكد من أن هذه الخانة تحتوي على القيمة الصحيحة
                'free_filling_quantity' => $free_filling_quantity,
                'entity_for_free_filling' => $row[12],
                'document_number' => $row[13],
                'notes' => $row[14],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.station_code' => 'required|exists:stations,code', // التحقق من وجود `station_code` في جدول المحطات
            '*.well_name' => 'required|string', // التحقق من وجود اسم البئر
            '*.has_flow_meter' => 'nullable|in:نعم,لا', // التحقق من وجود قيمة صالحة لـ has_flow_meter
            '*.flow_meter_start' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ flow_meter_start
            '*.flow_meter_end' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ flow_meter_end
            '*.water_sold_quantity' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ water_sold_quantity
            '*.water_price' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ water_price
            '*.total_amount' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ total_amount
            '*.has_vehicle_filling' => 'nullable|in:نعم,لا', // التحقق من وجود قيمة صالحة لـ has_vehicle_filling
            '*.vehicle_filling_quantity' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ vehicle_filling_quantity
            '*.has_free_filling' => 'nullable|in:نعم,لا', // التحقق من وجود قيمة صالحة لـ has_free_filling
            '*.free_filling_quantity' => 'nullable|numeric', // التحقق من وجود قيمة صالحة لـ free_filling_quantity
            '*.entity_for_free_filling' => 'nullable|string', // التحقق من وجود قيمة صالحة لـ entity_for_free_filling
            '*.document_number' => 'nullable|string', // التحقق من وجود قيمة صالحة لـ document_number
        ];
    }
}
