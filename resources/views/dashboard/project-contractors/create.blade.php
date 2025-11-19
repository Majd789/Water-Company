@extends('layouts.app')
@section('title', 'إضافة عقد مقاول جديد')

@push('styles')
    {{-- Select2 CSS ---}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .form-control:not(:placeholder-shown):invalid { border-color: #dc3545 !important; }
        .form-control:not(:placeholder-shown):valid { border-color: #28a745 !important; }
        .select2-container--bootstrap4 .select2-selection { transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; }
        .form-control.is-valid + .select2-container--bootstrap4 .select2-selection { border-color: #28a745 !important; }
        .form-control.is-invalid + .select2-container--bootstrap4 .select2-selection { border-color: #dc3545 !important; }
        .section-title { border-bottom: 1px solid #ddd; padding-bottom: 10px; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة عقد مقاول جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.project-contractors.index') }}">عقود المقاولين</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                @include('dashboard.partials.alerts')

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات العقد الجديد
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.project-contractors.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contract_code">كود العقد<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="contract_code" name="contract_code"
                                               placeholder="أدخل كوداً فريداً للعقد" value="{{ old('contract_code') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_id">المشروع<span class="text-danger">*</span></label>
                                        <select name="project_id" class="form-control select2" id="project_id" required>
                                            <option value="" disabled selected>-- اختر المشروع --</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                    {{ $project->name }} ({{ $project->project_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" type="checkbox" id="direct_implementation">
                                        <label for="direct_implementation" class="custom-control-label">هذا عقد تنفيذ مباشر (بدون مقاول خارجي)</label>
                                    </div>
                                        <label for="contractor_id">المقاول<span class="text-danger">*</span></label>
                                        <select name="contractor_id" class="form-control select2" id="contractor_id" required>
                                            <option value="" disabled selected>-- اختر المقاول --</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}" {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                                    {{ $contractor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. التفاصيل المالية والزمنية --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-dollar-sign text-success ml-2"></i>التفاصيل المالية والزمنية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="value">قيمة العقد</label>
                                        <input type="number" step="0.01" class="form-control" id="value" name="value"
                                               placeholder="أدخل قيمة العقد بالدولار" value="{{ old('value') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contract_date">تاريخ العقد</label>
                                        <input type="date" class="form-control" id="contract_date" name="contract_date" value="{{ old('contract_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duration_days">مدة التنفيذ (باليوم)</label>
                                        <input type="number" class="form-control" id="duration_days" name="duration_days"
                                               placeholder="مثال: 60" value="{{ old('duration_days') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">تاريخ بداية التنفيذ</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">تاريخ نهاية التنفيذ</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الحالة والموافقات --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-stamp text-info ml-2"></i>الحالة والموافقات</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="contractor_status_id">حالة التنفيذ<span class="text-danger">*</span></label>
                                        <select name="contractor_status_id" class="form-control select2" id="contractor_status_id" required>
                                            <option value="" disabled selected>-- اختر حالة التنفيذ --</option>
                                            @foreach ($contractorStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('contractor_status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="org_approval_number">رقم موافقة المنظمة</label>
                                        <input type="text" class="form-control" id="org_approval_number" name="org_approval_number"
                                               placeholder="أدخل رقم الموافقة" value="{{ old('org_approval_number') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="org_approval_date">تاريخ كتاب الموافقة</label>
                                        <input type="date" class="form-control" id="org_approval_date" name="org_approval_date" value="{{ old('org_approval_date') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ العقد
                            </button>
                            <a href="{{ route('dashboard.project-contractors.index') }}" class="btn btn-secondary btn-lg">
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
