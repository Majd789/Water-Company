<?php

namespace App\Imports;

use App\Models\ProjectActivity;
use App\Models\Project;
use App\Models\Town;
use App\Models\MasterActivity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class ProjectActivitiesImport implements ToModel, WithStartRow
{
    private $townsCache = [];
    private $activitiesCache = [];

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // 1. كود النشاط
        $activity_code = trim($row[0] ?? '');
        if (empty($activity_code)) return null;

        // 2. كود المشروع
        $projectCode = trim($row[1] ?? '');
        $project = Project::where('project_code', $projectCode)->first();

        // 3. اسم القرية (مع التصحيح)
        $excelTownName = trim($row[5] ?? '');
        $townId = $this->getCorrectTownId($excelTownName);

        if (!$project || !$townId) {
            return null;
        }

        // 4. معالجة النشاط الرئيسي (Master Activity)
        $excelActivityName = trim($row[7] ?? '');

        // تصحيح الاسم ليطابق الموجود في القاعدة
        $finalActivityName = $this->getCorrectActivityName($excelActivityName);

        // تجهيز الواحدة في حال اضطررنا لإنشاء نشاط جديد
        $rawUnit = trim($row[9] ?? '');
        $unitMeasure = ($rawUnit == '_' || empty($rawUnit)) ? 'N/A' : $rawUnit;

        // البحث أو الإنشاء
        $masterActivity = MasterActivity::firstOrCreate(
            ['name' => $finalActivityName], // البحث بالاسم الصحيح
            [
                // القيم التي تضاف فقط إذا كان النشاط جديداً
                'code' => 'MA-' . strtoupper(Str::random(6)),
                'unit' => $unitMeasure
            ]
        );

        // تنظيف الأرقام
        $cost = isset($row[6]) ? str_replace(['$', ','], '', $row[6]) : 0;
        $quantity = ($row[8] == '_' || empty($row[8])) ? 0 : $row[8];
        $unitCapacity = ($row[10] == '_' || empty($row[10])) ? 0 : $row[10];

        // 5. حفظ نشاط المشروع
        return ProjectActivity::updateOrCreate(
            ['activity_code' => $activity_code],
            [
                'project_id'         => $project->id,
                'master_activity_id' => $masterActivity->id,
                'town_id'            => $townId,
                'station_name'       => $row[4] ?? null,
                'quantity'           => floatval($quantity),
                'unit_measure'       => ($rawUnit == '_') ? null : $rawUnit,
                'unit_capacity'      => floatval($unitCapacity),
                'cost'               => floatval($cost),
                'status'             => $row[11] ?? null,
                'notes'              => $row[12] ?? null,
            ]
        );
    }

    /**
     * دالة لتصحيح أسماء الأنشطة لتطابق قاعدة البيانات
     */
    private function getCorrectActivityName($excelName)
    {
        // قائمة الربط: (الاسم في الإكسل) => (الاسم في قاعدة البيانات)
        $mappings = [
            'تزويد كلور'            => 'تزويد كلور صلب', // افتراض، أو يمكنك تغييره لـ تزويد كلور سائل
            'تزويد الكلور'          => 'تزويد كلور صلب',
            'تزويد ديزل'            => 'تزويد ديزل',     // تطابق تام ولكن للاحتياط
            'تزويد الديزل'          => 'تزويد ديزل',
            'انشاء منظومة طاقة'     => 'انشاء منظومة طاقة شمسية',
            'انشاء منظومة طاقة شمسية ' => 'انشاء منظومة طاقة شمسية', // إزالة مسافة زائدة
            'تأهيل محطات المياه'    => 'تأهيل وترميم المحطة مدنيا', // مثال تقريبي
            'صيانة شبكة'            => 'صيانة شبكة المياه',
        ];

        return $mappings[$excelName] ?? $excelName;
    }

    /**
     * دالة تصحيح أسماء البلدات
     */
    private function getCorrectTownId($excelName)
    {
        if (empty($excelName)) return null;

        if (isset($this->townsCache[$excelName])) {
            return $this->townsCache[$excelName];
        }

        $mappings = [
            'الاتارب'             => 'الأتارب',
            'التمانعة الغربية'    => 'التمانعة - الغربية',
            'التمانعه'            => 'التمانعة - الغربية',
            'تلعاس'               => 'تل عاس',
            'معرة مصرين'          => 'معر تمصرين',
            'معرتمصرين الغربية'   => 'معر تمصرين',
            'كفرشلايا'            => 'كفر شلايا',
            'السعدية_بسنديتا'     => 'السعدية_بسندتيا',
            'المرج الأخضر الغربي' => 'المرج الأخضر االغربي (مشمشان)',
            'الخواري'             => 'خوارى',
            'معرة حرمة'           => 'معرتحرمة',
            'كفرتخاريم'           => 'كفر تخاريم',
            'معردبسة'             => 'معردبسي',
            'معردبسة الشرقية'     => 'معردبسي',
            'كفر عويد'            => 'كفرعويد',
            'الدانا-حاس'          => 'حاس',
            'بسيدا'               => 'بسيدا',
        ];

        $searchName = $mappings[$excelName] ?? $excelName;

        $town = Town::where('town_name', $searchName)->orWhere('town_name', 'LIKE', $searchName)->first();

        if (!$town) {
            $town = Town::where(function($query) use ($searchName) {
                if (Str::contains($searchName, 'سلقين')) $query->where('town_name', 'like', '%سلقين%');
                elseif (Str::contains($searchName, 'ارمناز')) $query->where('town_name', 'like', '%أرمناز%');
                elseif (Str::contains($searchName, 'رام حمدان')) $query->where('town_name', 'like', '%رام حمدان%');
                elseif (Str::contains($searchName, 'مرديخ')) $query->where('town_name', 'like', '%مرديخ%');
                elseif (Str::contains($searchName, 'معرشمشة')) $query->where('town_name', 'like', '%معرشمشة%');
            })->first();
        }

        if ($town) {
            $this->townsCache[$excelName] = $town->id;
            return $town->id;
        }

        return null;
    }
}
