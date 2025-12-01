@extends('layouts.app')

@section('title', 'قائمة أنشطة المشاريع')

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
                <h1>قائمة أنشطة المشاريع</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">أنشطة المشاريع</li>
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
                                <i class="fas fa-tasks mr-1"></i>
                                عرض الأنشطة <span class="badge badge-primary ml-2">{{ $projectActivities->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('project_activities.create')
                                    <a href="{{ route('dashboard.project-activities.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة نشاط جديد
                                    </a>
                                    <a href="{{ route('dashboard.project-activities.export') }}" class="btn btn-info" id="exportBtn">
                                    <i class="fas fa-file-export"></i> تصدير Excel
                                    </a>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importExcelModal">
                                        <i class="fas fa-file-excel"></i> استيراد Excel
                                    </button>

                                    <!-- نافذة الاستيراد (Modal) -->
                                    <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">استيراد أنشطة المشاريع</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('dashboard.project-activities.import') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>اختر ملف الإكسل</label>
                                                    <input type="file" name="file" class="form-control" required>
                                                </div>
                                                <div class="alert alert-warning">
                                                    <small>يرجى التأكد من تنسيق الملف وأن العمود الأول هو كود النشاط.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-primary">رفع واستيراد</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @include('dashboard.partials.alerts')

                            <div class="table-responsive">
                                <table id="projectActivitiesTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود النشاط</th>
                                            <th>اسم النشاط</th>
                                            <th>المشروع</th>
                                            {{-- تم تحديث العناوين --}}
                                            <th>القرية</th>
                                            <th>المحطة</th>
                                            <th>التكلفة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($projectActivities as $activity)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $activity->activity_code }}</td>
                                                <td>{{ $activity->masterActivity->name ?? 'N/A' }}</td>
                                                <td>{{ $activity->project->name ?? 'N/A' }}</td>
                                                {{-- عرض القرية واسم المحطة النصي --}}
                                                <td>{{ $activity->town->town_name ?? 'N/A' }}</td>
                                                <td>{{ $activity->station_name ?? '-' }}</td>
                                                <td>${{ number_format($activity->cost, 2) }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('project_activities.view')
                                                            <a href="{{ route('dashboard.project-activities.show', $activity->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('project_activities.edit')
                                                            <a href="{{ route('dashboard.project-activities.edit', $activity->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('project_activities.delete')
                                                            <form action="{{ route('dashboard.project-activities.destroy', $activity->id) }}"
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
                                                <td colspan="8" class="text-center">لا توجد أنشطة مشاريع لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $projectActivities->links() }}
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
            $("#projectActivitiesTable").DataTable({
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
