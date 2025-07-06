@extends('layouts.app')
@section('title', 'إضافة وحدة جديدة')

{{-- استيراد مكتبة Select2 --}}
{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <style>
        /* لا يتم تطبيق الألوان إلا بعد أن يبدأ المستخدم بالكتابة */
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545;
            /* أحمر bootstrap */
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745;
            /* أخضر bootstrap */
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endpush

{{-- قسم رأس المحتوى لإضافة عنوان ومسافة علوية --}}
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة وحدة جديدة</h1>
            </div>
        </div>
    </div>
@endsection


@section('content')
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

                <!-- الفورم الرئيسي لإضافة وحدة جديدة -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الوحدة
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('units.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- اسم الوحدة -->
                                    <div class="form-group">
                                        <label for="unit_name">اسم الوحدة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="unit_name" name="unit_name"
                                                placeholder="أدخل اسم الوحدة" value="{{ old('unit_name') }}" required>
                                        </div>
                                    </div>

                                    <!-- اختيار المحافظة -->
                                    <div class="form-group">
                                        <label for="governorate_id">المحافظة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                                            </div>
                                            <select name="governorate_id" class="form-control select2" id="governorate_id"
                                                required>
                                                <option value="" disabled selected>اختر المحافظة</option>
                                                @foreach ($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}"
                                                        {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <!-- الملاحظات العامة -->
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات العامة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                            </div>
                                            <textarea name="general_notes" class="form-control" id="general_notes" rows="5"
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
                                حفظ الوحدة
                            </button>
                            <a href="{{ route('units.index') }}" class="btn btn-secondary">
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
        });
    </script>
@endpush
