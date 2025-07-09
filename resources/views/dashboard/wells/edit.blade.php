@extends('layouts.app')
@section('title', 'تعديل بيانات البئر')

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

        .form-control.is-valid~.select2-container--bootstrap4 .select2-selection {
            border-color: #28a745 !important;
        }

        .form-control.is-invalid~.select2-container--bootstrap4 .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل بيانات البئر: <span class="text-primary">{{ $well->well_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.wells.index') }}">الآبار</a></li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">

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
                            بيانات البئر
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.wells.update', $well->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابع لها<span class="text-danger">*</span></label>
                                        <select name="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $well->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                    ({{ $station->town->town_name ?? 'غير محدد' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_name">اسم البئر<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" class="form-control" name="well_name"
                                                placeholder="أدخل اسم البئر"
                                                value="{{ old('well_name', $well->well_name) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town_code">كود البلدة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-barcode"></i></span></div>
                                            <input type="text" class="form-control" name="town_code"
                                                placeholder="أدخل كود البلدة"
                                                value="{{ old('town_code', $well->town_code) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_type">نوع البئر</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-water"></i></span></div>
                                            <select name="well_type" class="form-control">
                                                <option value="">-- اختر نوع البئر --</option>
                                                <option value="جوفي"
                                                    {{ old('well_type', $well->well_type) == 'جوفي' ? 'selected' : '' }}>
                                                    جوفي</option>
                                                <option value="سطحي"
                                                    {{ old('well_type', $well->well_type) == 'سطحي' ? 'selected' : '' }}>
                                                    سطحي</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_status">الوضع التشغيلي</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="well_status" class="form-control" id="well_status">
                                                <option value="">-- اختر الوضع --</option>
                                                <option value="يعمل"
                                                    {{ old('well_status', $well->well_status) == 'يعمل' ? 'selected' : '' }}>
                                                    يعمل</option>
                                                <option value="متوقف"
                                                    {{ old('well_status', $well->well_status) == 'متوقف' ? 'selected' : '' }}>
                                                    متوقف</option>
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
                                            <input type="text" class="form-control" name="stop_reason"
                                                placeholder="أدخل سبب التوقف"
                                                value="{{ old('stop_reason', $well->stop_reason) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات الحفر --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-tools text-success ml-2"></i>بيانات الحفر</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drilling_depth">عمق الحفر (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-vertical"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="drilling_depth"
                                                value="{{ old('drilling_depth', $well->drilling_depth) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_diameter">قطر البئر (بوصة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-circle-notch"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="well_diameter"
                                                value="{{ old('well_diameter', $well->well_diameter) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="static_depth">العمق الستاتيكي (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-vertical"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="static_depth"
                                                value="{{ old('static_depth', $well->static_depth) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dynamic_depth">العمق الديناميكي (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-vertical"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="dynamic_depth"
                                                value="{{ old('dynamic_depth', $well->dynamic_depth) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_flow">تدفق البئر (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" class="form-control" name="well_flow"
                                                value="{{ old('well_flow', $well->well_flow) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. بيانات المضخة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-info ml-2"></i>بيانات المضخة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_installation_depth">عمق تركيب المضخة (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-vertical"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="pump_installation_depth"
                                                value="{{ old('pump_installation_depth', $well->pump_installation_depth) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_capacity">استطاعة المضخة (حصان)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-horse-head"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="pump_capacity"
                                                value="{{ old('pump_capacity', $well->pump_capacity) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_pump_flow">تدفق المضخة الفعلي (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="actual_pump_flow"
                                                value="{{ old('actual_pump_flow', $well->actual_pump_flow) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_lifting">رفع المضخة (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-arrow-up"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="pump_lifting"
                                                value="{{ old('pump_lifting', $well->pump_lifting) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pump_brand_model">ماركة المضخة</label>
                                        <select name="pump_brand_model" class="form-control select2">
                                            <option value="">-- اختر الماركة --</option>
                                            <option value="ATURIA"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'ATURIA' ? 'selected' : '' }}>
                                                ATURIA</option>
                                            <option value="CHINESE"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'CHINESE' ? 'selected' : '' }}>
                                                CHINESE</option>
                                            <option value="GRUNDFOS"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'GRUNDFOS' ? 'selected' : '' }}>
                                                GRUNDFOS</option>
                                            <option value="RED JACKET"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'RED JACKET' ? 'selected' : '' }}>
                                                RED JACKET</option>
                                            <option value="JET"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'JET' ? 'selected' : '' }}>
                                                JET</option>
                                            <option value="LOWARA"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA' ? 'selected' : '' }}>
                                                LOWARA</option>
                                            <option value="LOWARA/EU"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/EU' ? 'selected' : '' }}>
                                                LOWARA/EU</option>
                                            <option value="LOWARA/FRANKLIN"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/FRANKLIN' ? 'selected' : '' }}>
                                                LOWARA/FRANKLIN</option>
                                            <option value="LOWARA/VOGEL"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/VOGEL' ? 'selected' : '' }}>
                                                LOWARA/VOGEL</option>
                                            <option value="PLUGER"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'PLUGER' ? 'selected' : '' }}>
                                                PLUGER</option>
                                            <option value="RITZ"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'RITZ' ? 'selected' : '' }}>
                                                RITZ</option>
                                            <option value="ROVATTI"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'ROVATTI' ? 'selected' : '' }}>
                                                ROVATTI</option>
                                            <option value="VANSAN"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'VANSAN' ? 'selected' : '' }}>
                                                VANSAN</option>
                                            <option value="WILLO"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'WILLO' ? 'selected' : '' }}>
                                                WILLO</option>
                                            <option value="غير معروف"
                                                {{ old('pump_brand_model', $well->pump_brand_model) == 'غير معروف' ? 'selected' : '' }}>
                                                غير معروف</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. بيانات الموقع والملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-map-marked-alt text-warning ml-2"></i>بيانات الموقع والملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="energy_source">مصدر الطاقة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <select name="energy_source" class="form-control">
                                                <option value="">-- اختر مصدر الطاقة --</option>
                                                <option value="لا يوجد"
                                                    {{ old('energy_source', $well->energy_source) == 'لا يوجد' ? 'selected' : '' }}>
                                                    لا يوجد</option>
                                                <option value="كهرباء"
                                                    {{ old('energy_source', $well->energy_source) == 'كهرباء' ? 'selected' : '' }}>
                                                    كهرباء</option>
                                                <option value="مولدة"
                                                    {{ old('energy_source', $well->energy_source) == 'مولدة' ? 'selected' : '' }}>
                                                    مولدة</option>
                                                <option value="طاقة شمسية"
                                                    {{ old('energy_source', $well->energy_source) == 'طاقة شمسية' ? 'selected' : '' }}>
                                                    طاقة شمسية</option>
                                                <option value="كهرباء و مولدة"
                                                    {{ old('energy_source', $well->energy_source) == 'كهرباء و مولدة' ? 'selected' : '' }}>
                                                    كهرباء و مولدة</option>
                                                <option value="كهرباء و طاقة شمسية"
                                                    {{ old('energy_source', $well->energy_source) == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و طاقة شمسية</option>
                                                <option value="مولدة و طاقة شمسية"
                                                    {{ old('energy_source', $well->energy_source) == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    مولدة و طاقة شمسية</option>
                                                <option value="كهرباء و مولدة و طاقة شمسية"
                                                    {{ old('energy_source', $well->energy_source) == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و مولدة و طاقة شمسية</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distance_from_station">المسافة من المحطة (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-route"></i></span></div>
                                            <input type="number" step="0.01" class="form-control"
                                                name="distance_from_station"
                                                value="{{ old('distance_from_station', $well->distance_from_station) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_address">عنوان البئر</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-marker-alt"></i></span></div>
                                            <input type="text" class="form-control" name="well_address"
                                                value="{{ old('well_address', $well->well_address) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_location">إحداثيات البئر (خط عرض، خط طول)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="text" class="form-control" name="well_location"
                                                placeholder="مثال: 34.7335, 36.7135"
                                                value="{{ old('well_location', $well->well_location) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">ملاحظات عامة</label>
                                        <textarea name="general_notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية">{{ old('general_notes', $well->general_notes) }}</textarea>
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
                            <a href="{{ route('dashboard.wells.index') }}" class="btn btn-secondary btn-lg">
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
            const wellStatusSelect = $('#well_status');
            const stopReasonContainer = $('#stop_reason_container');

            function toggleStopReason() {
                if (wellStatusSelect.val() === 'متوقف') {
                    stopReasonContainer.slideDown();
                } else {
                    stopReasonContainer.slideUp();
                }
            }

            wellStatusSelect.on('change', toggleStopReason);

            // تشغيل عند تحميل الصفحة لضبط الحالة الأولية
            toggleStopReason();
        });
    </script>
@endpush
