@extends('layouts.app')

@section('title', 'تسجيل إجازة جديدة')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تسجيل إجازة للموظف</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.leaves.index') }}">الإجازات</a></li>
                        <li class="breadcrumb-item active">طلب جديد</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-1"></i>
                                تفاصيل طلب الإجازة
                            </h3>
                        </div>

                        <form action="{{ route('dashboard.leaves.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="icon fas fa-ban"></i> خطأ!</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    {{-- اختيار الموظف --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="employee_id">اسم الموظف <span class="text-danger">*</span></label>
                                            <select name="employee_id" id="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" style="width: 100%;">
                                                <option value="">-- اختر الموظف --</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }} (رصيد: {{ $employee->remaining_days }} يوم)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- نوع الإجازة --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="leave_type">نوع الإجازة <span class="text-danger">*</span></label>
                                            <select name="leave_type_id" id="leave_type" class="form-control select2 @error('leave_type_id') is-invalid @enderror">
                                                <option value="">-- اختر النوع --</option>
                                                @foreach($leaveTypes as $type)
                                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                                        {{ $type->type_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- عرض الرصيد بشكل تفاعلي (اختياري) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>حالة الرصيد</label>
                                            <div class="info-box bg-light shadow-none border" style="min-height: 38px;">
                                                <span class="info-box-text text-muted p-2">اختر موظفاً لعرض الرصيد المتبقي...</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- تاريخ البدء --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>تاريخ البدء <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror">
                                        </div>
                                    </div>

                                    {{-- تاريخ الانتهاء --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>تاريخ الانتهاء <span class="text-danger">*</span></label>
                                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control @error('end_date') is-invalid @enderror">
                                        </div>
                                    </div>

                                    {{-- السبب --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>السبب / الملاحظات</label>
                                            <textarea name="reason" rows="3" class="form-control" placeholder="اكتب سبباً مختصراً للطلب...">{{ old('reason') }}</textarea>
                                        </div>
                                    </div>

                                    {{-- المرفقات --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">إرفاق مستند (تقرير طبي / طلب ورقي)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="attachment" class="custom-file-input" id="attachment">
                                                    <label class="custom-file-label" for="attachment">اختر الملف</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-1"></i> حفظ وتسجيل الإجازة
                                </button>
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
