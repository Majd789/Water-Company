@extends('layouts.app')
@section('title', 'تعديل مجموعة توليد')

{{-- استيراد مكتبة Select2 و CSS مخصص --}}
@push('styles')
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
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل مجموعة توليد: <span
                        class="text-primary">{{ $generationGroup->generator_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('generation-groups.index') }}">مجموعات التوليد</a></li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات مجموعة التوليد
                        </h3>
                    </div>

                    <form action="{{ route('generation-groups.update', $generationGroup->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- 1. البيانات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>البيانات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابعة لها<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $generationGroup->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generator_name">اسم / ماركة المولدة<span
                                                class="text-danger">*</span></label>
                                        <select name="generator_name" id="generator_name" class="form-control select2"
                                            required>
                                            <option value="">-- اختر اسم المولدة --</option>
                                            @php
                                                $generators = [
                                                    'DOOSAN',
                                                    'PERKINS',
                                                    'SCANIA',
                                                    'VOLVO',
                                                    'CATERPILLAR',
                                                    'TEKSAN',
                                                    'CUMMINS',
                                                    'DAEWOO',
                                                    'JOHN DEERE',
                                                    'IVECO',
                                                    'FIAT',
                                                    'EMSA GENERATOR',
                                                    'AKSA',
                                                    'FPT',
                                                    'DORMAN',
                                                    'RICARDO (صيني)',
                                                    'BAUDOUIN',
                                                    'MARKON',
                                                    'KJPOWER',
                                                    'GENPOWER',
                                                    'DODS',
                                                    'AIFO',
                                                    'DOTZ',
                                                    'MAK',
                                                    'MITSUBISHI / MITORELA',
                                                    'IDEA',
                                                    'COELMO',
                                                    'غير معروف',
                                                ];
                                            @endphp
                                            @foreach ($generators as $generator)
                                                <option value="{{ $generator }}"
                                                    {{ old('generator_name', $generationGroup->generator_name) == $generator ? 'selected' : '' }}>
                                                    {{ $generator }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generation_capacity">استطاعة التوليد (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="number" step="0.01" name="generation_capacity"
                                                class="form-control"
                                                value="{{ old('generation_capacity', $generationGroup->generation_capacity) }}"
                                                placeholder="أدخل استطاعة التوليد" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_operating_capacity">استطاعة العمل الفعلية (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" name="actual_operating_capacity"
                                                class="form-control"
                                                value="{{ old('actual_operating_capacity', $generationGroup->actual_operating_capacity) }}"
                                                placeholder="أدخل استطاعة العمل الفعلية" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. البيانات التشغيلية --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-success ml-2"></i>البيانات التشغيلية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fuel_consumption">استهلاك الوقود (لتر/ساعة)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-gas-pump"></i></span></div>
                                            <input type="number" step="0.01" name="fuel_consumption"
                                                class="form-control"
                                                value="{{ old('fuel_consumption', $generationGroup->fuel_consumption) }}"
                                                placeholder="أدخل استهلاك الوقود" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generation_group_readiness_percentage">نسبة الجاهزية (%)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" step="0.01"
                                                name="generation_group_readiness_percentage" class="form-control"
                                                value="{{ old('generation_group_readiness_percentage', $generationGroup->generation_group_readiness_percentage) }}"
                                                placeholder="أدخل نسبة الجاهزية (0-100)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oil_usage_duration">مدة استخدام الزيت (ساعة)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-clock"></i></span></div>
                                            <input type="number" name="oil_usage_duration" class="form-control"
                                                value="{{ old('oil_usage_duration', $generationGroup->oil_usage_duration) }}"
                                                placeholder="أدخل مدة استخدام الزيت" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oil_quantity_for_replacement">كمية الزيت للتبديل (لتر)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-oil-can"></i></span></div>
                                            <input type="number" step="0.01" name="oil_quantity_for_replacement"
                                                class="form-control"
                                                value="{{ old('oil_quantity_for_replacement', $generationGroup->oil_quantity_for_replacement) }}"
                                                placeholder="أدخل كمية الزيت" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operational_status">الوضع التشغيلي<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="operational_status" id="operational_status"
                                                class="form-control" required>
                                                <option value="عاملة"
                                                    {{ old('operational_status', $generationGroup->operational_status) == 'عاملة' ? 'selected' : '' }}>
                                                    عاملة</option>
                                                <option value="متوقفة"
                                                    {{ old('operational_status', $generationGroup->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                                    متوقفة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="stop_reason_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="stop_reason">سبب التوقف</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span></div>
                                            <input type="text" name="stop_reason" class="form-control"
                                                placeholder="أدخل سبب التوقف"
                                                value="{{ old('stop_reason', $generationGroup->stop_reason) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات عامة</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية">{{ old('notes', $generationGroup->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('generation-groups.index') }}" class="btn btn-secondary btn-lg">
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
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });

            // إظهار/إخفاء حقل سبب التوقف
            const statusSelect = $('#operational_status');
            const reasonContainer = $('#stop_reason_container');

            function toggleReasonField() {
                if (statusSelect.val() === 'متوقفة') {
                    reasonContainer.slideDown();
                } else {
                    reasonContainer.slideUp();
                }
            }

            statusSelect.on('change', toggleReasonField);

            // التشغيل عند تحميل الصفحة لضمان عرض الحالة الصحيحة
            toggleReasonField();
        });
    </script>
@endpush
