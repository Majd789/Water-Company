@extends('layouts.app')

@section('title', 'قائمة عقود المقاولين')

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
                <h1>قائمة عقود المقاولين</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">عقود المقاولين</li>
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
                                <i class="fas fa-file-signature mr-1"></i>
                                عرض العقود <span class="badge badge-primary ml-2">{{ $projectContractors->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('project_contractors.create')
                                    <a href="{{ route('dashboard.project-contractors.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة عقد جديد
                                    </a>
                                    <a href="{{ route('dashboard.project-contractors.export', request()->query()) }}" class="btn btn-warning ms-2">
                                        <i class="fas fa-file-download"></i> تصدير Excel
                                    </a>
                                @endcan

                                {{-- زر الاستيراد والمودال --}}
                                @can('project_contractors.create')
                                    <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#importModal">
                                        <i class="fas fa-file-excel"></i> استيراد من Excel
                                    </button>

                                    {{-- المودال --}}
                                    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('dashboard.project-contractors.import') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="importModalLabel">استيراد عقود المقاولين</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="file">اختر ملف Excel</label>
                                                            <input type="file" class="form-control-file" name="file" required accept=".xlsx,.xls,.csv">
                                                            <small class="form-text text-muted">
                                                                يجب أن يتطابق ترتيب الأعمدة مع النموذج المعتمد (كود العقد، كود المشروع، ...).
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-success">رفع واستيراد</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @include('dashboard.partials.alerts')

                            <div class="table-responsive">
                                <table id="projectContractorsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود العقد</th>
                                            <th>اسم المشروع</th>
                                            <th>اسم المقاول</th>
                                            <th>قيمة العقد</th>
                                            <th>حالة التنفيذ</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($projectContractors as $contract)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $contract->contract_code }}</td>
                                                <td>{{ $contract->project->name ?? 'N/A' }}</td>
                                                <td>{{ $contract->contractor->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($contract->value, 2) }} {{ $contract->currency }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $contract->contractorStatus->name ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('project_contractors.view')
                                                            <a href="{{ route('dashboard.project-contractors.show', $contract->id) }}"
                                                                class="btn btn-sm btn-outline-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('project_contractors.edit')
                                                            <a href="{{ route('dashboard.project-contractors.edit', $contract->id) }}"
                                                                class="btn btn-sm btn-outline-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('project_contractors.delete')
                                                            <form action="{{ route('dashboard.project-contractors.destroy', $contract->id) }}"
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
                                                <td colspan="7" class="text-center">لا توجد عقود مقاولين لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $projectContractors->links() }}
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
            $("#projectContractorsTable").DataTable({
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
