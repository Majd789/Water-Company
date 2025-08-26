<?php

namespace App\Exports;

use App\Models\Station;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StationCardExport implements FromView, WithTitle, WithEvents
{
    protected $station;

    public function __construct(Station $station)
    {
        $this->station = $station;
    }

    public function view(): View
    {
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

                // إعدادات الطباعة والهوامش واتجاه الصفحة (تبقى كما هي)
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
                
                // --- الجزء الذي تم تعديله ---
                // الحصول على أعلى صف يحتوي على بيانات
                $highestRow = $sheet->getHighestRow();

                // المرور على جميع الصفوف من 1 إلى آخر صف وتعيين الارتفاع
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(35);
                }
                // --- نهاية الجزء المعدل ---

                // التحكم اليدوي بعرض الأعمدة
                $sheet->getColumnDimension('A')->setWidth(45);
                for ($col = 'B'; $col <= 'I'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(25);
                }

                // تطبيق التنسيقات على جميع الخلايا (التوسيط وحجم الخط والتفاف النص)
                $cellRange = 'A1:' . $sheet->getHighestColumn() . $highestRow;
                $styleArray = [
                    'font' => [
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ];

                $sheet->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}