<?php

namespace App\Imports; // تأكد من الـ Namespace الصحيح، هنا يجب أن يكون App\Exports
namespace App\Exports;

use App\Models\ProjectContractor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class ProjectContractorsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    // نستقبل الـ Request لتمكين التصدير حسب الفلتر الموجود في الصفحة
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = ProjectContractor::query()->with(['project', 'contractor', 'contractorStatus']);

        // تطبيق نفس الفلاتر المستخدمة في صفحة العرض
        if ($this->request->filled('project_id')) {
            $query->where('project_id', $this->request->project_id);
        }
        if ($this->request->filled('contractor_id')) {
            $query->where('contractor_id', $this->request->contractor_id);
        }

        return $query;
    }

    // البيانات التي ستظهر في كل صف
    public function map($contract): array
    {
        return [
            $contract->contract_code,
            $contract->project->project_code ?? '',
            $contract->project->name ?? '',
            $contract->contractor->name ?? '',
            $contract->contract_date,
            $contract->value,
            $contract->currency,
            $contract->duration_days,
            $contract->start_date,
            $contract->end_date,
            $contract->actual_start_date,
            $contract->actual_end_date,
            $contract->contract_status, // حالة العقد (موافقة)
            $contract->org_approval_number,
            $contract->org_approval_date,
            $contract->contractorStatus->name ?? '', // حالة التنفيذ
        ];
    }

    // عناوين الأعمدة في ملف الإكسل
    public function headings(): array
    {
        return [
            'كود العقد',
            'كود المشروع',
            'اسم المشروع',
            'اسم المقاول',
            'تاريخ العقد',
            'قيمة العقد',
            'العملة',
            'المدة (أيام)',
            'تاريخ البداية',
            'تاريخ النهاية',
            'البدء الفعلي',
            'الانتهاء الفعلي',
            'حالة العقد',
            'رقم الموافقة',
            'تاريخ الموافقة',
            'حالة التنفيذ',
        ];
    }

    // تنسيق بسيط للخط العريض في العنوان
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
