@extends('layouts.app')

@section('title', 'قائمة الإجازات')

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

    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            right: auto;
            left: 10px;
        }
        .badge-duration { font-size: 0.9rem; }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>سجل الإجازات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الإجازات</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- قسم الفلترة --}}
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> تصفية النتائج</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.leaves.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
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
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block mb-3">
                                    <i class="fas fa-search"></i> تصفية
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check mr-1"></i>
                                سجل الإجازات المسجلة <span class="badge badge-primary ml-2">{{ $leaves->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('dashboard.leaves.export', request()->query()) }}" class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> تصدير Excel
                                </a>
                                <a href="{{ route('dashboard.leaves.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i> تسجيل إجازة
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
                                <table id="leavesTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الموظف</th>
                                            <th>نوع الإجازة</th>
                                            <th>من تاريخ</th>
                                            <th>إلى تاريخ</th>
                                            <th>المدة (يوم)</th>
                                            <th>بواسطة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($leaves as $leave)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $leave->employee->full_name }}</strong><br>
                                                    <small class="text-muted">{{ $leave->employee->unit->unit_name ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $leave->type->affects_balance ? 'badge-warning' : 'badge-info' }}">
                                                        {{ $leave->type->type_name }}
                                                    </span>
                                                </td>
                                                <td>{{ $leave->start_date }}</td>
                                                <td>{{ $leave->end_date }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-secondary badge-duration">{{ $leave->duration }}</span>
                                                </td>
                                                <td>{{ $leave->creator->name ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.leaves.export-excel', $leave->id) }}" class="btn btn-sm btn-outline-info" title="طباعة الإشعار" target="_blank">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <form action="{{ route('dashboard.leaves.destroy', $leave->id) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد إجازات مسجلة حالياً.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $leaves->appends(request()->query())->links() }}
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
    {{-- ... استكمال بقية ملفات الـ JS بنفس كودك --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            $('.select2').select2({ theme: 'bootstrap4' });

            var table = $("#leavesTable").DataTable({
                "responsive": true,
                "lengthChange": false, // التعطيل لأننا نستخدم Paginate الخاص بـ Laravel
                "autoWidth": false,
                "paging": false, // نستخدم ترقيم صفحات لارافيل
                "searching": true,
                "ordering": true,
                "info": false,
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}"
                },
            });

            // SweetAlert2 للتأكيد
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل تريد حذف هذه الإجازة؟',
                    text: "سيتم إعادة الرصيد المخصوم للموظف تلقائياً!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، حذف وإعادة الرصيد',
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
