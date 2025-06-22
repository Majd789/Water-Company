<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivitiesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $userId;
    protected $modelName;

    public function __construct($userId = null, $modelName = null)
    {
        $this->userId = $userId;
        $this->modelName = $modelName;
    }

    public function query()
    {
        $query = Activity::query()->with('causer');

        if ($this->userId) {
            $query->where('causer_id', $this->userId);
        }

        if ($this->modelName) {
            $query->where('subject_type', 'like', '%' . $this->modelName);
        }

        return $query->latest(); // Ensure the same order as in the table
    }

    public function headings(): array
    {
        return [
            'المستخدم',
            'الحدث',
            'الموديل',
            'رقم العنصر',
            'التاريخ',
            'تفاصيل التغيير (قبل)',
            'تفاصيل التغيير (بعد)',
        ];
    }

    public function map($activity): array
    {
        $oldProperties = isset($activity->properties['old']) ? json_encode($activity->properties['old'], JSON_UNESCAPED_UNICODE) : '';
        $newProperties = isset($activity->properties['attributes']) ? json_encode($activity->properties['attributes'], JSON_UNESCAPED_UNICODE) : '';

        return [
            $activity->causer ? $activity->causer->name : 'غير معروف',
            ucfirst($activity->description),
            class_basename($activity->subject_type),
            $activity->subject_id,
            $activity->created_at->format('Y-m-d H:i'),
            $oldProperties,
            $newProperties,
        ];
    }
}