<?php

namespace App\Exports;

use App\Models\Station;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class StationCardExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
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

    /**
     * التحكم في إعدادات الصفحة والتنسيقات بعد إنشائها
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                // === الجزء الأصلي لإعدادات الطباعة ===

                // 1. ضبط اتجاه الصفحة وحجم الورق
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);

                // 2. ضبط هوامش الصفحة
                $pageMargins = $sheet->getPageMargins();
                $pageMargins->setTop(0.75);
                $pageMargins->setRight(0.4);
                $pageMargins->setLeft(0.4);
                $pageMargins->setBottom(0.75);

                // 3. جعل اتجاه ورقة العمل من اليمين إلى اليسار
                $sheet->setRightToLeft(true);

                // === الجزء الجديد المطلوب ===

                // 4. ✅ ضبط ارتفاع الصف الافتراضي إلى 30
                $sheet->getDefaultRowDimension()->setRowHeight(35);

                // 5. ✅ ضبط حجم الخط لجميع الخلايا إلى 16
                // نحدد نطاق الخلايا من A1 وحتى آخر خلية تحتوي على بيانات
                $cellRange = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
                $sheet->getStyle($cellRange)->getFont()->setSize(16);
            },
        ];
    }
}