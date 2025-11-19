@extends('layouts.app')

@section('title', 'قائمة المشاريع')

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
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة المشاريع</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">المشاريع</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-project-diagram mr-1"></i>
                                عرض المشاريع <span class="badge badge-primary ml-2">{{ $projects->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                {{-- يمكنك إضافة زر التصدير هنا مستقبلاً --}}
                                {{-- <a href="{{ route('dashboard.projects.export', request()->query()) }}" class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> تصدير Excel
                                </a> --}}

                                @can('projects.create')
                                    <a href="{{ route('dashboard.projects.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة مشروع
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

                            {{-- يمكنك إضافة فلاتر هنا مستقبلاً --}}

                            <div class="table-responsive">
                                <table id="projectsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المشروع</th>
                                            <th>كود المشروع</th>
                                            <th>المنظمة الداعمة</th>
                                            <th>الحالة العامة</th>
                                            <th>تاريخ البدء</th>
                                            <th>تاريخ النهاية</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($projects as $project)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->project_code }}</td>
                                                <td>{{ $project->organization->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $project->generalStatus->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $project->start_date }}</td>
                                                <td>{{ $project->end_date }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('projects.view')
                                                            <a href="{{ route('dashboard.projects.show', $project->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('projects.edit')
                                                            <a href="{{ route('dashboard.projects.edit', $project->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('projects.delete')
                                                            <form action="{{ route('dashboard.projects.destroy', $project->id) }}"
                                                                method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد مشاريع لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $projects->links() }}
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
            // تهيئة DataTable
            var table = $("#projectsTable").DataTable({
                "responsive": true,
                "lengthChange": false, // تعطيل تغيير عدد الصفوف
                "autoWidth": false,
                "paging": false,      // تعطيل ترقيم الصفحات من DataTable (سنستخدم ترقيم Laravel)
                "searching": true,
                "ordering": true,
                "info": false,        // تعطيل معلومات الجدول (Showing 1 to 10 of 57 entries)
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
                "buttons": [
                    {
                        extend: 'collection',
                        text: 'تصدير',
                        className: 'btn-dark',
                        buttons: [
                            { extend: 'copy', text: '<i class="fas fa-copy"></i> نسخ', exportOptions: { columns: ':visible:not(.no-export)' } },
                            { extend: 'excel', text: '<i class="fas fa-file-excel"></i> إكسيل', exportOptions: { columns: ':visible:not(.no-export)' } },
                            { extend: 'print', text: '<i class="fas fa-print"></i> طباعة', exportOptions: { columns: ':visible:not(.no-export)' } }
                        ]
                    },
                    {
                        extend: 'colvis',
                        text: 'إظهار/إخفاء الأعمدة',
                        className: 'btn-info',
                    }
                ]
            }).buttons().container().appendTo('#projectsTable_wrapper .col-md-6:eq(0)');

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
