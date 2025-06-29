<?php

namespace App\Exports;

use App\Models\StationReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StationStatsSheetExport implements FromQuery, WithHeadings, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        $userUnitId = auth()->user()->unit_id;
        $userUnitName = DB::table('units')->where('id', $userUnitId)->value('unit_name');

        $stationStatsQuery = StationReport::query()
            ->select(
                'station_reports.وحدة المياه',
                'station_reports.البلدة',
                'station_reports.المحطات',
                'station_reports.station_code',
                
                // ---== التصحيح هنا ==---
                // تم تغليف اسم العمود العربي بعلامات الاقتباس الخلفية (backticks)
                DB::raw("SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%تعمل%' THEN 1 ELSE 0 END) as days_working"),
                DB::raw("SUM(CASE WHEN `station_reports`.`الوضع التشغيلي` LIKE '%متوقفة%' THEN 1 ELSE 0 END) as days_stopped"),

                // بقية المجاميع
                DB::raw("SUM(total_well_hours)"),
                DB::raw("SUM(horizontal_pump_hours)"),
                DB::raw("SUM(solar_electricity_hours)"),
                DB::raw("SUM(solar_generator_hours)"),
                DB::raw("SUM(solar_only_hours)"),
                DB::raw("SUM(electricity_hours)"),
                DB::raw("SUM(electricity_consumption_kwh)"),
                DB::raw("SUM(water_pumped_m3)"),
                DB::raw("SUM(diesel_consumption)"),
                DB::raw("SUM(oil_quantity)"),
                DB::raw("SUM(charged_electricity_kwh)")
            )
            ->groupBy(
                'station_reports.وحدة المياه', 
                'station_reports.البلدة', 
                'station_reports.المحطات',
                'station_reports.station_code'
            );

        if (!empty($userUnitName)) {
            $stationStatsQuery->where('station_reports.وحدة المياه', $userUnitName);
        }
        
        return $stationStatsQuery;
    }

    // دالة headings() و title() تبقى كما هي دون تغيير
    public function headings(): array
    {
        return [
            'وحدة المياه', 'البلدة', 'المحطة', 'كود المحطة', 'أيام العمل', 'أيام التوقف',
            'مجموع ساعات تشغيل الآبار', 'مجموع ساعات تشغيل المضخات الأفقية',
            'مجموع ساعات (طاقة شمسية + كهرباء)', 'مجموع ساعات (طاقة شمسية + مولدة)',
            'مجموع ساعات (طاقة شمسية فقط)', 'مجموع ساعات (كهرباء فقط)',
            'مجموع استهلاك الكهرباء (KWH)', 'مجموع كمية المياه المنتجة (م3)',
            'مجموع استهلاك الديزل (لتر)', 'مجموع كمية الزيت المضافة (لتر)',
            'مجموع الكهرباء المشحونة (KWH)',
        ];
    }

    public function title(): string
    {
        return 'إحصائيات المحطات';
    }
}