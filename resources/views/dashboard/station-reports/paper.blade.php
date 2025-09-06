<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير عمل المحطة اليومي عن شهر {{ $month }}/{{ $year }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background-color: #e9ecef;
        }

        .header-table th,
        .header-table td {
            border: none;
            text-align: right;
            font-size: 12px;
        }

        .main-title {
            background-color: #c5e0b4;
            font-weight: bold;
            font-size: 16px;
            padding: 8px;
        }

        .sub-header {
            background-color: #f8cbad;
        }

        .total-row {
            background-color: #d9e1f2;
            font-weight: bold;
        }

        .no-print {
            margin-bottom: 20px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                background-color: white;
            }

            .container {
                box-shadow: none;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            .mb-2,
            .mb-3,
            .mt-2,
            .mt-3,
            .mt-4 {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>

<body>

    <div class="container my-4">
        <div class="no-print text-center">
            <button onclick="window.print()" class="btn btn-primary">طباعة التقرير</button>
            <button onclick="window.close()" class="btn btn-secondary">إغلاق</button>
        </div>

        <!-- ======================= PAGE 1 ======================= -->
        <div class="page-1">
            <table class="header-table mb-2">
                <tr>
                    <td><strong>الجمهورية العربية السورية</strong></td>
                    <td rowspan="4" class="text-center"><img src="{{ asset('assets/img/syrian-logo.png') }}"
                            alt="logo" height="60"></td>
                    <td><strong>Syrian Arab Republic</strong></td>
                </tr>
                <tr>
                    <td><strong>وزارة الطاقة</strong></td>
                    <td><strong>Ministry of Energy</strong></td>
                </tr>
                <tr>
                    <td><strong>الهيئة العامة للمياه</strong></td>
                    <td><strong>General Water Authority</strong></td>
                </tr>
                <tr>
                    <td><strong>المؤسسة العامة لمياه الشرب</strong></td>
                    <td><strong>The General Organization for Drinking Water</strong></td>
                </tr>
                <tr>
                    <td><strong>محافظة: إدلب</strong></td>
                    <td></td>
                    <td><strong>Governorate: Idlib</strong></td>
                </tr>
            </table>
            <div class="main-title text-center">تقرير عمل المحطة اليومي عن شهر {{ $month }} / {{ $year }}
                م</div>
            <table class="header-table mt-2 mb-3">
                <tr>
                    <td width="50%"><strong>اسم المحطة:</strong> {{ $station->station_name }}</td>
                    <td width="50%"><strong>طريقة الضخ:</strong> ..........................</td>
                </tr>
            </table>

            <table class="table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2">اليوم</th>
                        <th rowspan="2">عدد ساعات التشغيل</th>
                        <th colspan="2">رقم عداد الكهرباء</th>
                        <th rowspan="2">كمية الكهرباء المستهلكة KW</th>
                        <th rowspan="2" style="font-size:10px;">مصدر الطاقة التشغيلية</th>
                        <th colspan="2">عدد ساعات عمل الطاقة الشمسية</th>
                        <th colspan="2">رقم عداد الغزارة</th>
                        <th rowspan="2">كمية الماء المنتجة m3</th>
                        <th rowspan="2">كمية الكهرباء المشحونة KW</th>
                    </tr>
                    <tr class="sub-header">
                        <th>قبل</th>
                        <th>بعد</th>
                        <th>دمج</th>
                        <th>دون دمج</th>
                        <th>قبل</th>
                        <th>بعد</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= 31; $day++)
                        @php
                            $report = $reportsByDay[$day] ?? null;
                            $source = $report?->power_source?->value;
                        @endphp
                        <tr>
                            <td>{{ $day }}</td>
                            <td>{{ $report?->operating_hours }}</td>
                            <td>{{ $report?->electricity_Counter_number_before }}</td>
                            <td>{{ $report?->electricity_Counter_number_after }}</td>
                            <td>{{ $report?->electricity_power_kwh }}</td>
                            <td>{{ $report?->power_source?->getLabel() }}</td>

                            {{-- منطق الدمج للطاقة الشمسية --}}
                            <td>{!! in_array($source, ['electricity_solar', 'solar_generator', 'all_sources'])
                                ? $report?->solar_hours
                                : '&nbsp;' !!}</td>
                            <td>{!! $source === 'solar' ? $report?->solar_hours : '&nbsp;' !!}</td>

                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>{{ $report?->water_pumped_m3 }}</td>
                            <td>{{ $report?->quantity_of_electricity_meter_charged_kwh }}</td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td>الإجمالي</td>
                        <td>{{ $monthlyTotals['operating_hours'] }}</td>
                        <td colspan="2"></td>
                        <td>{{ $monthlyTotals['electricity_power_kwh'] }}</td>
                        <td></td>
                        <td colspan="2">{{ $monthlyTotals['solar_hours'] }}</td>
                        <td colspan="2"></td>
                        <td>{{ $monthlyTotals['water_pumped_m3'] }}</td>
                        <td>{{ $monthlyTotals['quantity_of_electricity_meter_charged_kwh'] }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- ======================= PAGE 2 (with page break) ======================= -->
        <div class="page-2 page-break">
            <table class="header-table mb-2">
                <tr>
                    <td><strong>الجمهورية العربية السورية</strong></td>
                    <td rowspan="4" class="text-center"><img src="{{ asset('assets/img/syrian-logo.png') }}"
                            alt="logo" height="60"></td>
                    <td><strong>Syrian Arab Republic</strong></td>
                </tr>
                <tr>
                    <td><strong>وزارة الطاقة</strong></td>
                    <td><strong>Ministry of Energy</strong></td>
                </tr>
                <tr>
                    <td><strong>الهيئة العامة للمياه</strong></td>
                    <td><strong>General Water Authority</strong></td>
                </tr>
                <tr>
                    <td><strong>المؤسسة العامة لمياه الشرب</strong></td>
                    <td><strong>The General Organization for Drinking Water</strong></td>
                </tr>
                <tr>
                    <td><strong>محافظة: إدلب</strong></td>
                    <td></td>
                    <td><strong>Governorate: Idlib</strong></td>
                </tr>
            </table>
            <div class="main-title text-center">تقرير عمل المحطة اليومي عن شهر {{ $month }} /
                {{ $year }} م</div>
            <table class="header-table mt-2 mb-3">
                <tr>
                    <td width="50%"><strong>التاريخ:</strong> .../{{ $month }}/{{ $year }}</td>
                    <td width="50%"><strong>الجهة المشغلة:</strong> {{ $operatingEntityName }}</td>
                </tr>
            </table>

            <table class="table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">عدد ساعات عمل المولدة</th>
                        <th rowspan="2">كمية المازوت المستهلكه</th>
                        <th rowspan="2">كمية المازوت الموجودة</th>
                        <th rowspan="2">قطاع الضخ</th>
                        <th rowspan="2">اسم المشغل</th>
                        <th rowspan="2">توقيع المشغل</th>
                        <th rowspan="2">الملاحظات</th>
                    </tr>
                    <tr class="sub-header">
                        <th>دمج</th>
                        <th>دون دمج</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= 31; $day++)
                        @php
                            $report = $reportsByDay[$day] ?? null;
                            $source = $report?->power_source?->value;
                        @endphp
                        <tr>
                            {{-- منطق الدمج للمولدة --}}
                            <td>{!! in_array($source, ['electricity_generator', 'solar_generator', 'all_sources'])
                                ? $report?->generator_hours
                                : '&nbsp;' !!}</td>
                            <td>{!! $source === 'generator' ? $report?->generator_hours : '&nbsp;' !!}</td>

                            <td>{!! $report?->diesel_consumed_liters ?? '&nbsp;' !!}</td>
                            <td>{!! $report?->Total_desil_liters ?? '&nbsp;' !!}</td>
                            <td>{!! $report?->pumpingSector?->sector_name ?? '&nbsp;' !!}</td>
                            <td>{!! $report?->operator?->name ?? '&nbsp;' !!}</td>
                            <td>&nbsp;</td>
                            <td>{!! $report?->notes ?? ($report?->stop_reason ?? '&nbsp;') !!}</td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2">{{ $monthlyTotals['generator_hours'] }}</td>
                        <td>{{ $monthlyTotals['diesel_consumed_liters'] }}</td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="row mt-4">
                <div class="col-6"><strong>ملاحظات مشرف المحطة:</strong>
                    <p>................................................................</p>
                </div>
                <div class="col-6 text-center"><strong>توقيع مشرف المحطة</strong>
                    <p class="mt-4">........................................</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
