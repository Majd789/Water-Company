@extends('layouts.app')

@section('title', 'قائمة المضخات الأفقية')

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
                    <h1>قائمة المضخات الأفقية</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">المضخات الأفقية</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- قسم الفلترة للأدمن --}}
                 
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-filter mr-1"></i>
                                    فلترة حسب الوحدة
                                </h3>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('dashboard.horizontal-pumps.index') }}"
                                    id="unitFilterForm">
                                    <div class="row align-items-end">
                                        <div class="col-md-9">
                                            <div class="form-group mb-0">
                                                <label>اختر وحدة لعرض مضخاتها:</label>
                                                <select name="unit_id" id="unitFilterSelect" class="form-control select2"
                                                    style="width: 100%;">
                                                    <option value="">عرض جميع الوحدات</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->unit_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <a href="{{ route('dashboard.horizontal-pumps.index') }}"
                                                    class="btn btn-secondary w-100">إعادة التعيين</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    

                    {{-- قسم جدول البيانات --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-water mr-1"></i>
                                عرض المضخات <span class="badge badge-primary ml-2">{{ $horizontalPumps->count() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                {{-- تمرير الفلاتر الحالية لزر التصدير --}}
                                <a href="{{ route('dashboard.horizontal_pumps.export', request()->query()) }}"
                                    class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> تصدير Excel
                                </a>
                               
                                    <a href="{{ route('dashboard.horizontal-pumps.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة مضخة
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
                                <table id="pumpsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المضخة</th>
                                            <th>الحالة التشغيلية</th>
                                            <th>اسم المحطة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($horizontalPumps as $pump)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pump->pump_name ?? 'غير متوفر' }}</td>
                                                <td>
                                                    @if ($pump->pump_status == 'يعمل')
                                                        <span class="badge badge-success"
                                                            style="font-size: 0.9em;">يعمل</span>
                                                    @else
                                                        <span class="badge badge-danger"
                                                            style="font-size: 0.9em;">متوقف</span>
                                                    @endif
                                                </td>
                                                <td>{{ $pump->station->station_name ?? 'غير معروف' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.horizontal-pumps.show', $pump->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                       
                                                            <a href="{{ route('dashboard.horizontal-pumps.edit', $pump->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        
                                                        
                                                            <form
                                                                action="{{ route('dashboard.horizontal-pumps.destroy', $pump->id) }}"
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
                                                <td colspan="5" class="text-center">لا توجد مضخات أفقية مضافة حتى الآن.
                                                </td>
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

            // إرسال فورم الفلترة تلقائياً عند تغيير الوحدة
            $('#unitFilterSelect').on('change', function() {
                $('#unitFilterForm').submit();
            });

            // تهيئة DataTable
            var table = $("#pumpsTable").DataTable({
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
            table.buttons().container().appendTo('#pumpsTable_wrapper .col-md-6:eq(0)');

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
