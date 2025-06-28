<?php

namespace App\Exports;

use App\Models\StationReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UnitStatsSheetExport implements FromQuery, WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // نفس الكود الذي استخدمته في الـ Controller
        $userUnitId = auth()->user()->unit_id;
        $userUnitName = DB::table('units')->where('id', $userUnitId)->value('unit_name');

        // تم توسيع الاستعلام ليشمل كل الأعمدة القابلة للجمع
        $unitStatsQuery = StationReport::select(
            'station_reports.وحدة المياه',
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
        )->groupBy('station_reports.وحدة المياه');

        if (!empty($userUnitName)) {
            $unitStatsQuery->where('station_reports.وحدة المياه', $userUnitName);
        }
        
        return $unitStatsQuery;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // أسماء الأعمدة في ملف Excel
        return [
            'وحدة المياه',
            'مجموع ساعات تشغيل الآبار',
            'مجموع ساعات تشغيل المضخات الأفقية',
            'مجموع ساعات (طاقة شمسية + كهرباء)',
            'مجموع ساعات (طاقة شمسية + مولدة)',
            'مجموع ساعات (طاقة شمسية فقط)',
            'مجموع ساعات (كهرباء فقط)',
            'مجموع استهلاك الكهرباء (KWH)',
            'مجموع كمية المياه المنتجة (م3)',
            'مجموع استهلاك الديزل (لتر)',
            'مجموع كمية الزيت المضافة (لتر)',
            'مجموع الكهرباء المشحونة (KWH)',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // اسم ورقة العمل (Sheet)
        return 'إحصائيات الوحدات';
    }
}