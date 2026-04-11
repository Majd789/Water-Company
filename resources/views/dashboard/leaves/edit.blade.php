@extends('layouts.app')

@section('title', 'تعديل تفاصيل الإجازة')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        /* تنسيق خاص للحقول التي لا يمكن تعديلها */
        .form-control[readonly], .form-control[disabled] {
            background-color: #f4f6f9;
            opacity: 1;
        }
        .select2-container--bootstrap4.select2-container--disabled .select2-selection--single {
            background-color: #f4f6f9 !important;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تحديث بيانات الإجازة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.leaves.index') }}">الإجازات</a></li>
                        <li class="breadcrumb-item active">تعديل إجازة</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    {{-- بطاقة التعديل باللون البرتقالي (Warning) للتمييز عن الإنشاء --}}
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-warning">
                                <i class="fas fa-edit mr-1"></i>
                                تعديل بيانات الإجازة للموظف: {{ $leave->employee->full_name }}
                            </h3>
                        </div>

                        {{-- لاحظ الـ PUT ميثود وتفعيل خاصية enctype لرفع الملفات --}}
                        <form action="{{ route('dashboard.leaves.update', $leave->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="icon fas fa-ban"></i> خطأ! هناك أخطاء في البيانات</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    {{-- اسم الموظف - للقراءة فقط --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>اسم الموظف</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                                <input type="text" class="form-control" value="{{ $leave->employee->full_name }}" readonly disabled>
                                                {{-- إرسال الـ ID مخفياً لتجاوز تعطيل الحقل --}}
                                                <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- نوع الإجازة - للقراءة فقط --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>نوع الإجازة</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text {{ $leave->type->affects_balance ? 'bg-warning' : 'bg-info' }}">
                                                        <i class="fas fa-calendar-check text-white"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" value="{{ $leave->type->type_name }}" readonly disabled>
                                                {{-- إرسال الـ ID مخفياً لتجاوز تعطيل الحقل --}}
                                                <input type="hidden" name="leave_type_id" value="{{ $leave->leave_type_id }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- تاريخ البدء - للقراءة فقط --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ البدء</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-clock"></i></span></div>
                                                <input type="date" name="start_date" value="{{ old('start_date', $leave->start_date) }}" class="form-control" readonly required>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- تاريخ الانتهاء - للقراءة فقط --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ الانتهاء</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-clock"></i></span></div>
                                                <input type="date" name="end_date" value="{{ old('end_date', $leave->end_date) }}" class="form-control" readonly required>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- المدة - للقراءة فقط --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>المدة المحسوبة (يوم)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend bg-light rounded-left border border-right-0"><span class="input-group-text bg-transparent text-secondary"><i class="fas fa-hourglass-half"></i></span></div>
                                                <input type="text" class="form-control text-center font-weight-bold" value="{{ $leave->duration }}" readonly disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="dropdown-divider my-4"></div>
                                    </div>

                                    {{-- السبب - قابل للتعديل --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>السبب / الملاحظات للتعديل</label>
                                            <textarea name="reason" rows="4" class="form-control" placeholder="يمكنك تحديث السبب أو الملاحظات هنا...">{{ old('reason', $leave->reason) }}</textarea>
                                        </div>
                                    </div>

                                    {{-- المرفقات - قابل للتعديل --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">تحديث المستند المرفق (تقرير طبي / طلب ورقي)</label>

                                            {{-- عرض الملف الحالي إن وجد --}}
                                            @if ($leave->attachment_path)
                                                <div class="mb-2">
                                                    <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                        <i class="fas fa-paperclip mr-1"></i> عرض المرفق الحالي
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="attachment" class="custom-file-input" id="attachment">
                                                    <label class="custom-file-label" for="attachment">اختر ملفاً جديداً لاستبدال القديم...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-warning px-4 text-white font-weight-bold">
                                    <i class="fas fa-sync-alt mr-1"></i> تحديث وحفظ التغييرات
                                1</button>
                                <a href="{{ route('dashboard.leaves.index') }}" class="btn btn-default px-4">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Select2 JS --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- BS-Custom-File-Input لظهور اسم الملف عند الاختيار --}}
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function () {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });

            // تفعيل إضافة اسم الملف في الحقل مخصص
            bsCustomFileInput.init();
        });
    </script>
@endpush
