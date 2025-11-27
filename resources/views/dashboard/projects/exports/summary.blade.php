{{-- القسم الأول: البطاقات الرئيسية --}}
<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #007bff; color: #ffffff; border: 1px solid #000000;">المؤشرات العامة</th>
            <th style="background-color: #007bff; border: 1px solid #000000;"></th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000;">إجمالي عدد المشاريع</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ $totalCount }}</th>
            <th style="border: 1px solid #000000;">مشروع</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000;">إجمالي المحفظة المالية</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ number_format($totalValue, 2) }}</th>
            <th style="border: 1px solid #000000;">USD</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e9ecef; border: 1px solid #000000;">متوسط مدة المشروع</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000000;">{{ number_format($avgDuration, 0) }}</th>
            <th style="border: 1px solid #000000;">يوم</th>
        </tr>
        <tr></tr>
    </thead>
</table>

{{-- القسم الثاني: تحليل المنظمات (الأهم) --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #28a745; color: #ffffff; border: 1px solid #000000;">تحليل المنظمات (مرتب حسب القيمة المالية)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">اسم المنظمة</th>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">عدد المشاريع</th>
            <th style="font-weight: bold; background-color: #d4edda; border: 1px solid #000000;">إجمالي التكلفة (USD)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byOrg as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->organization->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- القسم الثالث: تحليل أنواع المشاريع والحالات --}}
<table>
    <!-- الصف الأول من الجداول المتجاورة (نظرياً في إكسل سيكونون تحت بعضهم) -->
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #17a2b8; color: #ffffff; border: 1px solid #000000;">توزيع المشاريع حسب النوع</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">نوع المشروع</th>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">العدد</th>
            <th style="font-weight: bold; background-color: #d1ecf1; border: 1px solid #000000;">القيمة المالية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byType as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->projectType->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- الحالة العامة --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #ffc107; color: #000000; border: 1px solid #000000;">توزيع المشاريع حسب الحالة العامة</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">الحالة العامة</th>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">العدد</th>
            <th style="font-weight: bold; background-color: #fff3cd; border: 1px solid #000000;">القيمة المالية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byGenStatus as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->generalStatus->name ?? 'غير محدد' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- القسم الرابع: الجهات المانحة --}}
<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; text-align: center; background-color: #6c757d; color: #ffffff; border: 1px solid #000000;">أهم الجهات المانحة</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">الجهة المانحة</th>
            <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">عدد المنح</th>
            <th style="font-weight: bold; background-color: #e2e3e5; border: 1px solid #000000;">حجم التمويل</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byDonor as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->donor_name }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->count }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($item->total_value, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

{{-- القسم الخامس: المشرفين والسنوات --}}
<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: center; background-color: #343a40; color: #ffffff; border: 1px solid #000000;">نشاط المشرفين (أعلى 15)</th>
            <th style="border: none;"></th>
            <th colspan="2" style="font-weight: bold; text-align: center; background-color: #343a40; color: #ffffff; border: 1px solid #000000;">التوزيع حسب السنوات</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #d6d8db; border: 1px solid #000000;">اسم المشرف</th>
            <th style="font-weight: bold; background-color: #d6d8db; border: 1px solid #000000;">عدد المشاريع</th>
            <th style="border: none;"></th>
            <th style="font-weight: bold; background-color: #d6d8db; border: 1px solid #000000;">السنة</th>
            <th style="font-weight: bold; background-color: #d6d8db; border: 1px solid #000000;">عدد المشاريع</th>
        </tr>
    </thead>
    <tbody>
        {{-- هنا سنستخدم حلقة دوران ذكية لدمج الجدولين بجانب بعضهما إن أمكن،
             أو عرضهما بشكل منفصل. لضمان التنسيق في Blade سنعرضهم منفصلين أو متتالين --}}

        @php
            $maxRows = max($bySupervisor->count(), $byYear->count());
        @endphp

        @for($i = 0; $i < $maxRows; $i++)
            <tr>
                {{-- بيانات المشرفين --}}
                @if(isset($bySupervisor[$i]))
                    <td style="border: 1px solid #000000;">{{ $bySupervisor[$i]->supervisor_name }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $bySupervisor[$i]->count }}</td>
                @else
                    <td style="border: 1px solid #000000;"></td>
                    <td style="border: 1px solid #000000;"></td>
                @endif

                <td style="border: none;"></td> {{-- فاصل --}}

                {{-- بيانات السنوات --}}
                @if(isset($byYear[$i]))
                    <td style="border: 1px solid #000000;">{{ $byYear[$i]->year }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $byYear[$i]->count }}</td>
                @else
                    <td style="border: 1px solid #000000;"></td>
                    <td style="border: 1px solid #000000;"></td>
                @endif
            </tr>
        @endfor
    </tbody>
</table>
