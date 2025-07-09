@extends('layouts.app')
@section('title', 'إضافة بلدة جديدة')

{{-- استيراد مكتبة Select2 --}}
{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <style>
        /* لا يتم تطبيق الألوان إلا بعد أن يبدأ المستخدم بالكتابة */
        .form-control:not(:placeholder-shown):invalid,
        .custom-file-input:not(:placeholder-shown):invalid~.custom-file-label {
            border-color: #dc3545;
            /* أحمر bootstrap */
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control:not(:placeholder-shown):valid,
        .custom-file-input:not(:placeholder-shown):valid~.custom-file-label {
            border-color: #28a745;
            /* أخضر bootstrap */
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endpush

{{-- 1. تم إضافة هذا القسم لإنشاء عنوان ومسافة علوية --}}
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة بلدة جديدة</h1>
            </div>
        </div>
    </div>
@endsection


@section('content')
    {{-- 2. تم إضافة pt-3 لإعطاء مسافة إضافية بسيطة من الأعلى --}}
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <!-- رسائل الحالة والأخطاء -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check ml-1"></i> نجاح!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- قسم استيراد البلدات -->
             
                    <div class="card card-success collapsed-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-excel ml-1"></i>
                                استيراد من ملف Excel
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.towns.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالبلدات دفعة واحدة. يجب أن يحتوي الملف على
                                    الأعمدة: `town_name`, `town_code`, `unit_id`.</p>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="file"
                                            required>
                                        <label class="custom-file-label" for="customFile">اختر ملف Excel</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fas fa-upload ml-1"></i> بدء
                                    الاستيراد</button>
                            </form>
                        </div>
                    </div>
               

                <!-- الفورم الرئيسي لإضافة بلدة جديدة -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{-- 3. تم تغيير العنوان ليكون أكثر تحديدًا --}}
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات البلدة
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.towns.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- اسم البلدة -->
                                    <div class="form-group">
                                        <label for="town_name">اسم البلدة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="town_name" name="town_name"
                                                placeholder="مثال: إدلب" value="{{ old('town_name') }}" required>
                                        </div>
                                    </div>

                                    <!-- كود البلدة -->
                                    <div class="form-group">
                                        <label for="town_code">رمز البلدة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="town_code" name="town_code"
                                                placeholder="مثال: B030600101" value="{{ old('town_code') }}" required>
                                        </div>
                                        <small class="form-text text-muted">يجب أن يكون الرمز فريداً لكل بلدة.</small>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <!-- الوحدة التنظيمية -->
                                    <div class="form-group">
                                        <label for="unit_id">الوحدة التنظيمية</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                            </div>
                                            @if (auth()->user()->unit_id)
                                                <input type="text" class="form-control"
                                                    value="{{ auth()->user()->unit->unit_name }}" readonly>
                                                <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">
                                            @else
                                                <select name="unit_id" class="form-control select2" id="unit_id" required>
                                                    <option value="" disabled selected>اختر الوحدة التابعة لها البلدة
                                                    </option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->unit_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- الملاحظات العامة -->
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات العامة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                            </div>
                                            <textarea name="general_notes" class="form-control" id="general_notes" rows="3"
                                                placeholder="أي ملاحظات إضافية (اختياري)">{{ old('general_notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-1"></i>
                                حفظ البلدة
                            </button>
                            <a href="{{ route('dashboard.towns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                    <!-- نهاية الفورم -->
                </div>
                <!-- /.card -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2 مع دعم كامل للغة العربية
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl"
            });

            // تفعيل bs-custom-file-input لإظهار اسم الملف المختار
            bsCustomFileInput.init();
        });
    </script>
@endpush
