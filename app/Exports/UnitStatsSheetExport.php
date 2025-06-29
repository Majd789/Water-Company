<?php

namespace App\Exports;

use App\Models\StationReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UnitStatsSheetExport implements FromQuery, WithHeadings, WithTitle, ShouldAutoSize
{
    public function query()
    {
        $userUnitId = auth()->user()->unit_id;
        $userUnitName = $userUnitId ? DB::table('units')->where('id', $userUnitId)->value('unit_name') : null;

        $unitStatsQuery = StationReport::query()
            ->select(
                'station_reports.وحدة المياه',

                // 1. عدد المحطات الفريدة في الوحدة
                DB::raw("COUNT(DISTINCT station_reports.المحطات) as stations_count"),

                // 2. حساب إجمالي الأيام (للنسبة المئوية)
                DB::raw("SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%تعمل%' THEN 1 ELSE 0 END) as total_days_working"),
                DB::raw("SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%متوقفة%' THEN 1 ELSE 0 END) as total_days_stopped"),
                
                // 3. حساب المتوسطات المنطقية والمقاييس الجديدة (الأكثر أهمية)
                DB::raw("ROUND(AVG(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%تعمل%' THEN 1 ELSE 0 END) * 30, 1) as avg_working_days_per_station"), // نفترض شهراً من 30 يوم
                DB::raw("ROUND(AVG(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%متوقفة%' THEN 1 ELSE 0 END) * 30, 1) as avg_stopped_days_per_station"),
                
                // 4. نسبة الجاهزية التشغيلية
                DB::raw("ROUND((SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%تعمل%' THEN 1 ELSE 0 END) / NULLIF(SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` IS NOT NULL THEN 1 ELSE 0 END), 0)) * 100, 2) as operational_readiness_percentage"),

                // 5. متوسط إنتاج المياه لكل ساعة تشغيل للآبار
                DB::raw("ROUND(SUM(water_pumped_m3) / NULLIF(SUM(total_well_hours), 0), 2) as avg_water_per_well_hour"),
                
                // 6. متوسط استهلاك الديزل لكل ساعة تشغيل بالمولدة (مولدة شمسية أو مولدة عادية)
                DB::raw("ROUND(SUM(diesel_consumption) / NULLIF(SUM(solar_generator_hours + generator_hours), 0), 2) as avg_diesel_per_generator_hour"),

                // 7. متوسط استهلاك الكهرباء لكل ساعة تشغيل بالكهرباء
                DB::raw("ROUND(SUM(electricity_consumption_kwh) / NULLIF(SUM(electricity_hours), 0), 2) as avg_kwh_per_electricity_hour"),

                // --- المجاميع الإجمالية التقليدية ---
                DB::raw("SUM(total_well_hours) as total_well_hours"),
                DB::raw("SUM(water_pumped_m3) as total_water_pumped"),
                DB::raw("SUM(diesel_consumption) as total_diesel_consumption"),
                DB::raw("SUM(electricity_consumption_kwh) as total_electricity_consumption")
            )
            ->groupBy('station_reports.وحدة المياه');

        if (!empty($userUnitName)) {
            $unitStatsQuery->where('station_reports.وحدة المياه', $userUnitName);
        }
        
        return $unitStatsQuery;
    }

    public function headings(): array
    {
        // رؤوس أعمدة جديدة وواضحة تعكس المقاييس الجديدة
        return [
            'وحدة المياه',
            'عدد المحطات',
            'إجمالي أيام العمل (كل المحطات)', // أبقيت عليه للمقارنة
            'إجمالي أيام التوقف (كل المحطات)',
            'متوسط أيام العمل للمحطة',
            'متوسط أيام التوقف للمحطة',
            'نسبة الجاهزية التشغيلية (%)',
            'متوسط إنتاج المياه (م3/ساعة بئر)',
            'متوسط استهلاك الديزل (لتر/ساعة مولدة)',
            'متوسط استهلاك الكهرباء (ك.و.س/ساعة كهرباء)',
            'إجمالي ساعات تشغيل الآبار',
            'إجمالي المياه المنتجة (م3)',
            'إجمالي الديزل المستهلك (لتر)',
            'إجمالي الكهرباء المستهلكة (ك.و.س)',
        ];
    }

    public function title(): string
    {
        return 'ملخص أداء الوحدات'; // اسم جديد يعكس المحتوى
    }
}