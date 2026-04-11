<?php
namespace App\Exports;

use App\Models\Leave;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LeaveExport implements FromView, WithEvents
{
    protected $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function view(): View
    {
        return view('dashboard.leaves.export_excel', [
            'leave' => $this->leave
        ]);
    }
public function setPageSetup(): array
    {
        return [
            'orientation' => PageSetup::ORIENTATION_PORTRAIT,
            'paperSize'   => PageSetup::PAPERSIZE_A4,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setRightToLeft(true);

                // 2. ضبط الهوامش لتكون ضيقة (لزيادة المساحة المتاحة)
                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setBottom(0.5);
                $sheet->getPageMargins()->setLeft(0.5);
                $sheet->getPageMargins()->setRight(0.5);

                // 3. ضبط عرض الأعمدة (إجمالي العرض يجب أن يناسب A4)
                foreach (range('A', 'F') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setWidth(15);
                }

                // 4. السحر هنا: زيادة ارتفاع الصفوف لتغطية طول الصفحة
                // الصفوف العادية (البيانات)
                foreach (range(4, 25) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(35);
                }

                // صفوف التواقيع (تحتاج مساحة أكبر)
                $sheet->getRowDimension(12)->setRowHeight(80); // توقيع مقدم الطلب
                $sheet->getRowDimension(20)->setRowHeight(80); // توقيع المدير
            },
        ];
    }
}
