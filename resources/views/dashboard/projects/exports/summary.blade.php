<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #cce5ff; border: 1px solid #000000;">إجمالي المشاريع</th>
            <th style="font-weight: bold; font-size: 14px; text-align: center; border: 1px solid #000000;">{{ $totalCount }}</th>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #d4edda; border: 1px solid #000000;">إجمالي القيمة المالية (USD)</th>
            <th style="font-weight: bold; font-size: 14px; text-align: center; border: 1px solid #000000;">{{ number_format($totalValue, 2) }}</th>
        </tr>
        <tr></tr> <!-- سطر فارغ -->
    </thead>
</table>

<!-- جدول حسب نوع المشروع -->
<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e3e5; border: 1px solid #000000;">توزيع المشاريع حسب النوع</th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000;">نوع المشروع</th>
            <th style="font-weight: bold; border: 1px solid #000000;">العدد</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byType as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->projectType->name ?? 'غير محدد' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<!-- جدول حسب الحالة الرئيسية -->
<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e3e5; border: 1px solid #000000;">توزيع المشاريع حسب الحالة الرئيسية</th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000;">الحالة الرئيسية</th>
            <th style="font-weight: bold; border: 1px solid #000000;">العدد</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byMainStatus as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->mainStatus->name ?? 'غير محدد' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<!-- جدول حسب حالة التسليم -->
<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; text-align: center; background-color: #e2e3e5; border: 1px solid #000000;">توزيع المشاريع حسب حالة التسليم</th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000;">حالة التسليم</th>
            <th style="font-weight: bold; border: 1px solid #000000;">العدد</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byHandoverStatus as $item)
            <tr>
                <td style="border: 1px solid #000000;">{{ $item->handoverStatus->name ?? 'غير محدد' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
