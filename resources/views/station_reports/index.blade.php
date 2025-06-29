@extends('layouts.app')
<style>
    table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        /* لون خلفية أفتح */
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }

    .table-responsive {
        max-height: 800px;
        overflow-y: auto;
    }

    /* تنسيق الزر النشط */
    .btn-toggle.active {
        background-color: #0d6efd;
        color: #fff;
    }

    /* --- تنسيق الأزرار المحسّن (بتدرج لوني) --- */
    .btn-action-gradient {
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 500;
        color: #fff;
        /* لون النص أبيض دائمًا */
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        /* لإخفاء التأثيرات الزائدة */
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* تطبيق التدرج اللوني على الأزرار الأساسية */
    .btn-action-gradient.btn-primary {
        color: #fff;
        background-image: linear-gradient(45deg, #0d6efd, #3c8cff);
    }

    .btn-action-gradient.btn-success {
        color: #fff;
        background-image: linear-gradient(45deg, #198754, #28a745);
    }

    /* تأثير التمرير */
    .btn-action-gradient:hover {
        transform: scale(1.05);
        /* تكبير بسيط عند التمرير */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-action-gradient:active {
        transform: scale(1);
        /* إعادة الحجم الأصلي عند النقر */
    }
</style>
@section('content')
    <div class="recent-orders" style="text-align: center">
        <h2 class="text-center">قائمة تقارير المحطات</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- الأزرار الرئيسية وأزرار التبديل المحسنة -->
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
            <a href="{{ route('station_reports.create') }}" class="btn btn-primary btn-action-gradient">
                <i class="fas fa-plus"></i> إضافة تقرير جديد
            </a>
            <a href="{{ route('station_reports.export') }}" class="btn btn-success btn-action-gradient">
                <i class="fas fa-file-excel"></i> تصدير ملخص الإحصائيات
            </a>
            <!-- مجموعة أزرار التبديل -->
            <div class="btn-group" role="group">
                <button class="btn btn-outline-primary btn-toggle active" onclick="showTable('unitTable', this)">إحصائيات
                    الوحدات</button>
                <button class="btn btn-outline-primary btn-toggle" onclick="showTable('stationTable', this)">إحصائيات
                    المحطات</button>
                <button class="btn btn-outline-primary btn-toggle" onclick="showTable('avgTable', this)">متوسط
                    الإحصائيات</button>
            </div>
        </div>


        <!-- جدول إحصائيات الوحدات (الهيكل لم يتغير) -->
        <div id="unitTable" class="table-container" style="display:block;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>وحدة المياه</th>
                            <th>ساعات تشغيل البئر</th>
                            <th>ساعات المضخة الأفقية</th>
                            <th>كهرباء وطاقة</th>
                            <th>مولدة وطاقة</th>
                            <th>الطاقة الشمسية فقط</th>
                            <th>الكهرباء فقط</th>
                            <th>استهلاك الكهرباء</th>
                            <th>المياه المضخوخة</th>
                            <th>الديزل المستخدم</th>
                            <th>الزيت المضاف</th>
                            <th>الكهرباء المشحونة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unitStats as $unit)
                            <tr>
                                <td>{{ $unit->{"وحدة المياه"} }}</td>
                                <td>{{ number_format($unit->total_well_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_horizontal_pump_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_solar_electricity_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_solar_generator_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_solar_only_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_electricity_hours, 2) }}</td>
                                <td>{{ number_format($unit->total_electricity_consumption, 2) }}</td>
                                <td>{{ number_format($unit->total_water_pumped, 2) }}</td>
                                <td>{{ number_format($unit->total_diesel_used, 2) }}</td>
                                <td>{{ number_format($unit->total_oil_added, 2) }}</td>
                                <td>{{ number_format($unit->total_charged_electricity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- رسم بياني لإحصائيات الوحدات -->
            <div class="card mt-4">
                <div class="card-body">
                    <div id="unitStatsChart"></div>
                </div>
            </div>
        </div>

        <!-- جدول إحصائيات المحطات (الهيكل لم يتغير) -->
        <div id="stationTable" class="table-container" style="display:none;">
            <h2 class="mb-4">إحصائيات لكل محطة</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>وحدة المياه</th>
                            <th>المحطة</th>
                            <th>ساعات تشغيل البئر</th>
                            <th>ساعات المضخة الأفقية</th>
                            <th>كهرباء وطاقة</th>
                            <th>مولدة وطاقة</th>
                            <th>الطاقة الشمسية فقط</th>
                            <th>الكهرباء فقط</th>
                            <th>استهلاك الكهرباء</th>
                            <th>المياه المضخوخة</th>
                            <th>الديزل المستخدم</th>
                            <th>الزيت المضاف</th>
                            <th>الكهرباء المشحونة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stationStats as $station)
                            <tr>
                                <td>{{ $station->{"وحدة المياه"} }}</td>
                                <td>{{ $station->{"المحطات"} }}</td>
                                <td>{{ number_format($station->total_well_hours, 2) }}</td>
                                <td>{{ number_format($station->total_horizontal_pump_hours, 2) }}</td>
                                <td>{{ number_format($station->total_solar_electricity_hours, 2) }}</td>
                                <td>{{ number_format($station->total_solar_generator_hours, 2) }}</td>
                                <td>{{ number_format($station->total_solar_only_hours, 2) }}</td>
                                <td>{{ number_format($station->total_electricity_hours, 2) }}</td>
                                <td>{{ number_format($station->total_electricity_consumption, 2) }}</td>
                                <td>{{ number_format($station->total_water_pumped, 2) }}</td>
                                <td>{{ number_format($station->total_diesel_used, 2) }}</td>
                                <td>{{ number_format($station->total_oil_added, 2) }}</td>
                                <td>{{ number_format($station->total_charged_electricity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- رسم بياني لإحصائيات المحطات -->
            <div class="card mt-4">
                <div class="card-body">
                    <div id="stationStatsChart"></div>
                </div>
            </div>
        </div>

        <!-- جدول متوسط احصائيات المحطة (الهيكل لم يتغير) -->
        <div id="avgTable" class="table-container" style="display:none;">
            <h2 class="mb-4">متوسط احصائيات المحطة</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>المحطة</th>
                            <th>متوسط ساعات البئر</th>
                            <th>متوسط ساعات المضخة الأفقية</th>
                            <th>متوسط كهرباء وطاقة</th>
                            <th>متوسط مولدة وطاقة</th>
                            <th>متوسط الطاقة الشمسية فقط</th>
                            <th>متوسط الكهرباء فقط</th>
                            <th>متوسط استهلاك الكهرباء</th>
                            <th>متوسط المياه المضخوخة</th>
                            <th>متوسط الديزل المستخدم</th>
                            <th>متوسط الزيت المضاف</th>
                            <th>متوسط الكهرباء المشحونة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($avgStats as $avg)
                            <tr>
                                <td>{{ $avg->{"المحطات"} }}</td>
                                <td>{{ number_format($avg->avg_total_well_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_horizontal_pump_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_solar_electricity_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_solar_generator_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_solar_only_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_electricity_hours, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_electricity_consumption, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_water_pumped, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_diesel_used, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_oil_added, 2) }}</td>
                                <td>{{ number_format($avg->avg_total_charged_electricity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- رسم بياني لمتوسط احصائيات المحطة -->
            <div class="card mt-4">
                <div class="card-body">
                    <div id="avgStatsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript للتحكم في عرض الجداول والرسومات البيانية -->
    <script>
        function showTable(tableId, clickedButton) {
            // إخفاء جميع الحاويات
            document.querySelectorAll('.table-container').forEach(table => {
                table.style.display = 'none';
            });
            // إظهار الحاوية المطلوبة
            document.getElementById(tableId).style.display = 'block';

            // تحديث الأزرار
            document.querySelectorAll('.btn-toggle').forEach(button => {
                button.classList.remove('active');
            });
            clickedButton.classList.add('active');
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Chart 1: Unit Stats (Bar Chart for Comparison)
            new ApexCharts(document.querySelector("#unitStatsChart"), {
                series: [{
                        name: 'إجمالي ساعات تشغيل البئر',
                        data: @json($unitStats->pluck('total_well_hours'))
                    },
                    {
                        name: 'إجمالي المياه المضخوخة (م³)',
                        data: @json($unitStats->pluck('total_water_pumped'))
                    },
                    {
                        name: 'إجمالي استهلاك الكهرباء (kWh)',
                        data: @json($unitStats->pluck('total_electricity_consumption'))
                    }
                ],
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: true
                    }
                },
                title: {
                    text: 'مقارنة إحصائيات الوحدات الرئيسية',
                    align: 'center'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded'
                    }
                },
                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($unitStats->pluck('وحدة المياه'))
                },
                yaxis: {
                    labels: {
                        formatter: (value) => value.toFixed(0)
                    }
                },
                tooltip: {
                    y: {
                        formatter: (val) => `${val.toFixed(2)}`
                    }
                }
            }).render();

            // Chart 2: Station Stats (Line Chart for Trends)
            new ApexCharts(document.querySelector("#stationStatsChart"), {
                series: [{
                        name: 'إجمالي ساعات تشغيل البئر',
                        data: @json($stationStats->pluck('total_well_hours'))
                    },
                    {
                        name: 'إجمالي المياه المضخوخة (م³)',
                        data: @json($stationStats->pluck('total_water_pumped'))
                    },
                    {
                        name: 'إجمالي استهلاك الكهرباء (kWh)',
                        data: @json($stationStats->pluck('total_electricity_consumption'))
                    }
                ],
                chart: {
                    height: 380,
                    type: 'line',
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                title: {
                    text: 'مقارنة أداء المحطات',
                    align: 'center'
                },
                colors: ['#0d6efd', '#198754', '#fd7e14'],
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                markers: {
                    size: 3
                },
                xaxis: {
                    categories: @json($stationStats->pluck('المحطات')),
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '10px'
                        },
                        trim: true
                    },
                    tickPlacement: 'on'
                },
                yaxis: {
                    labels: {
                        formatter: (value) => value.toFixed(0)
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false
                }
            }).render();

            // Chart 3: Average Stats (Bar Chart for Comparison)
            new ApexCharts(document.querySelector("#avgStatsChart"), {
                series: [{
                        name: 'متوسط ساعات تشغيل البئر',
                        data: @json($avgStats->pluck('avg_total_well_hours'))
                    },
                    {
                        name: 'متوسط المياه المضخوخة (م³)',
                        data: @json($avgStats->pluck('avg_total_water_pumped'))
                    },
                    {
                        name: 'متوسط استهلاك الكهرباء (kWh)',
                        data: @json($avgStats->pluck('avg_total_electricity_consumption'))
                    }
                ],
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: true
                    }
                },
                title: {
                    text: 'مقارنة متوسط الإحصائيات لكل محطة',
                    align: 'center'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded'
                    }
                },
                colors: ['#6f42c1', '#17a2b8', '#ffc107'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($avgStats->pluck('المحطات')),
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '10px'
                        },
                        trim: true
                    }
                },
                yaxis: {
                    labels: {
                        formatter: (value) => value.toFixed(2)
                    }
                },
                tooltip: {
                    y: {
                        formatter: (val) => `${val.toFixed(2)}`
                    }
                }
            }).render();
        });
    </script>
@endsection
