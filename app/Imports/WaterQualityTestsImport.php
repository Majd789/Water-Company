<?php

namespace App\Imports;

use App\Models\WaterQualityTest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WaterQualityTestsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // تحقق إذا كان station_id موجوداً وقيمته ليست فارغة
            if (!isset($row['station_id']) || empty($row['station_id'])) {
                Log::warning('Skipping row due to empty station_id.');
                continue; // تجاهل الصف إذا كان station_id فارغاً
            }

            // معالجة التاريخ بشكل آمن
            try {
                // Carbon يتوقع صيغة Y-m-d أو m/d/Y, ملفك يستخدم d/m/Y
                $testDate = Carbon::createFromFormat('d/m/Y', $row['_submission_time'])->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error('Invalid date format for station_id ' . $row['station_id'] . ': ' . $row['_submission_time']);
                continue; // تجاهل الصف إذا كان التاريخ غير صالح
            }

            WaterQualityTest::create([
                'station_id'         => $row['station_id'],
                'test_date'          => $testDate,
                'turbidity'          => $row['ما_هي_آخر_درجة_أو_نتيجة_مسجلة_لجودة_المياه_العكارة'],
                'ph_level'           => $row['ما_هي_آخر_درجة_أو_نتيجة_مسجلة_لجودة_المياه_الرقم_الهيدروجيني'],
                'microbial_analysis' => $row['ما_هي_آخر_درجة_أو_نتيجة_مسجلة_لجودة_المياه_التحليل_الجرثومي'],
                'complaints'         => $row['اذا_كان_نعم_صفها'],
            ]);
        }
    }

    /**
     * تحديد حجم الدفعة
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
