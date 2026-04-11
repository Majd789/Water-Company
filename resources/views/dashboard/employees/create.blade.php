@extends('layouts.app')

@section('title', 'إضافة موظف جديد')

@push('styles')
    <style>
        .form-control:not(:placeholder-shown):invalid { border-color: #dc3545 !important; }
        .form-control:not(:placeholder-shown):valid { border-color: #28a745 !important; }
        .select2-container--bootstrap4 .select2-selection { transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-primary"><i class="fas fa-user-plus ml-2"></i>إضافة موظف جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.employees.index') }}">الموظفين</a></li>
                    <li class="breadcrumb-item active">إضافة موظف</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">

                {{-- عرض أخطاء التحقق --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> تنبيه! هناك أخطاء في البيانات</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">إدخال البيانات الأساسية والوظيفية</h3>
                    </div>

                    <form action="{{ route('dashboard.employees.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="card-body">

                            {{-- 1. المعلومات الشخصية --}}
                            <h5 class="mb-3 text-primary" style="border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 5px;">
                                <i class="fas fa-id-card ml-1"></i> المعلومات الأساسية
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">الاسم الكامل للموظف<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                            <input type="text" name="full_name" id="full_name" class="form-control"
                                                value="{{ old('full_name') }}" placeholder="أدخل الاسم الثلاثي" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_code">الرقم الوظيفي (الكود)<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-barcode"></i></span></div>
                                            <input type="text" name="employee_code" id="employee_code" class="form-control"
                                                value="{{ old('employee_code') }}" placeholder="مثال: EMP-1001" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. البيانات الإدارية --}}
                            <h5 class="mt-4 mb-3 text-primary" style="border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 5px;">
                                <i class="fas fa-sitemap ml-1"></i> البيانات الإدارية
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_id">الوحدة التنظيمية / القسم<span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-control select2" required>
                                            <option value="" selected disabled>-- اختر الوحدة --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_allowed_days">الرصيد السنوي المتاح (يوم)<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-umbrella-beach"></i></span></div>
                                            <input type="number" name="total_allowed_days" id="total_allowed_days" class="form-control"
                                                value="{{ old('total_allowed_days', 30) }}" min="0" required>
                                        </div>
                                        <small class="text-muted">سيتم تعيين الرصيد المتبقي مساوياً لهذا الرقم تلقائياً.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active">حالة العمل</label>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">الموظف على رأس عمله حالياً</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save ml-1"></i> حفظ الموظف
                            </button>
                            <a href="{{ route('dashboard.employees.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "إختر الوحدة"
            });
        });
    </script>
@endpush
