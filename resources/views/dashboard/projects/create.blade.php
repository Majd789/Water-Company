@extends('layouts.app')
@section('title', 'إضافة مشروع جديد')

@push('styles')
    {{-- Select2 CSS ---}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }
        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }
        .select2-container--bootstrap4 .select2-selection {
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .form-control.is-valid + .select2-container--bootstrap4 .select2-selection {
            border-color: #28a745 !important;
        }
        .form-control.is-invalid + .select2-container--bootstrap4 .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة مشروع جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.index') }}">المشاريع</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">

                @include('dashboard.partials.alerts')

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات المشروع الجديد
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.projects.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">اسم المشروع<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               placeholder="أدخل اسم المشروع" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                               <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_code">كود المشروع</label>
                                    <input type="text" class="form-control" id="project_code" name="project_code"
                                        placeholder="سيتم توليده تلقائياً عند الحفظ" readonly>
                                    <small class="form-text text-muted">يمكنك إدخال كود يدوي إذا لزم الأمر، وإلا سيتم إنشاؤه تلقائياً.</small>
                                </div>
                            </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="organization_id">المنظمة الداعمة<span class="text-danger">*</span></label>
                                        <select name="organization_id" class="form-control select2" id="organization_id" required>
                                            <option value="" disabled selected>-- اختر المنظمة --</option>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
                                                    {{ $organization->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="donor_name">الجهة المانحة</label>
                                        <input type="text" class="form-control" id="donor_name" name="donor_name"
                                               placeholder="أدخل اسم الجهة المانحة" value="{{ old('donor_name') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 2. معلومات المشرف والتصنيف --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-user-tie text-success ml-2"></i>معلومات المشرف والتصنيف</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisor_name">اسم المشرف</label>
                                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name"
                                               placeholder="أدخل اسم المشرف" value="{{ old('supervisor_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisor_phone">رقم تواصل المشرف</label>
                                        <input type="text" class="form-control" id="supervisor_phone" name="supervisor_phone"
                                               placeholder="أدخل رقم التواصل" value="{{ old('supervisor_phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="project_type_id">نوع المشروع<span class="text-danger">*</span></label>
                                        <select name="project_type_id" class="form-control select2" id="project_type_id" required>
                                            <option value="" disabled selected>-- اختر نوع المشروع --</option>
                                            @foreach ($projectTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('project_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الحالة والتواريخ --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-tasks text-info ml-2"></i>الحالة والتواريخ</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="main_status_id">حالة المشروع (الرئيسية)<span class="text-danger">*</span></label>
                                        <select name="main_status_id" class="form-control select2" id="main_status_id" required>
                                            @foreach ($mainStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('main_status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="general_status_id">حالة المشروع (العامة)<span class="text-danger">*</span></label>
                                        <select name="general_status_id" class="form-control select2" id="general_status_id" required>
                                            @foreach ($generalStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('general_status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">تاريخ البداية (الرئيسي)</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">تاريخ النهاية (الرئيسي)</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 4. معلومات إضافية --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-file-alt text-warning ml-2"></i>معلومات إضافية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_value">قيمة العقد الإجمالية</label>
                                        <input type="number" step="0.01" class="form-control" id="total_value" name="total_value"
                                               placeholder="أدخل القيمة الإجمالية" value="{{ old('total_value') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="handover_status_id">محضر التسليم<span class="text-danger">*</span></label>
                                        <select name="handover_status_id" class="form-control select2" id="handover_status_id" required>
                                            @foreach ($handoverStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('handover_status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">الملاحظات العامة</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ المشروع
                            </button>
                            <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });
        });
    </script>
@endpush
