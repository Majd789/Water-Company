@extends('layouts.app')
@section('title', 'إضافة نشاط مشروع جديد')

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
                <h1 class="m-0">إضافة نشاط مشروع جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.project-activities.index') }}">أنشطة المشاريع</a></li>
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
                            بيانات النشاط الجديد
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.project-activities.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="activity_code">كود النشاط (تلقائي)</label>
                                        {{-- تم إضافة readonly واستخدام المتغير nextCode --}}
                                        <input type="text" class="form-control" id="activity_code" name="activity_code"
                                            value="{{ $nextCode }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                                        <small class="text-muted">يتم توليد هذا الكود تلقائياً ولا يمكن تعديله.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_id">المشروع التابع له<span class="text-danger">*</span></label>
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
                                        <label for="master_activity_id">نوع النشاط<span class="text-danger">*</span></label>
                                        <select name="master_activity_id" class="form-control select2" id="master_activity_id" required>
                                            <option value="" disabled selected>-- اختر نوع النشاط من القائمة الرئيسية --</option>
                                            @foreach ($masterActivities as $masterActivity)
                                                <option value="{{ $masterActivity->id }}" {{ old('master_activity_id') == $masterActivity->id ? 'selected' : '' }}>
                                                    {{ $masterActivity->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. الموقع المستهدف --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-map-marked-alt text-success ml-2"></i>الموقع المستهدف</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_id">الوحدة الإدارية<span class="text-danger">*</span></label>
                                        <select name="unit_id" class="form-control select2" id="unit_id" required>
                                            <option value="" disabled selected>-- اختر الوحدة --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" class="form-control select2" id="station_id" required>
                                            <option value="" disabled selected>-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="village_name">القرية/الموقع المحدد</label>
                                        <input type="text" class="form-control" id="village_name" name="village_name"
                                               placeholder="اسم القرية أو وصف دقيق للموقع" value="{{ old('village_name') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الكميات والتكلفة --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-calculator text-info ml-2"></i>الكميات والتكلفة</h5>
                            <div class="row">
                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity">العدد / الكمية</label>
                                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" placeholder="مثال: 500" value="{{ old('quantity') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit_measure">الواحدة</label>
                                        <input type="text" class="form-control" id="unit_measure" name="unit_measure" placeholder="مثال: متر، قطعة" value="{{ old('unit_measure') }}">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost">التكلفة التقديرية</label>
                                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="بالدولار" value="{{ old('cost') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">حالة النشاط</label>
                                        <input type="text" class="form-control" id="status" name="status" placeholder="مثال: قيد التنفيذ" value="{{ old('status') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ النشاط
                            </button>
                            <a href="{{ route('dashboard.project-activities.index') }}" class="btn btn-secondary btn-lg">
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
