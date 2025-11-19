@extends('layouts.app')

@section('title', 'قائمة مهام المقاولين')

@push('styles')
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
                <h1>قائمة مهام المقاولين</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">مهام المقاولين</li>
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
                                <i class="fas fa-clipboard-check mr-1"></i>
                                عرض المهام <span class="badge badge-primary ml-2">{{ $contractorTasks->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('contractor_tasks.create')
                                    <a href="{{ route('dashboard.contractor-tasks.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة مهمة جديدة
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @include('dashboard.partials.alerts')

                            <div class="table-responsive">
                                <table id="contractorTasksTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود المهمة</th>
                                            <th>وصف المهمة</th>
                                            <th>النشاط</th>
                                            <th>المشروع</th>
                                            <th>المقاول</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($contractorTasks as $task)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $task->task_code }}</td>
                                                <td>{{ Str::limit($task->description, 50) }}</td>
                                                <td>{{ $task->projectActivity->masterActivity->name ?? 'N/A' }}</td>
                                                <td>{{ $task->projectActivity->project->name ?? 'N/A' }}</td>
                                                <td>{{ $task->projectContractor->contractor->name ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('contractor_tasks.view')
                                                            <a href="{{ route('dashboard.contractor-tasks.show', $task->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('contractor_tasks.edit')
                                                            <a href="{{ route('dashboard.contractor-tasks.edit', $task->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('contractor_tasks.delete')
                                                            <form action="{{ route('dashboard.contractor-tasks.destroy', $task->id) }}"
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
                                                <td colspan="7" class="text-center">لا توجد مهام مقاولين لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $contractorTasks->links() }}
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
            $("#contractorTasksTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                "searching": true,
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
