@extends('layouts.app')

@section('title', 'تقارير المناهل')

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
                    <h1>تقارير المناهل</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تقارير المناهل</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- قسم الفلترة والإجراءات --}}
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                فلترة وإجراءات
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('dashboard.waterwells2.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> إضافة تقرير
                                    </a>
                                    <a href="{{ route('dashboard.waterwells2.aggregated') }}" class="btn btn-info">
                                        <i class="fas fa-check-double"></i> تدقيق التقرير الإجمالي
                                    </a>
                                </div>
                                <form action="{{ route('dashboard.waterwells2.destroy') }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> حذف البيانات بعد التدقيق
                                    </button>
                                </form>
                            </div>

                            <hr>

                            <form method="GET" action="{{ route('dashboard.waterwells2.index') }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>فلترة حسب الحالة:</label>
                                            <select name="filter" class="form-control select2" style="width: 100%;">
                                                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>
                                                    جميع البيانات</option>
                                                <option value="incorrect"
                                                    {{ request('filter') == 'incorrect' ? 'selected' : '' }}>البيانات
                                                    الخاطئة فقط</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>فلترة حسب التاريخ:</label>
                                            <input type="date" name="date_filter" class="form-control"
                                                value="{{ request('date_filter') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-secondary w-100">تطبيق</button>
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
                                <i class="fas fa-table mr-1"></i>
                                عرض البيانات <span class="badge badge-primary ml-2">{{ $filteredWells->count() }}</span>
                            </h3>
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
                                <table id="wellsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>التاريخ</th>
                                            <th>اسم المنهل</th>
                                            <th>تحقق الكمية</th>
                                            <th>تحقق السعر</th>
                                            <th>تسلسل العداد</th>
                                            <th class="text-center no-export">التحكم</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($filteredWells as $well)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @php
                                                        $formattedDate = $well->date;
                                                        try {
                                                            if (is_numeric($well->date) && (int) $well->date > 0) {
                                                                $formattedDate = \Carbon\Carbon::createFromTimestamp(
                                                                    ($well->date - 25569) * 86400,
                                                                )->format('Y-m-d');
                                                            } else {
                                                                $formattedDate = \Carbon\Carbon::parse(
                                                                    $well->date,
                                                                )->format('Y-m-d');
                                                            }
                                                        } catch (\Exception $e) {
                                                            $formattedDate = $well->date; // عرض القيمة الأصلية عند الفشل
                                                        }
                                                    @endphp
                                                    {{ $formattedDate }}
                                                </td>
                                                <td>{{ $well->well_name }}</td>

                                                {{-- ========================================================== --}}
                                                {{-- تعديل: تصحيح منطق الألوان --}}
                                                {{-- ========================================================== --}}

                                                <td>
                                                    <span
                                                        class="badge {{ $well->quantity_check == 'مطابق' || $well->quantity_check == 'صحيحة' ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $well->quantity_check }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $well->price_check == 'مطابق' || $well->price_check == 'صحيحة' ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $well->price_check }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $well->meter_sequence_check == 'مطابق' || $well->meter_sequence_check == 'صحيح' ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $well->meter_sequence_check }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.waterwells2.show', $well->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('dashboard.waterwells2.edit', $well->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('dashboard.waterwells2.destroy', $well->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد بيانات لعرضها.</td>
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

            // تهيئة DataTable
            var table = $("#wellsTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "order": [
                    [0, "desc"]
                ], // ترتيب افتراضي حسب العمود الأول (ID) تنازلياً
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
            });

            // تعريف الأزرار وربطها بالجدول
            new $.fn.dataTable.Buttons(table, {
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
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> طباعة',
                        exportOptions: {
                            columns: ':visible:not(.no-export)'
                        }
                    }
                ]
            });

            table.buttons().container().appendTo('#wellsTable_wrapper .col-md-6:eq(0)');

            // تفعيل SweetAlert2 لتأكيد الحذف
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
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
