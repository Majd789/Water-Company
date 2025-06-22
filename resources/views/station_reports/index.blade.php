@extends('layouts.app')
<style>
    table thead th {
        position: sticky;
        top: 0;
        background: #fff;
        /* يمكنك تغيير لون الخلفية حسب التصميم */
        z-index: 10;
    }

    .table-responsive {
        max-height: 800px;
        /* يمكنك تعديل الارتفاع حسب الحاجة */
        overflow-y: auto;
    }
</style>
@section('content')
    <div class="recent-orders" style="text-align: center">
        <h2 class="text-center">قائمة تقارير المحطات</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a id="btnb" href="{{ route('station_reports.create') }}" class="btn btn-primary mb-3">إضافة تقرير جديد</a>

        <!-- أزرار التبديل بين الجداول -->
        <div class="text-center mb-4">
            <button id="btnb" class="btn btn-secondary mx-3" onclick="showTable('unitTable')">إحصائيات الوحدات</button>
            <button id="btnb" class="btn btn-secondary mx-3" onclick="showTable('stationTable')">إحصائيات
                المحطات</button>
            <button id="btnb" class="btn btn-secondary" onclick="showTable('avgTable')">متوسط احصائيات المحطة</button>
        </div>

        <!-- جدول إحصائيات الوحدات -->
        <div id="unitTable" class="table" style="display:block;">
            <table class="table">
                <thead>
                    <tr>
                        <th>وحدة المياه</th>
                        <th>ساعات تشغيل البئر</th>
                        <th>ساعات تشغيل المضخة الأفقية</th>
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
                            <td>{{ $unit->total_well_hours }}</td>
                            <td>{{ $unit->total_horizontal_pump_hours }}</td>
                            <td>{{ $unit->total_solar_electricity_hours }}</td>
                            <td>{{ $unit->total_solar_generator_hours }}</td>
                            <td>{{ $unit->total_solar_only_hours }}</td>
                            <td>{{ $unit->total_electricity_hours }}</td>
                            <td>{{ $unit->total_electricity_consumption }}</td>
                            <td>{{ $unit->total_water_pumped }}</td>
                            <td>{{ $unit->total_diesel_used }}</td>
                            <td>{{ $unit->total_oil_added }}</td>
                            <td>{{ $unit->total_charged_electricity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- رسم بياني لإحصائيات الوحدات -->
            <div class="card-box mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        الاحصائيات
                    </div>
                    <div class="card-body">
                        <div id="unitStatsChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#unitStatsChart"), {
                                    series: [{
                                            name: 'إجمالي ساعات تشغيل البئر',
                                            data: @json($unitStats->pluck('total_well_hours')),
                                        },
                                        {
                                            name: 'إجمالي استهلاك الكهرباء',
                                            data: @json($unitStats->pluck('total_electricity_consumption'))
                                        },
                                        {
                                            name: 'إجمالي المياه المضخوخة',
                                            data: @json($unitStats->pluck('total_water_pumped'))
                                        },
                                        {
                                            name: 'ساعات تشغيل الطاقة الشمسية (كهرباء)',
                                            data: @json($unitStats->pluck('total_solar_electricity_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل المولد الشمسي',
                                            data: @json($unitStats->pluck('total_solar_generator_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل الطاقة الشمسية فقط',
                                            data: @json($unitStats->pluck('total_solar_only_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل الكهرباء',
                                            data: @json($unitStats->pluck('total_electricity_hours')),
                                        }
                                    ],
                                    chart: {
                                        height: 350,
                                        type: 'line',
                                        toolbar: {
                                            show: false
                                        },
                                    },
                                    markers: {
                                        size: 4
                                    },
                                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#1abc9c', '#9b59b6', '#e67e22', '#34495e'],
                                    stroke: {
                                        curve: 'smooth',
                                        width: 2
                                    },
                                    xaxis: {
                                        categories: @json($unitStats->pluck('وحدة المياه'))
                                    },
                                    yaxis: {
                                        labels: {
                                            formatter: function(value) {
                                                return value.toFixed(2);
                                            }
                                        }
                                    },
                                    tooltip: {
                                        x: {
                                            format: 'dd/MM/yy'
                                        }
                                    }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول إحصائيات المحطات -->
        <div id="stationTable" class="table-responsive" style="display:none;">
            <h2 class="mb-4">إحصائيات لكل محطة</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>وحدة المياه</th>
                        <th>المحطة</th>
                        <th>ساعات تشغيل البئر</th>
                        <th>ساعات تشغيل المضخة الأفقية</th>
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
                            <td>{{ $station->total_well_hours }}</td>
                            <td>{{ $station->total_horizontal_pump_hours }}</td>
                            <td>{{ $station->total_solar_electricity_hours }}</td>
                            <td>{{ $station->total_solar_generator_hours }}</td>
                            <td>{{ $station->total_solar_only_hours }}</td>
                            <td>{{ $station->total_electricity_hours }}</td>
                            <td>{{ $station->total_electricity_consumption }}</td>
                            <td>{{ $station->total_water_pumped }}</td>
                            <td>{{ $station->total_diesel_used }}</td>
                            <td>{{ $station->total_oil_added }}</td>
                            <td>{{ $station->total_charged_electricity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- رسم بياني لإحصائيات المحطات -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div id="stationStatsChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#stationStatsChart"), {
                                    series: [{
                                            name: 'إجمالي ساعات تشغيل البئر',
                                            data: @json($stationStats->pluck('total_well_hours')),
                                        },
                                        {
                                            name: 'إجمالي استهلاك الكهرباء',
                                            data: @json($stationStats->pluck('total_electricity_consumption'))
                                        },
                                        {
                                            name: 'إجمالي المياه المضخوخة',
                                            data: @json($stationStats->pluck('total_water_pumped'))
                                        },
                                        {
                                            name: 'ساعات تشغيل الطاقة الشمسية (كهرباء)',
                                            data: @json($stationStats->pluck('total_solar_electricity_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل المولد الشمسي',
                                            data: @json($stationStats->pluck('total_solar_generator_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل الطاقة الشمسية فقط',
                                            data: @json($stationStats->pluck('total_solar_only_hours')),
                                        },
                                        {
                                            name: 'ساعات تشغيل الكهرباء',
                                            data: @json($stationStats->pluck('total_electricity_hours')),
                                        }
                                    ],
                                    chart: {
                                        height: 350,
                                        type: 'line',
                                        toolbar: {
                                            show: false
                                        },
                                        zoom: {
                                            enabled: true,
                                            type: 'x'
                                        }
                                    },
                                    markers: {
                                        size: 4
                                    },
                                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#1abc9c', '#9b59b6', '#e67e22', '#34495e'],
                                    stroke: {
                                        curve: 'smooth',
                                        width: 2
                                    },
                                    xaxis: {
                                        categories: @json($stationStats->pluck('المحطات')),
                                        labels: {
                                            rotate: -45,
                                            style: {
                                                fontSize: '10px'
                                            }
                                        },
                                        scrollable: true
                                    },
                                    yaxis: {
                                        labels: {
                                            formatter: function(value) {
                                                return value.toFixed(2);
                                            }
                                        }
                                    },
                                    tooltip: {
                                        x: {
                                            format: 'dd/MM/yy'
                                        }
                                    }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول متوسط احصائيات المحطة -->
        <div id="avgTable" class="table-responsive" style="display:none;">
            <h2 class="mb-4">متوسط احصائيات المحطة</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>المحطة</th>
                        <th>متوسط ساعات تشغيل البئر</th>
                        <th>متوسط ساعات تشغيل المضخة الأفقية</th>
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
                            <td>{{ $avg->avg_total_well_hours }}</td>
                            <td>{{ $avg->avg_total_horizontal_pump_hours }}</td>
                            <td>{{ $avg->avg_total_solar_electricity_hours }}</td>
                            <td>{{ $avg->avg_total_solar_generator_hours }}</td>
                            <td>{{ $avg->avg_total_solar_only_hours }}</td>
                            <td>{{ $avg->avg_total_electricity_hours }}</td>
                            <td>{{ $avg->avg_total_electricity_consumption }}</td>
                            <td>{{ $avg->avg_total_water_pumped }}</td>
                            <td>{{ $avg->avg_total_diesel_used }}</td>
                            <td>{{ $avg->avg_total_oil_added }}</td>
                            <td>{{ $avg->avg_total_charged_electricity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- رسم بياني لمتوسط احصائيات المحطة -->
            <div class="card-box mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        احصائيات المتوسطات
                    </div>
                    <div class="card-body">
                        <div id="avgStatsChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#avgStatsChart"), {
                                    series: [{
                                            name: 'متوسط ساعات تشغيل البئر',
                                            data: @json($avgStats->pluck('avg_total_well_hours')),
                                        },
                                        {
                                            name: 'متوسط استهلاك الكهرباء',
                                            data: @json($avgStats->pluck('avg_total_electricity_consumption'))
                                        },
                                        {
                                            name: 'متوسط المياه المضخوخة',
                                            data: @json($avgStats->pluck('avg_total_water_pumped'))
                                        },
                                        {
                                            name: 'متوسط ساعات تشغيل الطاقة الشمسية (كهرباء)',
                                            data: @json($avgStats->pluck('avg_total_solar_electricity_hours')),
                                        },
                                        {
                                            name: 'متوسط ساعات تشغيل المولد الشمسي',
                                            data: @json($avgStats->pluck('avg_total_solar_generator_hours')),
                                        },
                                        {
                                            name: 'متوسط ساعات تشغيل الطاقة الشمسية فقط',
                                            data: @json($avgStats->pluck('avg_total_solar_only_hours')),
                                        },
                                        {
                                            name: 'متوسط ساعات تشغيل الكهرباء',
                                            data: @json($avgStats->pluck('avg_total_electricity_hours')),
                                        }
                                    ],
                                    chart: {
                                        height: 350,
                                        type: 'line',
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    markers: {
                                        size: 4
                                    },
                                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#1abc9c', '#9b59b6', '#e67e22', '#34495e'],
                                    stroke: {
                                        curve: 'smooth',
                                        width: 2
                                    },
                                    xaxis: {
                                        categories: @json($avgStats->pluck('المحطات'))
                                    },
                                    yaxis: {
                                        labels: {
                                            formatter: function(value) {
                                                return value.toFixed(2);
                                            }
                                        }
                                    },
                                    tooltip: {
                                        x: {
                                            format: 'dd/MM/yy'
                                        }
                                    }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript للتحكم في عرض الجداول -->
    <script>
        function showTable(tableId) {
            document.getElementById('unitTable').style.display = (tableId === 'unitTable') ? 'block' : 'none';
            document.getElementById('stationTable').style.display = (tableId === 'stationTable') ? 'block' : 'none';
            document.getElementById('avgTable').style.display = (tableId === 'avgTable') ? 'block' : 'none';
        }
    </script>
@endsection
