<?php

namespace App\Imports;

use App\Models\WaterQualityTest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WaterQualityTestsImport implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // إزالة الصف الأول (العناوين)
        $rows->shift();

        // تحديد قواعد التحقق من الصحة
        $rules = [
            '*.0' => 'required|date_format:d/m/Y', // _submission_time
            '*.1' => 'required|integer|exists:stations,id', // station_id
            '*.2' => 'nullable|string', // turbidity
            '*.3' => 'nullable|string', // ph_level
            '*.4' => 'nullable|string', // microbial_analysis
            '*.5' => 'nullable|string', // complaints
        ];

        // تنفيذ التحقق من الصحة يدوياً
        $validator = Validator::make($rows->toArray(), $rules);

        if ($validator->fails()) {
            // يمكنك تسجيل الأخطاء أو رمي استثناء
            Log::error('Validation failed during import: ', $validator->errors()->toArray());
            // لرمي استثناء وإيقاف العملية، قم بإلغاء التعليق عن السطر التالي
            // throw ValidationException::withMessages($validator->errors()->toArray());
            return; // إيقاف التنفيذ عند وجود خطأ
        }

        foreach ($rows as $row) {
            // تخطي الصفوف الفارغة بالكامل
            if ($row->filter()->isEmpty()) {
                continue;
            }

            WaterQualityTest::create([
                // الاعتماد على ترتيب الأعمدة (الفهرس)
                'test_date'          => Carbon::createFromFormat('d/m/Y', $row[0])->format('Y-m-d'), // العمود الأول
                'station_id'         => $row[1], // العمود الثاني
                'turbidity'          => $row[2], // العمود الثالث
                'ph_level'           => $row[3], // العمود الرابع
                'microbial_analysis' => $row[4], // العمود الخامس
                'complaints'         => $row[5], // العمود السادس
            ]);
        }
    }
}
