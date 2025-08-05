<?php

namespace App\Exports;

use App\Models\Station;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup; // 👈 قم باستيراد هذه الفئة

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
     * التحكم في إعدادات الصفحة بعد إنشائها
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                // 1. ضبط اتجاه الصفحة وحجم الورق (أفقي هو الأفضل للجداول العريضة)
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);

                // 2. تطبيق خيار "ملاءمة لصفحة واحدة عرضاً" (Fit to 1 page wide)
                //    و "ارتفاع تلقائي" (Height auto)
                $pageSetup->setFitToWidth(1); // هذا هو السطر الأهم: يجعل العرض صفحة واحدة
                $pageSetup->setFitToHeight(0); // تعيين الارتفاع إلى 0 يعني "تلقائي" أو غير محدود

                // 3. ضبط هوامش الصفحة لتبدو أفضل عند الطباعة
                $pageMargins = $sheet->getPageMargins();
                $pageMargins->setTop(0.75);
                $pageMargins->setRight(0.4);
                $pageMargins->setLeft(0.4);
                $pageMargins->setBottom(0.75);

                // 4. جعل اتجاه ورقة العمل من اليمين إلى اليسار
                $sheet->setRightToLeft(true);
            },
        ];
    }
}