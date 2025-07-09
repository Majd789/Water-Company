@extends('layouts.app')

@section('title', 'قائمة المحطات')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- CSS لإصلاح مشكلة اتجاه السهم في Select2 مع RTL --}}
    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            right: auto;
            left: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>قائمة المحطات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">المحطات</li>
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
                                <i class="fas fa-broadcast-tower mr-1"></i>
                                عرض المحطات <span class="badge badge-primary ml-2">{{ $stations->count() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('dashboard.stations.export', request()->query()) }}"
                                    class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> تصدير Excel
                                </a>

                                <a href="{{ route('dashboard.stations.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i> إضافة محطة
                                </a>

                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> نجاح!</h5>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="stationsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المحطة</th>
                                            <th>كود المحطة</th>
                                            <th>الوضع التشغيلي</th>
                                            <th>البلدة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stations as $station)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $station->station_name }}</td>
                                                <td>{{ $station->station_code }}</td>
                                                <td>{{ $station->operational_status }}</td>
                                                <td>{{ $station->town->town_name ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.stations.show', $station->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('dashboard.stations.edit', $station->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>


                                                        <form
                                                            action="{{ route('dashboard.stations.destroy', $station->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد بيانات لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
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
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            // تهيئة Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // تهيئة DataTable
            var table = $("#stationsTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}", // استخدام ملف الترجمة المحلي
                },
            });

            // تعريف الأزرار وربطها بالجدول
            new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'collection',
                    text: 'تصدير (البيانات المعروضة)',
                    className: 'btn-dark',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> نسخ',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> إكسيل',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> طباعة',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'
                            }
                        }
                    ]
                }, {
                    extend: 'colvis',
                    text: 'إظهار/إخفاء الأعمدة',
                    className: 'btn-info',
                    columns: ':not(.no-export)'
                }]
            });

            // إضافة الأزرار إلى المكان المخصص لها
            table.buttons().container().appendTo('#stationsTable_wrapper .col-md-6:eq(0)');

            // تفعيل SweetAlert2 لتأكيد الحذف
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، قم بالحذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@endpush
