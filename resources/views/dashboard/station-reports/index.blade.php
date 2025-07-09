@extends('layouts.app')

@section('title', 'تقارير المحطات')

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* لجعل رأس الجدول ثابت عند التمرير */
        .table-responsive {
            max-height: 70vh;
            /* تحديد ارتفاع أقصى للجدول */
            overflow-y: auto;
        }

        table thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }

        /* تنسيق زر التبديل النشط */
        .btn-toggle.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تقارير المحطات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تقارير المحطات</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> نجاح!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        الإجراءات وعرض الإحصائيات
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <!-- أزرار الإجراءات الرئيسية -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.station_reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة تقرير جديد
                            </a>
                            <a href="{{ route('dashboard.station_reports.export') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> تصدير ملخص الإحصائيات
                            </a>
                        </div>
                        <!-- أزرار التبديل بين الجداول -->
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-secondary active" onclick="showTable('unitSection')">
                                <input type="radio" name="options" autocomplete="off" checked> إحصائيات الوحدات
                            </label>
                            <label class="btn btn-outline-secondary" onclick="showTable('stationSection')">
                                <input type="radio" name="options" autocomplete="off"> إحصائيات المحطات
                            </label>
                            <label class="btn btn-outline-secondary" onclick="showTable('avgSection')">
                                <input type="radio" name="options" autocomplete="off"> متوسط الإحصائيات
                            </label>
                        </div>
                    </div>

                    <!-- حاوية إحصائيات الوحدات -->
                    <div id="unitSection" class="table-container" style="display:block;">
                        <h4 class="mb-3 text-center">إحصائيات مجمعة لكل وحدة</h4>
                        <div class="table-responsive">
                            <table id="unitTable" class="table table-bordered table-striped">
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
                        <div class="card mt-4">
                            <div class="card-body">
                                <div id="unitStatsChart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- حاوية إحصائيات المحطات -->
                    <div id="stationSection" class="table-container" style="display:none;">
                        <h4 class="mb-3 text-center">إحصائيات لكل محطة</h4>
                        <div class="table-responsive">
                            <table id="stationTable" class="table table-bordered table-striped">
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
                        <div class="card mt-4">
                            <div class="card-body">
                                <div id="stationStatsChart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- حاوية متوسط الإحصائيات -->
                    <div id="avgSection" class="table-container" style="display:none;">
                        <h4 class="mb-3 text-center">متوسط احصائيات المحطة</h4>
                        <div class="table-responsive">
                            <table id="avgTable" class="table table-bordered table-striped">
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
                        <div class="card mt-4">
                            <div class="card-body">
                                <div id="avgStatsChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- JS Libraries --}}
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // دالة التبديل بين الجداول
        function showTable(sectionId) {
            document.querySelectorAll('.table-container').forEach(container => {
                container.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        $(function() {
            // دالة مشتركة لتهيئة DataTables
            function initializeDataTable(tableId) {
                var table = $(tableId).DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "language": {
                        "url": "{{ asset('datatable-lang/ar.json') }}",
                    },
                    "order": [
                        [0, "asc"]
                    ]
                });

                new $.fn.dataTable.Buttons(table, {
                    buttons: [{
                            extend: 'copy',
                            text: 'نسخ'
                        },
                        {
                            extend: 'excel',
                            text: 'إكسيل'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        },
                        {
                            extend: 'print',
                            text: 'طباعة'
                        },
                        {
                            extend: 'colvis',
                            text: 'إظهار/إخفاء الأعمدة'
                        }
                    ]
                });

                table.buttons().container().appendTo($(tableId + '_wrapper .col-md-6:eq(0)'));
            }

            // تهيئة جميع الجداول
            initializeDataTable('#unitTable');
            initializeDataTable('#stationTable');
            initializeDataTable('#avgTable');

            // --- الرسومات البيانية ---
            // Chart 1: Unit Stats
            new ApexCharts(document.querySelector("#unitStatsChart"), {
                series: [{
                    name: 'إجمالي المياه المضخوخة (م³)',
                    data: @json($unitStats->pluck('total_water_pumped'))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                title: {
                    text: 'إجمالي المياه المضخوخة لكل وحدة',
                    align: 'center'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true
                    }
                },
                colors: ['#4154f1'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($unitStats->pluck('وحدة المياه'))
                }
            }).render();

            // Chart 2: Station Stats
            new ApexCharts(document.querySelector("#stationStatsChart"), {
                series: [{
                    name: "ساعات تشغيل البئر",
                    data: @json($stationStats->pluck('total_well_hours'))
                }, {
                    name: "المياه المضخوخة",
                    data: @json($stationStats->pluck('total_water_pumped'))
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                title: {
                    text: 'أداء المحطات',
                    align: 'center'
                },
                colors: ['#0d6efd', '#198754'],
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'category',
                    categories: @json($stationStats->pluck('المحطات'))
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    }
                },
            }).render();

            // Chart 3: Average Stats
            new ApexCharts(document.querySelector("#avgStatsChart"), {
                series: [{
                    name: 'متوسط استهلاك الكهرباء',
                    data: @json($avgStats->pluck('avg_total_electricity_consumption'))
                }, {
                    name: 'متوسط الديزل المستخدم',
                    data: @json($avgStats->pluck('avg_total_diesel_used'))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                title: {
                    text: 'متوسط استهلاك الطاقة لكل محطة',
                    align: 'center'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                colors: ['#fd7e14', '#dc3545'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($avgStats->pluck('المحطات'))
                }
            }).render();
        });
    </script>
@endpush
