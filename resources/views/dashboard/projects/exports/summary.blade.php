{{--
    تنسيقات عامة:
    نستخدم Inline Styles لأن مكتبة Excel تدعمها بشكل أفضل.
    الألوان المستخدمة مستوحاة من Bootstrap.
--}}

{{-- ====================================================================================== --}}
{{-- القسم الأول: لوحة القيادة العامة (GLOBAL DASHBOARD) - شاملة لكل السنوات --}}
{{-- ====================================================================================== --}}

{{-- 1. المؤشرات الرئيسية العامة --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; font-size: 16px; text-align: center; background-color: #343a40; color: #ffffff; border: 2px solid #000000; height: 35px; vertical-align: center;">
                المؤشرات العامة للمحفظة (منذ بداية العمل)
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000; width: 30px;">المؤشر</th>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000; text-align: center; width: 20px;">القيمة</th>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000; width: 15px;">الوحدة</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">إجمالي عدد المشاريع</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $globalStats['overview']['total_count'] }}</td>
            <td style="border: 1px solid #000000;">مشروع</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">إجمالي القيمة المالية</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ number_format($globalStats['overview']['total_value'], 2) }}</td>
            <td style="border: 1px solid #000000;">USD</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">متوسط قيمة المشروع</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ number_format($globalStats['overview']['avg_value'], 2) }}</td>
            <td style="border: 1px solid #000000;">USD</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">قيمة أكبر مشروع</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ number_format($globalStats['overview']['max_value'], 2) }}</td>
            <td style="border: 1px solid #000000;">USD</td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: 1px solid #000000;">متوسط المدة الزمنية</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ number_format($globalStats['overview']['avg_duration'], 0) }}</td>
            <td style="border: 1px solid #000000;">يوم</td>
        </tr>
    </tbody>
</table>

<br>

{{-- 2. جداول التحليل العامة (منظمات، مانحين، أنواع) --}}
{{-- ملاحظة: في الإكسل ستظهر الجداول تحت بعضها --}}

{{-- جدول: أعلى المنظمات تمويلاً --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #28a745; color: #ffffff; border: 1px solid #000000;">أهم المنظمات الشريكة (إجمالاً)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">المنظمة</th>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">عدد المشاريع</th>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">القيمة المالية</th>
        </tr>
    </thead>
    <tbody>
        @forelse($globalStats['top_organizations'] as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->organization->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" style="text-align: center; border: 1px solid #000000;">لا توجد بيانات</td></tr>
        @endforelse
    </tbody>
</table>

<br>

{{-- جدول: توزيع الأنواع --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #17a2b8; color: #ffffff; border: 1px solid #000000;">توزيع المشاريع حسب النوع (إجمالاً)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">نوع المشروع</th>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">العدد</th>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">القيمة المالية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($globalStats['by_type'] as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->projectType->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- جدول: الحالة العامة وحالة التسليم --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #ffc107; color: #000000; border: 1px solid #000000;">التوزيع حسب الحالة العامة (إجمالاً)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">الحالة</th>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">العدد</th>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">القيمة المعلقة/المنفذة</th>
        </tr>
    </thead>
    <tbody>
        @foreach($globalStats['by_general_status'] as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->generalStatus->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<hr style="border: 2px solid #000000;">
<br>

{{-- ====================================================================================== --}}
{{-- القسم الثاني: التحليل السنوي (YEARLY BREAKDOWN) - حلقة تكرار لكل سنة --}}
{{-- ====================================================================================== --}}

@foreach($yearlyStatistics as $year => $yearStats)

    {{-- عنوان السنة --}}
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #007bff; color: #ffffff; border: 2px solid #000000; height: 30px;">
                    تقرير عام {{ $year }}
                </th>
            </tr>
        </thead>
    </table>

    {{-- ملخص السنة --}}
    <table>
        <thead>
            <tr>
                <th style="font-weight: bold; background-color: #cce5ff; border: 1px solid #000000;">مؤشرات {{ $year }}</th>
                <th style="font-weight: bold; background-color: #cce5ff; border: 1px solid #000000; text-align: center;">العدد/القيمة</th>
                <th style="font-weight: bold; background-color: #cce5ff; border: 1px solid #000000;">ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000000;">عدد المشاريع</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ $yearStats['overview']['count'] }}</td>
                <td style="border: 1px solid #000000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000000;">الميزانية السنوية</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ number_format($yearStats['overview']['value'], 2) }} USD</td>
                <td style="border: 1px solid #000000;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid #000000;">متوسط الإنجاز (مدة)</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{ number_format($yearStats['overview']['avg_duration'], 0) }} يوم</td>
                <td style="border: 1px solid #000000;"></td>
            </tr>
        </tbody>
    </table>

    {{-- الاتجاه الشهري (Monthly Trend) --}}
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-weight: bold; text-align: center; background-color: #6c757d; color: #ffffff; border: 1px solid #000000;">التوزيع الشهري للمشاريع في {{ $year }}</th>
            </tr>
            <tr>
                <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">الشهر</th>
                <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">عدد المشاريع</th>
                <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">قيمة العقود</th>
            </tr>
        </thead>
        <tbody>
            @forelse($yearStats['monthly_trend'] as $month)
                <tr>
                    <td style="border: 1px solid #000000;">{{ $month->month_name }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $month->count }}</td>
                    <td style="border: 1px solid #000000;">{{ number_format($month->total_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center; border: 1px solid #000000;">لا توجد مشاريع مسجلة بتواريخ محددة</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- المنظمات في هذه السنة --}}
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-weight: bold; text-align: center; background-color: #28a745; color: #ffffff; border: 1px solid #000000;">المنظمات في {{ $year }}</th>
            </tr>
            <tr>
                <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">المنظمة</th>
                <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">العدد</th>
                <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">القيمة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($yearStats['organizations'] as $org)
                <tr>
                    <td style="border: 1px solid #000000;">{{ $org->organization->name ?? 'غير محدد' }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $org->count }}</td>
                    <td style="border: 1px solid #000000;">{{ number_format($org->total_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center; border: 1px solid #000000;">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- المانحين في هذه السنة --}}
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-weight: bold; text-align: center; background-color: #17a2b8; color: #ffffff; border: 1px solid #000000;">المانحين في {{ $year }}</th>
            </tr>
            <tr>
                <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">الجهة المانحة</th>
                <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">العدد</th>
                <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">القيمة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($yearStats['donors'] as $donor)
                <tr>
                    <td style="border: 1px solid #000000;">{{ $donor->donor_name }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $donor->count }}</td>
                    <td style="border: 1px solid #000000;">{{ number_format($donor->total_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center; border: 1px solid #000000;">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- الحالة في هذه السنة --}}
    <table>
        <thead>
            <tr>
                <th colspan="3" style="font-weight: bold; text-align: center; background-color: #ffc107; color: #000000; border: 1px solid #000000;">الحالة العامة لمشاريع {{ $year }}</th>
            </tr>
            <tr>
                <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">الحالة</th>
                <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">العدد</th>
                <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">القيمة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($yearStats['general_status'] as $status)
                <tr>
                    <td style="border: 1px solid #000000;">{{ $status->generalStatus->name ?? 'غير محدد' }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $status->count }}</td>
                    <td style="border: 1px solid #000000;">{{ number_format($status->total_value, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center; border: 1px solid #000000;">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <br> {{-- مسافة كبيرة بين السنوات --}}

@endforeach
