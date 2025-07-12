@extends('layouts.app')

@section('title', 'قائمة مهام الصيانة')

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
                    <h1>قائمة مهام الصيانة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">مهام الصيانة</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- قسم الفلترة حسب الوحدة (اختياري، لكنه مفيد) --}}
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                فلترة حسب الوحدة
                            </h3>
                        </div>
                        <div class="card-body">
                            {{-- يجب تعديل الـ route ليتناسب مع الكنترولر الخاص بمهام الصيانة --}}
                            <form method="GET" action="{{ route('dashboard.maintenance_tasks.index') }}"
                                id="unitFilterForm">
                                <div class="row align-items-end">
                                    <div class="col-md-9">
                                        <div class="form-group mb-0">
                                            <label>اختر وحدة لعرض مهام الصيانة الخاصة بها:</label>
                                            <select name="unit_id" id="unitFilterSelect" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="">عرض جميع الوحدات</option>
                                                {{-- افترض أنك سترسل متغير $units من الكنترولر --}}
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
                                            <a href="{{ route('dashboard.maintenance_tasks.index') }}"
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
                                <i class="fas fa-tools mr-1"></i>
                                عرض مهام الصيانة <span
                                    class="badge badge-primary ml-2">{{ $maintenanceTasks->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                {{-- يمكن إضافة زر تصدير هنا بنفس الطريقة --}}
                                @can('maintenance_tasks.create')
                                    <a href="{{ route('dashboard.maintenance_tasks.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة مهمة صيانة
                                    </a>
                                @endcan
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
                                <table id="maintenanceTasksTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الوحدة</th>
                                            <th>الفني المسؤول</th>
                                            <th>تاريخ الصيانة</th>
                                            <th>مكان العطل</th>
                                            <th>حالة الإصلاح</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($maintenanceTasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $task->unit->unit_name ?? 'N/A' }}</td>
                                                <td>{{ $task->technician_name }}</td>
                                                <td>{{ $task->maintenance_date }}</td>
                                                <td>{{ $task->location }}</td>
                                                <td class="text-center">
                                                    @if ($task->is_fixed)
                                                        <span class="badge badge-success">تم الإصلاح</span>
                                                    @else
                                                        <span class="badge badge-danger">لم يتم الإصلاح</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('maintenance_tasks.view')
                                                            <a href="{{ route('dashboard.maintenance_tasks.show', $task->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('maintenance_tasks.edit')
                                                            <a href="{{ route('dashboard.maintenance_tasks.edit', $task->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('maintenance_tasks.delete')
                                                            <form
                                                                action="{{ route('dashboard.maintenance_tasks.destroy', $task->id) }}"
                                                                method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد مهام صيانة لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{-- عرض روابط الـ Pagination --}}
                                {{ $maintenanceTasks->links() }}
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
            $("#maintenanceTasksTable").DataTable({
                "responsive": true,
                "lengthChange": false, // تعطيل تغيير عدد الصفوف
                "autoWidth": false,
                "paging": false, // تعطيل الترقيم من DataTable لأننا نستخدم ترقيم Laravel
                "searching": true,
                "ordering": true,
                "info": false, // تعطيل معلومات الجدول
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
                "buttons": [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> نسخ'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> إكسيل'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> طباعة'
                    },
                    {
                        extend: 'colvis',
                        text: 'إظهار/إخفاء الأعمدة'
                    }
                ]
            }).buttons().container().appendTo('#maintenanceTasksTable_wrapper .col-md-6:eq(0)');


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
