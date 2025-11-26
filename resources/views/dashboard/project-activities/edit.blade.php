@extends('layouts.app')
@section('title', 'تعديل نشاط مشروع')

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
                <h1 class="m-0">تعديل نشاط: <span class="text-primary">{{ $projectActivity->activity_code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.project-activities.index') }}">أنشطة المشاريع</a></li>
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

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات النشاط
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.project-activities.update', $projectActivity->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                           {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="activity_code">كود النشاط<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="activity_code" name="activity_code"
                                               placeholder="أدخل كوداً فريداً للنشاط" value="{{ old('activity_code', $projectActivity->activity_code) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_id">المشروع التابع له<span class="text-danger">*</span></label>
                                        <select name="project_id" class="form-control select2" id="project_id" required>
                                            <option value="" disabled>-- اختر المشروع --</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}" {{ old('project_id', $projectActivity->project_id) == $project->id ? 'selected' : '' }}>
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
                                            <option value="" disabled>-- اختر نوع النشاط من القائمة الرئيسية --</option>
                                            @foreach ($masterActivities as $masterActivity)
                                                {{-- إضافة data-unit --}}
                                                <option value="{{ $masterActivity->id }}"
                                                        data-unit="{{ $masterActivity->unit }}"
                                                        {{ old('master_activity_id', $projectActivity->master_activity_id) == $masterActivity->id ? 'selected' : '' }}>
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
                                            <option value="" disabled>-- اختر الوحدة --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id', $projectActivity->unit_id) == $unit->id ? 'selected' : '' }}>
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
                                            <option value="" disabled>-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}" {{ old('station_id', $projectActivity->station_id) == $station->id ? 'selected' : '' }}>
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
                                               placeholder="اسم القرية أو وصف دقيق للموقع" value="{{ old('village_name', $projectActivity->village_name) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الكميات والتكلفة --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-calculator text-info ml-2"></i>الكميات والتكلفة</h5>
                            <div class="row">
                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity">العدد / الكمية</label>
                                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" placeholder="مثال: 500" value="{{ old('quantity', $projectActivity->quantity) }}">
                                    </div>
                                </div>
                               <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="unit_measure">الواحدة</label>
                                            {{-- يفضل جعلها readonly لضمان تطابقها مع نوع النشاط --}}
                                            <input type="text" class="form-control" id="unit_measure" name="unit_measure"
                                                placeholder="مثال: متر، قطعة" value="{{ old('unit_measure', $projectActivity->unit_measure) }}" readonly>
                                        </div>
                                    </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost">التكلفة التقديرية</label>
                                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="بالدولار" value="{{ old('cost', $projectActivity->cost) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">حالة النشاط</label>
                                        <input type="text" class="form-control" id="status" name="status" placeholder="مثال: قيد التنفيذ" value="{{ old('status', $projectActivity->status) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('notes', $projectActivity->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
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
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });

            // ==========================================
            // كود تحديث الواحدة عند التغيير
            // ==========================================
            $('#master_activity_id').on('change', function() {
                var selectedOption = $(this).find(':selected');
                var unit = selectedOption.data('unit');

                if(unit) {
                    $('#unit_measure').val(unit);
                }
            });

            // ملاحظة: في صفحة التعديل، البيانات موجودة بالفعل من قاعدة البيانات،
            // لذلك لا نجبر التحديث عند التحميل (trigger) إلا إذا أردت التأكد من التطابق 100%
            // لكن يفضل تركها كما هي لتجنب مسح أي تعديل يدوي سابق إن وجد.
            // إذا كنت تريد فرض التطابق دائماً، أضف السطر التالي:
            // $('#master_activity_id').trigger('change');
        });
    </script>
@endpush
