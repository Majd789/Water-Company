@extends('layouts.app') {{-- تأكد من أنه نفس ملف الـ layout الرئيسي --}}

@section('title', 'نشاطات المستخدمين')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    {{-- CSS مخصص لعرض التفاصيل وإصلاح اتجاه سهم Select2 --}}
    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            right: auto;
            left: 10px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>سجل نشاطات المستخدمين</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">نشاطات المستخدمين</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                     <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> تنبيه هام!</h5>
                        للحفاظ على أداء النظام، يرجى إفراغ سجل النشاطات كل ثلاثة أشهر على الأقل.
                    </div>
                    <!-- END: الرسالة التحذيرية -->

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> نجاح!</h5>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4><i class="fas fa-chart-bar mr-1"></i> أكثر المستخدمين نشاطاً</h4>
                        </div>
                        @forelse($userActivityCounts as $stat)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $stat->activity_count }} <sup style="font-size: 20px">تغيير</sup></h3>
                                        <p>{{ $stat->name }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">لا توجد إحصائيات لعرضها حالياً.</p>
                            </div>
                        @endforelse
                    </div>
                    {{-- قسم الفلترة --}}
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                فلترة النتائج
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('dashboard.activity-log.index') }}" id="filterForm">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اختر المستخدم:</label>
                                            <select name="user_id" id="userFilter" class="form-control select2" style="width: 100%;">
                                                <option value="">كل المستخدمين</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اختر الموديل:</label>
                                            <select name="model" id="modelFilter" class="form-control select2" style="width: 100%;">
                                                <option value="">كل الموديلات</option>
                                                @foreach ($models as $model)
                                                    <option value="{{ class_basename($model) }}" {{ request('model') == class_basename($model) ? 'selected' : '' }}>
                                                        {{ class_basename($model) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary w-100">تطبيق الفلتر</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <a href="{{ route('dashboard.activity-log.index') }}" class="btn btn-secondary w-100">إعادة التعيين</a>
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
                                <i class="fas fa-history mr-1"></i>
                                عرض السجلات
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('dashboard.activity-log.export', request()->query()) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel mr-1"></i> تصدير Excel
                                </a>
                                @can('activities_logs.delete')
                                <form action="{{ route('dashboard.activity-log.deleteAll') }}" method="POST" class="d-inline delete-all-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-2">
                                        <i class="fas fa-trash-alt mr-1"></i> حذف جميع السجلات
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="activityLogTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>المستخدم</th>
                                            <th>الحدث</th>
                                            <th>الموديل</th>
                                            <th>رقم العنصر</th>
                                            <th>التاريخ</th>
                                            <th class="text-center">التفاصيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($activities as $activity)
                                            <tr>
                                                <td>{{ $activity->causer ? $activity->causer->name : 'غير معروف' }}</td>
                                                <td>{{ ucfirst($activity->description) }}</td>
                                                <td>{{ class_basename($activity->subject_type) }}</td>
                                                <td>{{ $activity->subject_id }}</td>
                                                <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="text-center">
                                                    @if ($activity->properties && (isset($activity->properties['attributes']) || isset($activity->properties['old'])))
                                                        <details>
                                                            <summary class="btn btn-sm btn-outline-info cursor-pointer">عرض التفاصيل</summary>
                                                            <ul class="list-unstyled text-right mt-2 border p-2 rounded bg-light" dir="rtl">
                                                                @foreach (($activity->properties['attributes'] ?? []) as $key => $newValue)
                                                                    <li>
                                                                        <strong>{{ $key }}:</strong>
                                                                        @if (isset($activity->properties['old'][$key]))
                                                                            <span class="text-danger" style="text-decoration: line-through;" dir="ltr">{{ $activity->properties['old'][$key] }}</span>
                                                                            <i class="fas fa-arrow-left text-muted mx-1"></i>
                                                                            <span class="text-success" dir="ltr">{{ $newValue }}</span>
                                                                        @else
                                                                            <span class="text-success" dir="ltr">{{ $newValue }}</span> <span class="badge badge-info"> (جديد) </span>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </details>
                                                    @else
                                                        <span>—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد سجلات لعرضها.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            // ... (بقية كود تهيئة Select2 و DataTable كما كان) ...
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $("#activityLogTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "paging": false, "searching": true, "ordering": true, "info": false,
                "language": { "url": "{{ asset('datatable-lang/ar.json') }}", "search": "بحث في النتائج المعروضة:", },
            });

            // --- START: تفعيل SweetAlert2 لتأكيد الحذف الشامل ---
            $('.delete-all-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيتم حذف جميع سجلات النشاط بشكل نهائي! لا يمكن التراجع عن هذا الإجراء.",
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
            // --- END: تفعيل SweetAlert2 ---
        });
    </script>
@endpush
