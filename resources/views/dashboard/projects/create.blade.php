@extends('layouts.app')
@section('title', 'إضافة مشروع جديد')

@push('styles')
    {{-- Select2 CSS --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- Tempus Dominus (Date Picker) CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOm/uuutSJDbkXifOYScSmJuJFm8SrlbVCxtNljm3GdoEdddw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .section-title {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            color: #007bff;
            margin-bottom: 1.5rem !important;
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

                    <form action="{{ route('dashboard.projects.store') }}" method="POST">
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">اسم المشروع<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسم المشروع" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_code">كود المشروع</label>
                                        <input type="text" class="form-control" id="project_code" name="project_code" placeholder="سيتم توليده تلقائياً عند الحفظ" readonly>
                                        <small class="form-text text-muted">سيتم إنشاء هذا الكود بواسطة النظام.</small>
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
                                        <input type="text" class="form-control" id="donor_name" name="donor_name" placeholder="أدخل اسم الجهة المانحة" value="{{ old('donor_name') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 2. معلومات المشرف والتصنيف --}}
                            <h5 class="mt-4 section-title"><i class="fas fa-user-tie text-success ml-2"></i>معلومات المشرف والتصنيف</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisor_name">اسم المشرف</label>
                                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" placeholder="أدخل اسم المشرف" value="{{ old('supervisor_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisor_phone">رقم تواصل المشرف</label>
                                        <input type="text" class="form-control" id="supervisor_phone" name="supervisor_phone" placeholder="أدخل رقم التواصل" value="{{ old('supervisor_phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="project_type_id">نوع المشروع<span class="text-danger">*</span></label>
                                        <select name="project_type_id" class="form-control select2" id="project_type_id" required>
                                            <option value="" disabled selected>-- اختر نوع المشروع --</option>
                                            @foreach ($projectTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('project_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الحالة والتواريخ --}}
                            <h5 class="mt-4 section-title"><i class="fas fa-tasks text-info ml-2"></i>الحالة والتواريخ</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="main_status_id">حالة المشروع (الرئيسية)<span class="text-danger">*</span></label>
                                        <select name="main_status_id" class="form-control select2" id="main_status_id" required>
                                            @foreach ($mainStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('main_status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="general_status_id">حالة المشروع (العامة)<span class="text-danger">*</span></label>
                                        <select name="general_status_id" class="form-control select2" id="general_status_id" required>
                                            @foreach ($generalStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('general_status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contract_date">تاريخ العقد</label>
                                        <div class="input-group date datepicker" id="contract_date_picker" data-target-input="nearest">
                                            <input type="text" name="contract_date" class="form-control datetimepicker-input" data-target="#contract_date_picker" value="{{ old('contract_date') }}"/>
                                            <div class="input-group-append" data-target="#contract_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_duration_days">مدة المشروع الإجمالية (يوم)</label>
                                        <input type="number" class="form-control" id="total_duration_days" name="total_duration_days" placeholder="مثال: 90" value="{{ old('total_duration_days') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">تاريخ البداية (الرئيسي)</label>
                                        <div class="input-group date datepicker" id="start_date_picker" data-target-input="nearest">
                                            <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start_date_picker" value="{{ old('start_date') }}"/>
                                            <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">تاريخ النهاية (الرئيسي)</label>
                                        <div class="input-group date datepicker" id="end_date_picker" data-target-input="nearest">
                                            <input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#end_date_picker" value="{{ old('end_date') }}"/>
                                            <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. الموافقات الرسمية --}}
                            <h5 class="mt-4 section-title"><i class="fas fa-stamp text-secondary ml-2"></i>الموافقات الرسمية</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hac_issue_number">رقم كتاب HAC</label>
                                        <input type="text" name="hac_issue_number" id="hac_issue_number" class="form-control" placeholder="أدخل رقم الكتاب" value="{{ old('hac_issue_number') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hac_issue_date">تاريخ كتاب HAC</label>
                                        <div class="input-group date datepicker" id="hac_issue_date_picker" data-target-input="nearest">
                                            <input type="text" name="hac_issue_date" class="form-control datetimepicker-input" data-target="#hac_issue_date_picker" value="{{ old('hac_issue_date') }}"/>
                                            <div class="input-group-append" data-target="#hac_issue_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hac_received_date">تاريخ ورود كتاب HAC للديوان</label>
                                        <div class="input-group date datepicker" id="hac_received_date_picker" data-target-input="nearest">
                                            <input type="text" name="hac_received_date" class="form-control datetimepicker-input" data-target="#hac_received_date_picker" value="{{ old('hac_received_date') }}"/>
                                            <div class="input-group-append" data-target="#hac_received_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approval_number">رقم الموافقة</label>
                                        <input type="text" class="form-control" id="approval_number" name="approval_number" placeholder="أدخل رقم الموافقة" value="{{ old('approval_number') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approval_date">تاريخ الموافقة</label>
                                        <div class="input-group date datepicker" id="approval_date_picker" data-target-input="nearest">
                                            <input type="text" name="approval_date" class="form-control datetimepicker-input" data-target="#approval_date_picker" value="{{ old('approval_date') }}"/>
                                            <div class="input-group-append" data-target="#approval_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- 5. معلومات إضافية --}}
                            <h5 class="mt-4 section-title"><i class="fas fa-file-alt text-warning ml-2"></i>معلومات إضافية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_value">قيمة العقد الإجمالية</label>
                                        <input type="number" step="0.01" class="form-control" id="total_value" name="total_value" placeholder="أدخل القيمة الإجمالية" value="{{ old('total_value') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="handover_status_id">محضر التسليم<span class="text-danger">*</span></label>
                                        <select name="handover_status_id" class="form-control select2" id="handover_status_id" required>
                                            @foreach ($handoverStatuses as $status)
                                                <option value="{{ $status->id }}" {{ old('handover_status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
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
    {{-- المكتبات الموجودة مسبقاً --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unfs9yoBCen8nhOTgryNZ/IBCoGPifwiElOL4rB84goGIzkIOvyvoFZDlLUQ7MA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            // 1. تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --",
            });

            // 2. تفعيل Date Picker
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                locale: 'ar',
                buttons: { showToday: true, showClear: true, showClose: true },
                icons: {
                    time: 'far fa-clock', date: 'far fa-calendar', up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down', previous: 'fas fa-chevron-left', next: 'fas fa-chevron-right',
                    today: 'far fa-calendar-check', clear: 'far fa-trash-alt', close: 'fas fa-times'
                }
            });

            // ==========================================
            //  3. كود حساب مدة المشروع تلقائياً
            // ==========================================

            // دالة لحساب الفرق بين التاريخين
            function calculateDuration() {
                // جلب القيم من حقول الإدخال الموجودة داخل الـ div الخاص بالتقويم
                var startVal = $('#start_date_picker input').val();
                var endVal = $('#end_date_picker input').val();

                if (startVal && endVal) {
                    var startDate = moment(startVal, 'YYYY-MM-DD');
                    var endDate = moment(endVal, 'YYYY-MM-DD');

                    // التأكد من صحة التواريخ وأن تاريخ النهاية بعد البداية
                    if (startDate.isValid() && endDate.isValid() && endDate.isSameOrAfter(startDate)) {
                        // حساب الفرق بالأيام
                        var daysDiff = endDate.diff(startDate, 'days');

                        // وضع القيمة في حقل المدة (يمكنك إضافة +1 إذا كنت تريد احتساب يوم البداية أيضاً)
                        $('#total_duration_days').val(daysDiff);
                    } else {
                        // تفريغ الحقل إذا كانت التواريخ غير منطقية
                        $('#total_duration_days').val('');
                    }
                }
            }

            // الاستماع لحدث تغيير التاريخ في كلا التقويمين (start & end)
            $('#start_date_picker, #end_date_picker').on('change.datetimepicker', function(e) {
                calculateDuration();
            });

            // أيضاً الاستماع للكتابة اليدوية (Blur)
            $('#start_date_picker input, #end_date_picker input').on('blur', function() {
                calculateDuration();
            });
        });
    </script>
@endpush
