<?php

namespace App\Imports;

use App\Models\WaterWell2;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class WaterWell2Import implements ToCollection
{
    public function collection(Collection $rows)
    {
        // إزالة السطر الأول (العناوين)
        $rows->shift();

        foreach ($rows as $row) {
            // تحويل الكائن إلى مصفوفة وتعبئتها لتحتوي على 25 عنصراً (بحسب ترتيب الأعمدة المتوقع)
            // إذا كان الصف يحتوي على أعمدة أقل يتم تعبئتها بقيم فارغة
            $row = array_pad($row->toArray(), 25, '');

            /*
             * ترتيب الأعمدة في ملف الإكسل هو كما يلي:
             *  0: start
             *  1: end
             *  2: date
             *  3: إسم المُشغل المناوب في المنهل
             *  4: وحدة المياه
             *  5: البلدة
             *  6: المحطات
             *  7: كود المحطة
             *  8: أسم المنهل
             *  9: الوضع التشغيلي
             * 10: سبب التوقف
             * 11: هل يوجد عداد غزارة على المنهل
             * 12: رقم بداية عداد الغزارة للمنهل
             * 13: رقم نهاية عداد الغزارة للمنهل
             * 14: كمية المياه المباعة على المنهل (مترمكعب)
             * 15: سعر المتر على المنهل
             * 16: المبلغ ( باليرة التركية )من المياه المباعة على المنهل
             * 17: المبلغ ( $ )من المياه المباعة على المنهل
             * 18: هل يوجد تعبئة ماء لأليات المؤسسة العامة
             * 19: كمية المياه التي تم تعبئتها لأليات المؤسسة (متر مكعب )
             * 20: هل يوجد تعبئة ماء مجانية
             * 21: كمية المياه المجانية التي تم تعبئتها (متر مكعب )
             * 22: أسم الجهة التي تم تعبئة الماء المجاني لها
             * 23: رقم الكتاب
             * 24: ملاحظات مدخل  البيانات
             */

            // دالة تحويل المؤشرات لتوحيد القيم إلى "نعم" أو "لا"
            $convertIndicator = function ($value) {
                $value = trim($value);
                if ($value === 'يوجد') {
                    return 'نعم';
                } elseif ($value === 'لا يوجد') {
                    return 'لا';
                } elseif (in_array($value, ['نعم', 'لا'])) {
                    return $value;
                }
                return 'لا';
            };

            // تحويل قيم المؤشرات
            $has_flow_meter      = $convertIndicator($row[11]);
            $has_vehicle_filling = $convertIndicator($row[18]);
            $has_free_filling    = $convertIndicator($row[20]);

            // التحقق من القيم الرقمية وإعطاء قيمة افتراضية عند الحاجة
            $flow_meter_start      = is_numeric($row[12]) ? $row[12] : 0;
            $flow_meter_end        = is_numeric($row[13]) ? $row[13] : 0;
            $water_sold_quantity   = is_numeric($row[14]) ? $row[14] : 0;
            $water_price           = is_numeric($row[15]) ? $row[15] : 0;
            $total_amount          = is_numeric($row[16]) ? $row[16] : 0;
            $sold_amount_in_dollar = is_numeric($row[17]) ? $row[17] : 0;
            $vehicle_filling_quantity = is_numeric($row[19]) ? $row[19] : 0;
            $free_filling_quantity    = is_numeric($row[21]) ? $row[21] : 0;

            // تأكد من وجود قيمة في "كود المحطة" (station_code)
            $station_code = trim($row[7]);
            if (empty($station_code)) {
                // يمكنك تسجيل رسالة خطأ أو تخطي الصف
                continue;
            }

            WaterWell2::create([
                'start'                               => $row[0],
                'end'                                 => $row[1],
                'date'                            => $row[2],
                'إسم المُشغل المناوب في المنهل'      => $row[3],
                'وحدة المياه'                        => $row[4],
                'البلدة'                             => $row[5],
                'المحطات'                            => $row[6],
                'station_code'                        => $station_code,
                'well_name'                           => $row[8],
                'الوضع التشغيلي'                     => $row[9],
                'سبب التوقف'                          => $row[10],
                'has_flow_meter'                      => $has_flow_meter,
                'flow_meter_start'                    => $flow_meter_start,
                'flow_meter_end'                      => $flow_meter_end,
                'water_sold_quantity'                 => $water_sold_quantity,
                'water_price'                         => $water_price,
                'total_amount'                        => $total_amount,
                'المبلغ ( $ )من المياه المباعة على المنهل' => $sold_amount_in_dollar,
                'has_vehicle_filling'                 => $has_vehicle_filling,
                'vehicle_filling_quantity'            => $vehicle_filling_quantity,
                'has_free_filling'                    => $has_free_filling,
                'free_filling_quantity'               => $free_filling_quantity,
                'entity_for_free_filling'             => $row[22],
                'document_number'                     => $row[23],
                'notes'                               => $row[24],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.start' => 'nullable|string',
            '*.end' => 'nullable|string',
            '*.date' => 'nullable|date', // تأكد من تنسيق date المناسب
            '*.إسم المُشغل المناوب في المنهل' => 'nullable|string',
            '*.وحدة المياه' => 'nullable|string',
            '*.البلدة' => 'nullable|string',
            '*.المحطات' => 'nullable|string',
            '*.station_code' => 'required|exists:stations,code',
            '*.well_name' => 'required|string',
            '*.الوضع التشغيلي' => 'nullable|string',
            '*.سبب التوقف' => 'nullable|string',
            '*.has_flow_meter' => 'required|in:نعم,لا',
            '*.flow_meter_start' => 'nullable|numeric',
            '*.flow_meter_end' => 'nullable|numeric',
            '*.water_sold_quantity' => 'required|numeric',
            '*.water_price' => 'required|numeric',
            '*.total_amount' => 'required|numeric',
            '*.المبلغ ( $ )من المياه المباعة على المنهل' => 'nullable|numeric',
            '*.has_vehicle_filling' => 'required|in:نعم,لا',
            '*.vehicle_filling_quantity' => 'nullable|numeric',
            '*.has_free_filling' => 'required|in:نعم,لا',
            '*.free_filling_quantity' => 'nullable|numeric',
            '*.entity_for_free_filling' => 'nullable|string',
            '*.document_number' => 'nullable|string',
            '*.notes' => 'nullable|string',
        ];
    }
}
