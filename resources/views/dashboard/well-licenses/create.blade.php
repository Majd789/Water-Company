@extends('layouts.app')

@section('title', 'إضافة ترخيص بئر جديد')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        /* CSS لتلوين الحقول بشكل تفاعلي عند الإدخال */
        .form-control:not(:placeholder-shown):invalid { border-color: #dc3545 !important; }
        .form-control:not(:placeholder-shown):valid { border-color: #28a745 !important; }
        .select2-container--bootstrap4 .select2-selection { transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; }
        .form-control.is-valid ~ .select2-container--bootstrap4 .select2-selection { border-color: #28a745 !important; }
        .form-control.is-invalid ~ .select2-container--bootstrap4 .select2-selection { border-color: #dc3545 !important; }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>إضافة ترخيص بئر جديد</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.well-licenses.index') }}">تراخيص الآبار</a></li>
                        <li class="breadcrumb-item active">إضافة جديدة</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban mr-1"></i> خطأ!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-edit mr-1"></i>
                                بيانات ترخيص البئر
                            </h3>
                        </div>

                        <form action="{{ route('dashboard.well-licenses.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="card-body">

                                {{-- 1. معلومات الترخيص الأساسية --}}
                                <h5 class="mt-2 mb-3 section-title"><i class="fas fa-file-alt text-primary mr-2"></i>معلومات الترخيص الأساسية</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="archive_code">كود الأرشفة<span class="text-danger">*</span></label>
                                            <input type="text" name="archive_code" class="form-control" value="{{ old('archive_code') }}" placeholder="مثال: 2025-317-01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="applicant_name">اسم مقدم الطلب<span class="text-danger">*</span></label>
                                            <input type="text" name="applicant_name" class="form-control" value="{{ old('applicant_name') }}" placeholder="أدخل اسم مقدم الطلب" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="request_type">نوع الطلب<span class="text-danger">*</span></label>
                                            <select name="request_type" class="form-control select2" required>
                                                <option value="" disabled selected>-- اختر نوع الطلب --</option>
                                                @foreach ($requestTypes as $type)
                                                    <option value="{{ $type }}" {{ old('request_type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. معلومات العقار والموقع --}}
                                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-map-marked-alt text-success mr-2"></i>معلومات العقار والموقع</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="property_number">رقم العقار<span class="text-danger">*</span></label>
                                            <input type="text" name="property_number" class="form-control" value="{{ old('property_number') }}" placeholder="أدخل رقم العقار" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="property_zone">المنطقة العقارية<span class="text-danger">*</span></label>
                                            <input type="text" name="property_zone" class="form-control" value="{{ old('property_zone') }}" placeholder="أدخل المنطقة العقارية" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">خط العرض (Latitude)</label>
                                            <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude') }}" placeholder="مثال: 35.9285">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">خط الطول (Longitude)</label>
                                            <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude') }}" placeholder="مثال: 36.7215">
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. المراسلات والمعلومات الفنية --}}
                                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-exchange-alt text-info mr-2"></i>المراسلات والمعلومات الفنية</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="institution_letter_date">تاريخ كتاب ديوان المؤسسة</label>
                                            <input type="date" name="institution_letter_date" class="form-control" value="{{ old('institution_letter_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="directorate_letter_date">تاريخ كتاب مديرية الموارد</label>
                                            <input type="date" name="directorate_letter_date" class="form-control" value="{{ old('directorate_letter_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="directorate_letter_number">رقم كتاب مديرية الموارد</label>
                                            <input type="text" name="directorate_letter_number" class="form-control" value="{{ old('directorate_letter_number') }}" placeholder="أدخل رقم الكتاب">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="declared_distance_meters">المسافة المصرح بها (متر)</label>
                                            <input type="number" name="declared_distance_meters" class="form-control" value="{{ old('declared_distance_meters') }}" placeholder="أدخل المسافة بالأمتار">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="station_id">أقرب محطة تابعة للمؤسسة</label>
                                            <select name="station_id" class="form-control select2">
                                                <option value="" selected>-- اختر المحطة الأقرب (اختياري) --</option>
                                                @foreach ($stations as $station)
                                                    <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. معلومات الأرشفة المادية --}}
                                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-archive text-warning mr-2"></i>الأرشفة المادية (اختياري)</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="physical_cabinet">الخزانة</label>
                                            <input type="text" name="physical_cabinet" class="form-control" value="{{ old('physical_cabinet') }}" placeholder="رقم أو اسم الخزانة">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="physical_shelf">الرف</label>
                                            <input type="text" name="physical_shelf" class="form-control" value="{{ old('physical_shelf') }}" placeholder="رقم أو اسم الرف">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="physical_file_id">رقم الملف</label>
                                            <input type="text" name="physical_file_id" class="form-control" value="{{ old('physical_file_id') }}" placeholder="رقم الملف على الرف">
                                        </div>
                                    </div>
                                </div>

                                {{-- 5. ملاحظات ورابط الملف --}}
                                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-paperclip text-secondary mr-2"></i>ملاحظات وملفات</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_url">رابط الملف الممسوح</label>
                                            <input type="text" name="file_url" class="form-control" value="{{ old('file_url') }}" placeholder="الصق رابط الملف هنا">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">ملاحظات عامة</label>
                                            <textarea name="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-1"></i> حفظ الترخيص</button>
                                <a href="{{ route('dashboard.well-licenses.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times mr-1"></i> إلغاء</a>
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
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl" // لدعم اللغة العربية بشكل صحيح
            });
        });
    </script>
@endpush
