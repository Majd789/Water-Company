@extends('layouts.app')

@section('title', 'قائمة تراخيص الآبار')

@push('styles')
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
                    <h1>قائمة تراخيص الآبار</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تراخيص الآبار</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
    <div class="col-12">
        {{-- قسم الاستيراد --}}
        <div class="card card-success collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-excel mr-1"></i> استيراد / تصدير</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <h4>استيراد من ملف Excel</h4>
                        <p class="text-muted">يمكنك استيراد قائمة بالتراخيص دفعة واحدة.</p>
                        <form action="{{ route('dashboard.well-licenses.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="importFile" name="file" required>
                                    <label class="custom-file-label" for="importFile">اختر ملف Excel</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-upload mr-1"></i> بدء الاستيراد</button>
                            {{-- زر تحميل القالب --}}
                            <a href="{{ asset('templates/well_licenses_template.csv') }}" class="btn btn-info" download>
                                <i class="fas fa-download mr-1"></i> تحميل القالب
                            </a>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h4>تصدير إلى ملف Excel</h4>
                        <p class="text-muted">تصدير جميع تراخيص الآبار الموجودة في قاعدة البيانات إلى ملف إكسل.</p>
                        <a href="{{ route('dashboard.well-licenses.export') }}" class="btn btn-primary">
                            <i class="fas fa-file-export mr-1"></i> تصدير الكل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- تم دمج الفلترة مع الجدول مباشرة --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-contract mr-1"></i>
                                عرض تراخيص الآبار <span class="badge badge-primary ml-2">{{ $wellLicenses->count() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                @can('well_licenses.create')
                                    <a href="{{ route('dashboard.well-licenses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i> إضافة ترخيص
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
                            @if (session('error'))
                            <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> خطأ!</h5>
                            {!! session('error') !!}
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table id="licensesTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود الأرشفة</th>
                                            <th>رقم كتاب الموارد المائية</th>
                                            <th>رقم العقار</th>
                                            <th>مقدم الطلب</th>
                                            <th>نوع الطلب</th>
                                            <th>تاريخ كتاب المؤسسة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($wellLicenses as $license)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $license->archive_code }}</td>
                                                <td>{{ $license->directorate_letter_number }}</td>
                                                <td>{{ $license->property_number }}</td>
                                                <td>{{ $license->applicant_name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $license->request_type }}</span>
                                                </td>
                                                <td>{{ $license->institution_letter_date ? $license->institution_letter_date->format('Y-m-d') : 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        @can('well_licenses.view')
                                                        <a href="{{ route('dashboard.well-licenses.show', $license->id) }}" class="btn btn-sm btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @endcan

                                                        @can('well_licenses.edit')
                                                        <a href="{{ route('dashboard.well-licenses.edit', $license->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endcan

                                                        @can('well_licenses.delete')
                                                        <form action="{{ route('dashboard.well-licenses.destroy', $license->id) }}" method="POST" class="d-inline delete-form">
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
                                                <td colspan="7" class="text-center">لا توجد تراخيص لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- تم إزالة روابط الترقيم الخاصة بـ Laravel --}}
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
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();
            // تهيئة DataTable مع تفعيل جميع ميزاتها
            var table = $("#licensesTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "paging": true,
                "searching": true, // تفعيل البحث الفوري
                "ordering": true,
                "info": true,
                "language": {
                    "url": "{{ asset('datatable-lang/ar.json') }}",
                },
                "buttons": [
                    {
                        extend: 'collection',
                        text: 'تصدير',
                        className: 'btn-dark',
                        buttons: ['copy', 'excel', 'csv', 'pdf', 'print']
                    },
                    {
                        extend: 'colvis',
                        text: 'إظهار/إخفاء الأعمدة'
                    }
                ]
            }).buttons().container().appendTo('#licensesTable_wrapper .col-md-6:eq(0)');

            // تفعيل SweetAlert2 لتأكيد الحذف
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
