<?php

namespace App\Exports;

use App\Models\Station;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
// ❗ قمنا بإزالة ShouldAutoSize
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StationCardExport implements FromView, WithTitle, WithEvents // ❗ لاحظ إزالة ShouldAutoSize
{
    protected $station;

    public function __construct(Station $station)
    {
        $this->station = $station;
    }

    public function view(): View
    {
        // تأكد من أن المسار صحيح. بناءً على الكود، يجب أن يكون هكذا:
        return view('dashboard.exports.station-card', [
            'station' => $this->station
        ]);
    }

    public function title(): string
    {
        return 'بطاقة محطة - ' . $this->station->station_code;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ... إعدادات الطباعة والهوامش واتجاه الصفحة (تبقى كما هي)
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);

                $pageMargins = $sheet->getPageMargins();
                $pageMargins->setTop(0.75);
                $pageMargins->setRight(0.4);
                $pageMargins->setLeft(0.4);
                $pageMargins->setBottom(0.75);

                $sheet->setRightToLeft(true);
                
                $sheet->getDefaultRowDimension()->setRowHeight(35);

                // ✅ الجزء الجديد: التحكم اليدوي بعرض الأعمدة
                // العمود الأول (للمواصفات) نجعله عريضًا
                $sheet->getColumnDimension('A')->setWidth(45);
                // باقي الأعمدة (للبيانات) نجعلها بعرض معقول
                // نفترض أن لديك حتى 7 آبار/عناصر كحد أقصى (حتى العمود H)
                for ($col = 'B'; $col <= 'I'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(25);
                }

                // تطبيق التنسيقات على جميع الخلايا (التوسيط وحجم الخط والتفاف النص)
                $cellRange = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
                $styleArray = [
                    'font' => [
                        'size' => 14, // يمكنك تعديل الحجم حسب الرغبة
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true, // هذا السطر ضروري ليعمل فاصل الأسطر \n
                    ],
                ];

                $sheet->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}