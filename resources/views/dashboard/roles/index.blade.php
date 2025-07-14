@extends('layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات')

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>إدارة الأدوار والصلاحيات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الأدوار والصلاحيات</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-shield mr-1"></i>
                                عرض الأدوار <span class="badge badge-primary ml-2">{{ $roles->count() }}</span>
                            </h3>
                            <div class="card-tools">
                                @can('roles.create')
                                    <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة دور جديد
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
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-ban"></i> خطأ!</h5>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="rolesTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الدور</th>
                                            <th>الصلاحيات</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $role)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $role->display_name ?? $role->name }}</td>
                                                <td>
                                                    @forelse ($role->permissions->take(5) as $permission)
                                                        <span
                                                            class="badge bg-success m-1">{{ $permission->display_name }}</span>
                                                    @empty
                                                        <span class="badge bg-secondary m-1">لا توجد صلاحيات</span>
                                                    @endforelse
                                                    @if ($role->permissions->count() > 5)
                                                        <span class="badge bg-info m-1">... و
                                                            {{ $role->permissions->count() - 5 }} أخرى</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('roles.edit')
                                                            <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('roles.delete')
                                                            <form action="{{ route('dashboard.roles.destroy', $role->id) }}"
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
                                                <td colspan="4" class="text-center">لا توجد أدوار لعرضها.</td>
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
            var table = $("#rolesTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json",
                },
                "buttons": [{
                    extend: 'collection',
                    text: '<i class="fas fa-file-export"></i> تصدير',
                    className: 'btn-success',
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
                    text: '<i class="fas fa-eye"></i> إظهار/إخفاء الأعمدة',
                    className: 'btn-info',
                    columns: ':not(.no-export)'
                }]
            });

            // إضافة الأزرار إلى المكان المخصص لها في تصميم الجدول
            table.buttons().container().appendTo('#rolesTable_wrapper .col-md-6:eq(0)');


            // تفعيل SweetAlert2 لتأكيد الحذف
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيؤثر حذف الدور على المستخدمين المرتبطين به!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
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
