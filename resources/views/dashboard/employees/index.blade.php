@extends('layouts.app')

@section('title', 'قائمة الموظفين')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            right: auto;
            left: 10px;
        }
        .employee-info { line-height: 1.2; }
        .balance-badge { min-width: 45px; font-size: 0.95rem; }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>إدارة الموظفين</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الموظفين</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="container-fluid">
            {{-- قسم الفلترة والبحث --}}
            <div class="card card-default shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> تصفية الموظفين</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.employees.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group text-right">
                                    <label>الوحدة الإدارية</label>
                                    <select name="unit_id" class="form-control select2">
                                        <option value="">كل الوحدات</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group text-right">
                                    <label>البحث السريع</label>
                                    <input type="text" name="search" class="form-control" placeholder="الاسم أو الكود الوظيفي..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block mb-3 shadow-sm">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow">
                        <div class="card-header">
                            <h3 class="card-title text-right" style="float: right">
                                <i class="fas fa-users mr-1"></i>
                                إجمالي الموظفين <span class="badge badge-primary ml-2">{{ $employees->total() }}</span>
                            </h3>
                           <div class="card-tools d-flex align-items-center" style="float: left">
    {{-- زر التصدير --}}
    <a href="{{ route('dashboard.employees.export') }}" class="btn btn-success btn-sm mr-2">
        <i class="fas fa-file-excel"></i> تصدير
    </a>

    {{-- زر فتح نافذة الاستيراد (Modal) لترتيب الواجهة --}}
    <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#importModal">
        <i class="fas fa-upload"></i> استيراد
    </button>

    @can('employees.create')
    <a href="{{ route('dashboard.employees.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-user-plus"></i> إضافة موظف
    </a>
    @endcan
</div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> تم بنجاح!</h5>
                                    {{ session('success') }}
                                </div>
                            @endif
                                @if (session('import_errors'))
    <div class="alert alert-danger shadow-sm">
        <h5><i class="icon fas fa-ban"></i> تنبيه: لم يتم استيراد بعض السطور</h5>
        <ul class="mb-0">
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                            <div class="table-responsive text-right">
                                <table id="employeesTable" class="table table-bordered table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>المعلومات الأساسية</th>
                                            <th>الرقم الوظيفي</th>
                                            <th>الوحدة</th>
                                            <th>الرصيد الكلي</th>
                                            <th>المتبقي</th>
                                            <th>الحالة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="employee-info">
                                                    <strong>{{ $employee->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $employee->email ?? 'لا يوجد بريد' }}</small>
                                                </td>
                                                <td><span class="badge badge-light border">{{ $employee->employee_code }}</span></td>
                                                <td>{{ $employee->unit->unit_name ?? 'بدون وحدة' }}</td>
                                                <td class="text-center font-weight-bold">{{ $employee->total_allowed_days }}</td>
                                                <td class="text-center">
                                                    <span class="badge {{ $employee->remaining_days <= 5 ? 'badge-danger' : 'badge-success' }} balance-badge">
                                                        {{ $employee->remaining_days }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {!! $employee->is_active
                                                        ? '<span class="badge badge-success px-3">نشط</span>'
                                                        : '<span class="badge badge-danger px-3">متوقف</span>'
                                                    !!}
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.employees.show', $employee->id) }}" class="btn btn-sm btn-outline-info" title="عرض الملف">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @can('employees.edit')
                                                        <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endcan
                                                        @can('employees.delete')
                                                        <form action="{{ route('dashboard.employees.destroy', $employee->id) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف الموظف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted font-italic">لا يوجد موظفين مسجلين ضمن هذه المعايير.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $employees->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="importModalLabel text-right">استيراد موظفين من ملف إكسل</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.employees.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-right">
                    <div class="mb-3">
                        <a href="{{ route('dashboard.employees.download-template') }}" class="btn btn-outline-secondary btn-sm btn-block">
                            <i class="fas fa-download"></i> تحميل القالب المعتمد أولاً
                        </a>
                    </div>

                    <div class="form-group">
                        <label>اختر الملف (xlsx, csv)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">بدء الاستيراد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- JS Libraries --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            // تفعيل الـ Select2 مع دعم الاتجاه العربي
            $('.select2').select2({ theme: 'bootstrap4', dir: 'rtl' });

            // إعدادات الـ DataTable
            var table = $("#employeesTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                "searching": false, // نعتمد على بحث لارافيل المخصص في الـ Controller
                "ordering": true,
                "info": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                }
            });

            // تأكيد الحذف عبر SweetAlert2
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيؤدي حذف الموظف إلى إزالة كافة سجلات إجازاته المرتبطة به!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف نهائياً',
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
