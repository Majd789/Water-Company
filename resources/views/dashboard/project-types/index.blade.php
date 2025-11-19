@extends('layouts.app')

@section('title', 'قائمة أنواع المشاريع')

@push('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة أنواع المشاريع</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">أنواع المشاريع</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8"> {{-- عرض مناسب لجدول بسيط --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list-alt mr-1"></i>
                                عرض الأنواع
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('project_types.create')
                                    <a href="{{ route('dashboard.project-types.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i> إضافة نوع جديد
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @include('dashboard.partials.alerts')

                            <div class="table-responsive">
                                <table id="projectTypesTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>الاسم</th>
                                            <th style="width: 150px" class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($projectTypes as $type)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $type->name }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('project_types.edit')
                                                            <a href="{{ route('dashboard.project-types.edit', $type->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('project_types.delete')
                                                            <form action="{{ route('dashboard.project-types.destroy', $type->id) }}"
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
                                                <td colspan="3" class="text-center">لا توجد أنواع مشاريع لعرضها.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            $("#projectTypesTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                "searching": false, // البحث غير ضروري لعدد قليل من العناصر
                "ordering": true,
                "info": false,
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
            });

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
