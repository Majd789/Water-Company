@extends('layouts.app')
@section('title', 'إضافة مهمة مقاول جديدة')

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
                <h1 class="m-0">إضافة مهمة مقاول جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor-tasks.index') }}">مهام المقاولين</a></li>
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
                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-not-equal text-danger ml-2"></i>حالة التطابق</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="is_discrepant" name="is_discrepant" value="1" {{ old('is_discrepant') ? 'checked' : '' }}>
                                    <label for="is_discrepant" class="custom-control-label">هذه المهمة غير مطابقة للنشاط الرسمي المرتبطة به</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="discrepancy_notes_container" style="{{ old('is_discrepant') ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="discrepancy_notes">شرح سبب عدم التطابق<span class="text-danger">*</span></label>
                                <textarea name="discrepancy_notes" id="discrepancy_notes" class="form-control" rows="3" placeholder="مثال: تم تنفيذ النشاط في موقع مختلف، تم تغيير كميات المواد، ...">{{ old('discrepancy_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                @include('dashboard.partials.alerts')

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات المهمة الجديدة
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.contractor-tasks.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. الربط الأساسي --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-link text-primary ml-2"></i>الربط الأساسي</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="task_code">كود المهمة<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="task_code" name="task_code"
                                               placeholder="أدخل كوداً فريداً للمهمة (مثال: TASK-00001)" value="{{ old('task_code') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                <div class="form-group">
                                    <label for="project_selector">المشروع<span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="project_selector">
                                        <option value="" disabled selected>-- الخطوة 1: اختر المشروع --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">
                                                {{ $project->name }} ({{ $project->project_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                                        {{-- الخطوة 2: اختر النشاط (سيتم ملؤه بواسطة JS) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_activity_id">النشاط التابع له<span class="text-danger">*</span></label>
                                    <select name="project_activity_id" class="form-control select2" id="project_activity_id" required disabled>
                                        <option value="" disabled selected>-- الخطوة 2: اختر النشاط --</option>
                                    </select>
                                </div>
                            </div>
                                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_contractor_id">عقد المقاول<span class="text-danger">*</span></label>
                                    <select name="project_contractor_id" class="form-control select2" id="project_contractor_id" required disabled>
                                        <option value="" disabled selected>-- الخطوة 3: اختر عقد المقاول --</option>
                                    </select>
                                </div>
                            </div>
                            </div>

                            {{-- 2. تفاصيل المهمة --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-info-circle text-success ml-2"></i>تفاصيل المهمة</h5>
                            <div class="row">
                                <div class="col-12">
                                     <div class="form-group">
                                        <label for="description">وصف المهمة</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="وصف تفصيلي للمهمة التي سيقوم بها المقاول">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity">الكمية</label>
                                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" placeholder="مثال: 50" value="{{ old('quantity') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit_measure">الواحدة</label>
                                        <input type="text" class="form-control" id="unit_measure" name="unit_measure" placeholder="مثال: نقطة صيانة، متر" value="{{ old('unit_measure') }}">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost">التكلفة</label>
                                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="بالدولار" value="{{ old('cost') }}">
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
                                حفظ المهمة
                            </button>
                            <a href="{{ route('dashboard.contractor-tasks.index') }}" class="btn btn-secondary btn-lg">
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

            const projectSelector = $('#project_selector');
            const activitySelector = $('#project_activity_id');
            const contractorSelector = $('#project_contractor_id');

            // دالة لتحديث القوائم المنسدلة
            function updateRelatedData(projectId, selectedActivityId = null, selectedContractorId = null) {
                if (!projectId) {
                    activitySelector.empty().append('<option value="" disabled selected>-- اختر النشاط --</option>').prop('disabled', true);
                    contractorSelector.empty().append('<option value="" disabled selected>-- اختر عقد المقاول --</option>').prop('disabled', true);
                    return;
                }

                // عرض مؤشر التحميل
                activitySelector.empty().append('<option>جار التحميل...</option>').prop('disabled', true);
                contractorSelector.empty().append('<option>جار التحميل...</option>').prop('disabled', true);

                // طلب AJAX لجلب البيانات
                $.ajax({
                    url: `/dashboard/projects/${projectId}/related-data`, // استخدام المسار الذي أنشأناه
                    type: 'GET',
                    success: function(data) {
                        // ملء قائمة الأنشطة
                        activitySelector.empty().append('<option value="" disabled selected>-- اختر النشاط --</option>').prop('disabled', false);
                        $.each(data.activities, function(index, activity) {
                            activitySelector.append($('<option>', {
                                value: activity.id,
                                text: activity.text
                            }));
                        });

                        // ملء قائمة عقود المقاولين
                        contractorSelector.empty().append('<option value="" disabled selected>-- اختر عقد المقاول --</option>').prop('disabled', false);
                        $.each(data.contractors, function(index, contractor) {
                            contractorSelector.append($('<option>', {
                                value: contractor.id,
                                text: contractor.text
                            }));
                        });

                        // في حالة التعديل، قم بتحديد القيم المحفوظة
                        if (selectedActivityId) {
                            activitySelector.val(selectedActivityId).trigger('change');
                        }
                        if (selectedContractorId) {
                            contractorSelector.val(selectedContractorId).trigger('change');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ أثناء تحميل البيانات.');
                    }
                });
            }

            // عند تغيير المشروع
            projectSelector.on('change', function() {
                updateRelatedData($(this).val());
            });

            // ===============================================
            // === كود خاص بصفحة التعديل فقط (edit.blade.php) ===
            // ===============================================
            @if(isset($contractorTask))
                // عند تحميل الصفحة، حدد المشروع الحالي وقم بتحميل البيانات المرتبطة به
                const initialProjectId = '{{ $contractorTask->projectActivity->project_id ?? '' }}';
                if (initialProjectId) {
                    projectSelector.val(initialProjectId).trigger('change');
                    // قم باستدعاء الدالة مع تمرير القيم المحفوظة
                    updateRelatedData(initialProjectId, '{{ $contractorTask->project_activity_id }}', '{{ $contractorTask->project_contractor_id }}');
                }
            @endif
        });
    </script>
@endpush
