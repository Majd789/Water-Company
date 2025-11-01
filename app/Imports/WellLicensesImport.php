<?php

namespace App\Imports;

use App\Models\Station; // <-- تم الاستدعاء
use App\Models\WellLicense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class WellLicensesImport implements ToModel, WithValidation, WithStartRow, WithCustomCsvSettings
{
    /**
     * @var array
     */
    private $stationsCache; // متغير لتخزين المحطات وتجنب الاستعلامات المتكررة

    /**
     * WellLicensesImport constructor.
     */
    public function __construct()
    {
        // تحسين الأداء: جلب كل المحطات مرة واحدة وتخزينها في مصفوفة بحث سريعة.
        // نستخدم pluck لإنشاء مصفوفة بالشكل: ['اسم المحطة' => id]
        $this->stationsCache = Station::pluck('id', 'station_name')->all();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // --- بداية منطق البحث عن المحطة بالاسم ---
        $stationName = $row[11] ?? null; // اقرأ اسم المحطة من العمود L
        $stationId = null;

        // ابحث عن اسم المحطة في المصفوفة التي قمنا بتخزينها في الذاكرة
        if (!empty($stationName) && isset($this->stationsCache[$stationName])) {
            $stationId = $this->stationsCache[$stationName];
        }
        // --- نهاية منطق البحث عن المحطة ---

        // الآن نستخدم الفهرس الرقمي (index) بدلاً من اسم العمود
        return WellLicense::updateOrCreate(
            ['archive_code' => $row[1]], // البحث بناءً على كود الأرشفة (العمود B)
            [
                'property_number' => $row[2],
                'property_zone' => $row[3],
                'applicant_name' => $row[4],
                'request_type' => $row[5],
                'institution_letter_date' => !empty($row[6]) ? Carbon::createFromFormat('d/m/Y', $row[6])->toDateString() : null,
                'directorate_letter_number' => $row[7],
                'directorate_letter_date' => !empty($row[8]) ? Carbon::createFromFormat('d/m/Y', $row[8])->toDateString() : null,
                'declared_distance_meters' => $row[9],

                'station_id' => $stationId, // <-- استخدام الـ ID الذي وجدناه

                'latitude' => $row[12],
                'longitude' => $row[13],
                'physical_cabinet' => $row[14],
                'physical_shelf' => $row[15],
                'physical_file_id' => $row[16],
                'notes' => $row[17],
            ]
        );
    }

    /**
     * تحديد الصف الذي سيبدأ منه الاستيراد (تجاهل الصف الأول).
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * قواعد التحقق من الصحة باستخدام الفهرس الرقمي
     */
    public function rules(): array
    {
        return [
            '*.1' => 'required|string|max:255', // كود الأرشفة
            '*.2' => 'required', // رقم العقار
            '*.3' => 'required|string', // المنطقة العقارية
            '*.4' => 'required|string', // اسم مقدم الطلب
            '*.5' => 'required|string|in:حفر,تجديد,تسوية', // نوع الطلب

            // **تعديل القاعدة:** التحقق من أن اسم المحطة (إذا وُجد) موجود في جدول المحطات
            '*.11' => 'nullable|string|exists:stations,station_name',
        ];
    }

    /**
     * تخصيص أسماء الأعمدة في رسائل الأخطاء
     */
    public function customValidationAttributes()
    {
        return [
            '*.1' => 'كود الأرشفة (العمود B)',
            '*.2' => 'رقم العقار (العمود C)',
            '*.3' => 'المنطقة العقارية (العمود D)',
            '*.4' => 'اسم مقدم الطلب (العمود E)',
            '*.5' => 'نوع الطلب (العمود F)',

            // **تعديل السمة:** الإشارة إلى العمود الصحيح في رسالة الخطأ
            '*.11' => 'اسم أقرب محطة (العمود L)',
        ];
    }

    /**
     * إعدادات CSV (لتجاهل BOM وضمان قراءة UTF-8)
     */
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8'
        ];
    }
}
