@extends('layouts.app')

@section('title', 'قائمة البلدات')

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
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة البلدات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">البلدات</li>
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
                    {{-- قسم الفلترة (إذا كان المستخدم ليس له وحدة محددة) --}}
                    @if (!auth()->user()->unit_id)
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-filter ml-1"></i> فلترة حسب الوحدة</h3>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('dashboard.towns.index') }}" id="unitFilterForm">
                                    <div class="row align-items-end">
                                        <div class="col-md-9">
                                            <div class="form-group mb-0">
                                                <label>اختر وحدة لعرض بلداتها:</label>
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
                                                <a href="{{ route('dashboard.towns.index') }}"
                                                    class="btn btn-secondary w-100">إعادة التعيين</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- قسم جدول البيانات --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-city ml-1"></i>
                                عرض البلدات <span class="badge badge-primary ml-2">{{ $towns->count() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('dashboard.towns.export', request()->query()) }}"
                                    class="btn btn-success ml-2">
                                    <i class="fas fa-file-excel"></i> تصدير Excel
                                </a>
                                <a href="{{ route('dashboard.towns.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus ml-1"></i> إضافة بلدة
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
                                <table id="townsTable" class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم البلدة</th>
                                            <th>كود البلدة</th>
                                            <th>الوحدة</th>
                                            <th>حالة الجباية</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($towns as $town)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $town->town_name }}</td>
                                                <td>{{ $town->town_code }}</td>
                                                <td>{{ $town->unit->unit_name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($town->is_billing_actually_active)
                                                        <span class="badge bg-success">نعم</span>
                                                    @else
                                                        <span class="badge bg-danger">لا</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.towns.show', $town->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="عرض"><i
                                                                class="fas fa-eye"></i></a>
                                                        <a href="{{ route('dashboard.towns.edit', $town->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="تعديل"><i
                                                                class="fas fa-edit"></i></a>
                                                        <form action="{{ route('dashboard.towns.destroy', $town->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="حذف"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد بيانات لعرضها.</td>
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

            // إرسال فورم الفلترة تلقائياً
            $('#unitFilterSelect').on('change', function() {
                $('#unitFilterForm').submit();
            });

            // تهيئة DataTable
            $("#townsTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}"
                },
                // يمكنك إضافة أزرار التصدير هنا إذا أردت
            });

            // تفعيل SweetAlert2 لتأكيد الحذف
            $('.delete-form').on('submit', function(e) {
                /* ... نفس كود SweetAlert ... */
            });
        });
    </script>
@endpush
