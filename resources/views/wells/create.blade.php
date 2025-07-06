@extends('layouts.app')
@section('title', 'إضافة بئر جديدة')

{{-- استيراد مكتبة Select2 --}}
{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <style>
        /* لا يتم تطبيق الألوان إلا بعد أن يبدأ المستخدم بالكتابة */
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        /* استهداف خاص لـ Select2 */
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
                <h1 class="m-0">إضافة بئر جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wells.index') }}">الآبار</a></li>
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

                <!-- رسائل الحالة والأخطاء -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check ml-1"></i> نجاح!</h5>
                        {{ session('success') }}
                    </div>
                @endif

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

                <!-- قسم استيراد الآبار -->
                @if (auth()->check() && auth()->user()->role_id == 'admin')
                    <div class="card card-success collapsed-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-excel ml-1"></i>
                                استيراد من ملف Excel
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('wells.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالآبار دفعة واحدة من ملف إكسل.</p>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="importFile" name="file"
                                            required>
                                        <label class="custom-file-label" for="importFile">اختر ملف Excel</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fas fa-upload ml-1"></i> بدء
                                    الاستيراد</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- الفورم الرئيسي لإضافة بئر جديدة -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات البئر الجديد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('wells.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_name">اسم البئر<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" class="form-control" name="well_name"
                                                value="{{ old('well_name') }}" placeholder="أدخل اسم البئر" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابع لها<span class="text-danger">*</span></label>
                                        <select name="station_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_type">نوع البئر<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-water"></i></span></div>
                                            <select name="well_type" class="form-control" required>
                                                <option value="">-- اختر نوع البئر --</option>
                                                <option value="جوفي" {{ old('well_type') == 'جوفي' ? 'selected' : '' }}>
                                                    جوفي</option>
                                                <option value="سطحي" {{ old('well_type') == 'سطحي' ? 'selected' : '' }}>
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
                                                <option value="">-- اختر الوضع التشغيلي --</option>
                                                <option value="يعمل"
                                                    {{ old('well_status') == 'يعمل' ? 'selected' : '' }}>يعمل</option>
                                                <option value="متوقف"
                                                    {{ old('well_status') == 'متوقف' ? 'selected' : '' }}>متوقف</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="stop_reason_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="stop_reason">سبب التوقف</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span></div>
                                            <input type="text" class="form-control" name="stop_reason"
                                                value="{{ old('stop_reason') }}" placeholder="أدخل سبب التوقف">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات الحفر والقياسات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-ruler-combined text-info ml-2"></i>بيانات الحفر والقياسات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drilling_depth">عمق الحفر (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-arrows-alt-v"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="drilling_depth" value="{{ old('drilling_depth') }}"
                                                placeholder="أدخل عمق الحفر">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_diameter">قطر البئر (إنش)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-arrows-alt-h"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="well_diameter" value="{{ old('well_diameter') }}"
                                                placeholder="أدخل قطر البئر">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="static_depth">العمق الستاتيكي (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-level-down-alt"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="static_depth" value="{{ old('static_depth') }}"
                                                placeholder="أدخل العمق الستاتيكي">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dynamic_depth">العمق الديناميكي (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-level-down-alt"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="dynamic_depth" value="{{ old('dynamic_depth') }}"
                                                placeholder="أدخل العمق الديناميكي">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. بيانات المضخة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-success ml-2"></i>بيانات المضخة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_installation_depth">عمق تركيب المضخة (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-download"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="pump_installation_depth"
                                                value="{{ old('pump_installation_depth') }}"
                                                placeholder="أدخل عمق تركيب المضخة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_capacity">استطاعة المضخة (حصان)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-horse-head"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="pump_capacity" value="{{ old('pump_capacity') }}"
                                                placeholder="أدخل استطاعة المضخة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_pump_flow">تدفق المضخة الفعلي (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="actual_pump_flow" value="{{ old('actual_pump_flow') }}"
                                                placeholder="أدخل تدفق المضخة الفعلي">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_lifting">رفع المضخة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-upload"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="pump_lifting" value="{{ old('pump_lifting') }}"
                                                placeholder="أدخل رفع المضخة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pump_brand_model">ماركة وموديل المضخة</label>
                                        <select name="pump_brand_model" class="form-control select2">
                                            <option value="">-- اختر الماركة --</option>
                                            <option value="ATURIA"
                                                {{ old('pump_brand_model') == 'ATURIA' ? 'selected' : '' }}>ATURIA</option>
                                            <option value="CHINESE"
                                                {{ old('pump_brand_model') == 'CHINESE' ? 'selected' : '' }}>CHINESE
                                            </option>
                                            <option value="GRUNDFOS"
                                                {{ old('pump_brand_model') == 'GRUNDFOS' ? 'selected' : '' }}>GRUNDFOS
                                            </option>
                                            <option value="RED JACKET"
                                                {{ old('pump_brand_model') == 'RED JACKET' ? 'selected' : '' }}>RED JACKET
                                            </option>
                                            <option value="JET"
                                                {{ old('pump_brand_model') == 'JET' ? 'selected' : '' }}>JET</option>
                                            <option value="LOWARA"
                                                {{ old('pump_brand_model') == 'LOWARA' ? 'selected' : '' }}>LOWARA</option>
                                            <option value="LOWARA/EU"
                                                {{ old('pump_brand_model') == 'LOWARA/EU' ? 'selected' : '' }}>LOWARA/EU
                                            </option>
                                            <option value="LOWARA/FRANKLIN"
                                                {{ old('pump_brand_model') == 'LOWARA/FRANKLIN' ? 'selected' : '' }}>
                                                LOWARA/FRANKLIN</option>
                                            <option value="LOWARA/VOGEL"
                                                {{ old('pump_brand_model') == 'LOWARA/VOGEL' ? 'selected' : '' }}>
                                                LOWARA/VOGEL</option>
                                            <option value="PLUGER"
                                                {{ old('pump_brand_model') == 'PLUGER' ? 'selected' : '' }}>PLUGER</option>
                                            <option value="RITZ"
                                                {{ old('pump_brand_model') == 'RITZ' ? 'selected' : '' }}>RITZ</option>
                                            <option value="ROVATTI"
                                                {{ old('pump_brand_model') == 'ROVATTI' ? 'selected' : '' }}>ROVATTI
                                            </option>
                                            <option value="VANSAN"
                                                {{ old('pump_brand_model') == 'VANSAN' ? 'selected' : '' }}>VANSAN</option>
                                            <option value="WILLO"
                                                {{ old('pump_brand_model') == 'WILLO' ? 'selected' : '' }}>WILLO</option>
                                            <option value="غير معروف"
                                                {{ old('pump_brand_model') == 'غير معروف' ? 'selected' : '' }}>غير معروف
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. بيانات إضافية وملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-map-marked-alt text-warning ml-2"></i>بيانات إضافية وملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_flow">تدفق البئر (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas-fa-wind"></i></span></div>
                                            <input type="number" step="any" class="form-control" name="well_flow"
                                                value="{{ old('well_flow') }}" placeholder="أدخل تدفق البئر">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distance_from_station">المسافة من المحطة (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-route"></i></span></div>
                                            <input type="number" step="any" class="form-control"
                                                name="distance_from_station" value="{{ old('distance_from_station') }}"
                                                placeholder="أدخل المسافة من المحطة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="energy_source">مصدر الطاقة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <select name="energy_source" class="form-control">
                                                <option value="">-- اختر مصدر الطاقة --</option>
                                                <option value="لا يوجد"
                                                    {{ old('energy_source') == 'لا يوجد' ? 'selected' : '' }}>لا يوجد
                                                </option>
                                                <option value="كهرباء"
                                                    {{ old('energy_source') == 'كهرباء' ? 'selected' : '' }}>كهرباء
                                                </option>
                                                <option value="مولدة"
                                                    {{ old('energy_source') == 'مولدة' ? 'selected' : '' }}>مولدة</option>
                                                <option value="طاقة شمسية"
                                                    {{ old('energy_source') == 'طاقة شمسية' ? 'selected' : '' }}>طاقة شمسية
                                                </option>
                                                <option value="كهرباء و مولدة"
                                                    {{ old('energy_source') == 'كهرباء و مولدة' ? 'selected' : '' }}>كهرباء
                                                    و مولدة</option>
                                                <option value="كهرباء و طاقة شمسية"
                                                    {{ old('energy_source') == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و طاقة شمسية</option>
                                                <option value="مولدة و طاقة شمسية"
                                                    {{ old('energy_source') == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    مولدة و طاقة شمسية</option>
                                                <option value="كهرباء و مولدة و طاقة شمسية"
                                                    {{ old('energy_source') == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و مولدة و طاقة شمسية</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_location">إحداثيات البئر (خط الطول، خط العرض)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="text" class="form-control" name="well_location"
                                                value="{{ old('well_location') }}" placeholder="مثال: 36.2023, 36.7135">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="well_address">عنوان البئر</label>
                                        <textarea name="well_address" class="form-control" rows="2" placeholder="أدخل العنوان التفصيلي للبئر">{{ old('well_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">ملاحظات عامة</label>
                                        <textarea name="general_notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('general_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ البئر
                            </button>
                            <a href="{{ route('wells.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                    <!-- نهاية الفورم -->
                </div>
                <!-- /.card -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl"
            });

            // تفعيل bs-custom-file-input
            bsCustomFileInput.init();

            // منطق إظهار/إخفاء حقل سبب التوقف
            const wellStatusSelect = document.getElementById('well_status');
            const stopReasonContainer = document.getElementById('stop_reason_container');

            function toggleStopReason() {
                if (wellStatusSelect.value === 'متوقف') {
                    stopReasonContainer.style.display = 'block';
                } else {
                    stopReasonContainer.style.display = 'none';
                }
            }

            // استدعاء الوظيفة عند تغيير القيمة
            wellStatusSelect.addEventListener('change', toggleStopReason);

            // استدعاء الوظيفة عند تحميل الصفحة لضبط الحالة الأولية بناءً على old()
            toggleStopReason();
        });
    </script>
@endpush
