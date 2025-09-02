@extends('layouts.app')

@section('title', 'تقارير المحطات')

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

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تقارير المحطات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تقارير المحطات</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- قسم الفلترة --}}
                    <div class="card card-secondary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                تصفية البيانات
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <form method="GET" action="{{ route('dashboard.station-reports.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="station_id">المحطة</label>
                                            <select name="station_id" id="station_id" class="form-control select2">
                                                <option value="">جميع المحطات</option>
                                                @foreach($stations as $station)
                                                    <option value="{{ $station->id }}"
                                                        {{ request('station_id') == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">الحالة التشغيلية</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">جميع الحالات</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->value }}"
                                                        {{ request('status') == $status->value ? 'selected' : '' }}>
                                                        {{ $status->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_from">من تاريخ</label>
                                            <input type="date" name="date_from" id="date_from" class="form-control"
                                                value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date_to">إلى تاريخ</label>
                                            <input type="date" name="date_to" id="date_to" class="form-control"
                                                value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-search"></i> بحث
                                                </button>
                                            </div>
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
                                <i class="fas fa-file-alt mr-1"></i>
                                عرض التقارير <span class="badge badge-primary ml-2">{{ $reports->total() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('station_reports.create')
                                    <a href="{{ route('dashboard.station-reports.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة تقرير
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
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>تاريخ التقرير</th>
                                            <th>المحطة</th>
                                            <th>المشغل</th>
                                            <th>الحالة</th>
                                            <th>ساعات التشغيل</th>
                                            <th>كمية المياه (م³)</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reports as $report)
                                            <tr>
                                                <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</td>
                                                <td>{{ $report->report_date ? $report->report_date->format('Y-m-d') : '-' }}</td>
                                                <td>{{ $report->station->station_name ?? 'غير محدد' }}</td>
                                                <td>{{ $report->operator->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    @if($report->status)
                                                        <span class="badge badge-{{ $report->status->getColor() }}">
                                                            {{ $report->status->getLabel() }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">غير محدد</span>
                                                    @endif
                                                </td>
                                                <td>{{ $report->operating_hours ?? 0 }}</td>
                                                <td>{{ $report->water_pumped_m3 ?? 0 }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('station_reports.view')
                                                            <a href="{{ route('dashboard.station-reports.show', $report) }}"
                                                                class="btn btn-sm btn-info" title="عرض">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('station_reports.edit')
                                                            <a href="{{ route('dashboard.station-reports.edit', $report) }}"
                                                                class="btn btn-sm btn-warning" title="تعديل">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('station_reports.delete')
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="deleteReport({{ $report->id }})" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد تقارير</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center">
                                {{ $reports->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Select2 JS --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });
        });

        function deleteReport(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/dashboard/station-reports/${id}`;

                    let csrfField = document.createElement('input');
                    csrfField.type = 'hidden';
                    csrfField.name = '_token';
                    csrfField.value = '{{ csrf_token() }}';

                    let methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfField);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
