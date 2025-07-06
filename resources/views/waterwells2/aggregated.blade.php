@extends('layouts.app')

@section('title', 'التقرير التجميعي للمناهل')

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>التقرير التجميعي للمناهل</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('waterwells2.index') }}">تقارير المناهل</a></li>
                        <li class="breadcrumb-item active">التقرير التجميعي</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- قسم جدول البيانات --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                ملخص إجمالي لكل منهل
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('waterwells2.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة تقرير جديد
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="aggregatedTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>اسم المنهل</th>
                                            <th>الكمية المقاسة (م³)</th>
                                            <th>كمية المياه المباعة (م³)</th>
                                            <th>المياه المجانية (م³)</th>
                                            <th>تعبئة المركبات (م³)</th>
                                            <th>سعر المياه</th>
                                            <th>الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($aggregatedResults as $result)
                                            <tr>
                                                <td>{{ $result['well_name'] }}</td>
                                                <td>{{ number_format($result['total_measured_qty'], 2) }}</td>
                                                <td>{{ number_format($result['total_sold_qty'], 2) }}</td>
                                                <td>{{ number_format($result['total_free_qty'], 2) }}</td>
                                                <td>{{ number_format($result['total_vehicle_qty'], 2) }}</td>
                                                <td>{{ number_format($result['water_price'], 2) }}</td>
                                                <td>{{ number_format($result['total_amount'], 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد بيانات مجمعة لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold; background-color: #f8f9fa;">
                                            <th>الإجمالي الكلي</th>
                                            {{-- استبدال array_column و array_sum بدوال Collection --}}
                                            <th>{{ number_format($aggregatedResults->sum('total_measured_qty'), 2) }}</th>
                                            <th>{{ number_format($aggregatedResults->sum('total_sold_qty'), 2) }}</th>
                                            <th>{{ number_format($aggregatedResults->sum('total_free_qty'), 2) }}</th>
                                            <th>{{ number_format($aggregatedResults->sum('total_vehicle_qty'), 2) }}</th>
                                            <th>-</th>
                                            <th>{{ number_format($aggregatedResults->sum('total_amount'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
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

    <script>
        $(function() {
            // تهيئة DataTable
            var table = $("#aggregatedTable").DataTable({
                "responsive": true,
                "lengthChange": false, // تعطيل خيار تغيير عدد الصفوف
                "autoWidth": false,
                "paging": false, // تعطيل الترقيم لأنها صفحة ملخص
                "searching": true, // تفعيل البحث الفوري
                "ordering": true,
                "info": false, // تعطيل معلومات الجدول
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();

                    // دالة لتنسيق الأرقام
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // حساب الإجمالي لكل عمود وعرضه في الـ footer
                    $(api.column(1).footer()).html(intVal(api.column(1, {
                        page: 'current'
                    }).data().sum()).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $(api.column(2).footer()).html(intVal(api.column(2, {
                        page: 'current'
                    }).data().sum()).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $(api.column(3).footer()).html(intVal(api.column(3, {
                        page: 'current'
                    }).data().sum()).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $(api.column(4).footer()).html(intVal(api.column(4, {
                        page: 'current'
                    }).data().sum()).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $(api.column(6).footer()).html(intVal(api.column(6, {
                        page: 'current'
                    }).data().sum()).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }
            });

            // تعريف الأزرار وربطها بالجدول
            new $.fn.dataTable.Buttons(table, {
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> نسخ',
                        footer: true
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> إكسيل',
                        footer: true
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        footer: true
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> طباعة',
                        footer: true,
                        messageTop: 'التقرير التجميعي للمناهل'
                    },
                    {
                        extend: 'colvis',
                        text: 'إظهار/إخفاء الأعمدة'
                    }
                ]
            });

            // إضافة الأزرار إلى المكان المخصص لها
            table.buttons().container().appendTo('#aggregatedTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
