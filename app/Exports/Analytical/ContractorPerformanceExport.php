<?php

namespace App\Exports\Analytical;

use App\Models\Contractor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContractorPerformanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $filters;
    public function __construct($filters) { $this->filters = $filters; }

    public function collection()
    {
        $query = Contractor::with(['projectContracts.tasks']);

        return $query->get()->map(function ($contractor) {
            // تصفية العقود حسب التاريخ
            $contracts = $contractor->projectContracts;

            if (!empty($this->filters['start_date'])) {
                $contracts = $contracts->where('contract_date', '>=', $this->filters['start_date']);
            }
            if (!empty($this->filters['end_date'])) {
                $contracts = $contracts->where('contract_date', '<=', $this->filters['end_date']);
            }

            // تجميع المهام من العقود المصفاة
            $allTasks = $contracts->pluck('tasks')->flatten();

            $totalContractsValue = $contracts->sum('value');
            $discrepantTasks = $allTasks->where('is_discrepant', 1)->count();
            $totalTasks = $allTasks->count();

            $discrepancyRate = $totalTasks > 0 ? round(($discrepantTasks / $totalTasks) * 100, 2) . '%' : '0%';

            return [
                'name' => $contractor->name,
                'phone' => $contractor->phone_number,
                'contracts_count' => $contracts->count(),
                'total_contracts_value' => $totalContractsValue,
                'total_tasks' => $totalTasks,
                'discrepant_tasks' => $discrepantTasks,
                'rate' => $discrepancyRate,
            ];
        })->sortByDesc('total_contracts_value');
    }

    public function headings(): array
    {
        return [
            'اسم المقاول', 'الهاتف', 'عدد العقود', 'قيمة العقود ($)', 'إجمالي المهام', 'مهام غير مطابقة', 'نسبة عدم التطابق'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('#,##0.00');
        return [];
    }
}
