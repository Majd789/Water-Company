<?php

namespace App\Exports;

use App\Models\ProjectActivity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectActivitiesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    // نستقبل الريكويست لنطبق نفس فلاتر البحث الموجودة في الصفحة
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = ProjectActivity::query()->with(['project', 'masterActivity', 'town.unit']);

        // تطبيق الفلاتر (نفس الموجودة في ProjectActivityController index)
        if ($this->request->filled('project_id')) {
            $query->where('project_id', $this->request->project_id);
        }

        if ($this->request->filled('search')) {
            $searchTerm = trim($this->request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('activity_code', 'like', "%{$searchTerm}%")
                  ->orWhere('station_name', 'like', "%{$searchTerm}%");
            });
        }

        return $query->latest();
    }

    // تحديد البيانات التي ستظهر في كل عمود
    public function map($activity): array
    {
        return [
            $activity->activity_code,
            $activity->project->project_code ?? '', // كود المشروع
            $activity->project->name ?? '',         // اسم المشروع
            $activity->town->unit->unit_name ?? '', // اسم الوحدة (من علاقة القرية)
            $activity->station_name,
            $activity->town->town_name ?? '',       // اسم البلدة
            $activity->cost,
            $activity->masterActivity->name ?? '',  // النشاط الرئيسي
            $activity->quantity,
            $activity->unit_measure,
            $activity->unit_capacity,
            $activity->status,
            $activity->notes,
            $activity->created_at->format('Y-m-d'),
        ];
    }

    // عناوين الأعمدة في ملف الإكسل
    public function headings(): array
    {
        return [
            'كود النشاط',
            'كود المشروع',
            'اسم المشروع',
            'اسم الوحدة',
            'اسم المحطة',
            'القرية / البلدة',
            'الكلفة',
            'النشاط الرئيسي',
            'العدد',
            'الواحدة',
            'الاستطاعة/الحجم',
            'الحالة',
            'ملاحظات',
            'تاريخ الإضافة',
        ];
    }

    // تنسيق الصف الأول (العناوين) ليكون بالخط العريض
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
