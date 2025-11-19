@extends('layouts.app')
@section('title', 'تعديل مهمة مقاول')

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
                <h1 class="m-0">تعديل مهمة: <span class="text-primary">{{ $contractorTask->task_code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor-tasks.index') }}">مهام المقاولين</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
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
                <h5 class="mt-4 mb-3 section-title"><i class="fas fa-not-equal text-danger ml-2"></i>حالة التطابق</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="is_discrepant" name="is_discrepant" value="1" {{ old('is_discrepant', $contractorTask->is_discrepant) ? 'checked' : '' }}>
                                    <label for="is_discrepant" class="custom-control-label">هذه المهمة غير مطابقة للنشاط الرسمي المرتبطة به</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id="discrepancy_notes_container" style="{{ old('is_discrepant', $contractorTask->is_discrepant) ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="discrepancy_notes">شرح سبب عدم التطابق<span class="text-danger">*</span></label>
                                <textarea name="discrepancy_notes" id="discrepancy_notes" class="form-control" rows="3" placeholder="مثال: تم تنفيذ النشاط في موقع مختلف، تم تغيير كميات المواد، ...">{{ old('discrepancy_notes', $contractorTask->discrepancy_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات المهمة
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.contractor-tasks.update', $contractorTask->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            {{-- 1. الربط الأساسي --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-link text-primary ml-2"></i>الربط الأساسي</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="task_code">كود المهمة<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="task_code" name="task_code"
                                               placeholder="أدخل كوداً فريداً للمهمة" value="{{ old('task_code', $contractorTask->task_code) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_activity_id">النشاط التابع له<span class="text-danger">*</span></label>
                                        <select name="project_activity_id" class="form-control select2" id="project_activity_id" required>
                                            <option value="" disabled>-- اختر النشاط --</option>
                                            @foreach ($projectActivities as $activity)
                                                <option value="{{ $activity->id }}" {{ old('project_activity_id', $contractorTask->project_activity_id) == $activity->id ? 'selected' : '' }}>
                                                    {{ $activity->masterActivity->name ?? 'N/A' }} (مشروع: {{ Str::limit($activity->project->name, 20) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_contractor_id">عقد المقاول<span class="text-danger">*</span></label>
                                        <select name="project_contractor_id" class="form-control select2" id="project_contractor_id" required>
                                            <option value="" disabled>-- اختر عقد المقاول --</option>
                                            @foreach ($projectContractors as $contract)
                                                <option value="{{ $contract->id }}" {{ old('project_contractor_id', $contractorTask->project_contractor_id) == $contract->id ? 'selected' : '' }}>
                                                    {{ $contract->contractor->name ?? 'N/A' }} (عقد: {{ $contract->contract_code }})
                                                </option>
                                            @endforeach
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
                                        <textarea name="description" class="form-control" rows="3" placeholder="وصف تفصيلي للمهمة التي سيقوم بها المقاول">{{ old('description', $contractorTask->description) }}</textarea>
                                    </div>
                                </div>
                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity">الكمية</label>
                                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" placeholder="مثال: 50" value="{{ old('quantity', $contractorTask->quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit_measure">الواحدة</label>
                                        <input type="text" class="form-control" id="unit_measure" name="unit_measure" placeholder="مثال: نقطة صيانة، متر" value="{{ old('unit_measure', $contractorTask->unit_measure) }}">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost">التكلفة</label>
                                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="بالدولار" value="{{ old('cost', $contractorTask->cost) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('notes', $contractorTask->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
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
        });
        $('#is_discrepant').on('change', function() {
    if ($(this).is(':checked')) {
        $('#discrepancy_notes_container').slideDown();
        $('#discrepancy_notes').prop('required', true); // جعل الملاحظات إلزامية عند تحديد عدم التطابق
    } else {
        $('#discrepancy_notes_container').slideUp();
        $('#discrepancy_notes').prop('required', false);
        }
    });
    </script>
@endpush
