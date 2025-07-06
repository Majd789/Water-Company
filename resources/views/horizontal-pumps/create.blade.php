@extends('layouts.app')

@section('title', 'إضافة مضخة أفقية جديدة')

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
                <h1 class="m-0">إضافة مضخة أفقية جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('horizontal-pumps.index') }}">المضخات الأفقية</a></li>
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

                <!-- قسم استيراد بيانات المضخات -->
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
                            <form action="{{ route('horizontal_pumps.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالمضخات الأفقية دفعة واحدة.</p>
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

                <!-- الفورم الرئيسي لإضافة البيانات -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-water ml-1"></i>
                            بيانات المضخة الأفقية
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('horizontal-pumps.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
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
                                        <label for="pump_name">اسم المضخة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="pump_name" id="pump_name" class="form-control"
                                                value="{{ old('pump_name') }}" placeholder="أدخل اسماً تعريفياً للمضخة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_status">الحالة التشغيلية</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="pump_status" id="pump_status" class="form-control">
                                                <option value="يعمل" {{ old('pump_status') == 'يعمل' ? 'selected' : '' }}>
                                                    تعمل</option>
                                                <option value="متوقفة"
                                                    {{ old('pump_status') == 'متوقفة' ? 'selected' : '' }}>متوقفة
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="technical_condition">الحالة الفنية</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tools"></i></span></div>
                                            <input type="number" name="technical_condition" id="technical_condition"
                                                class="form-control" value="{{ old('technical_condition') }}"
                                                placeholder=" الحالة الفنية للمضخة">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. المواصفات الفنية --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-success ml-2"></i>المواصفات الفنية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_capacity_hp">الاستطاعة (حصان)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-horse-head"></i></span></div>
                                            <input type="number" name="pump_capacity_hp" id="pump_capacity_hp"
                                                class="form-control" step="0.01"
                                                value="{{ old('pump_capacity_hp') }}" placeholder="مثال: 75">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_flow_rate_m3h">تدفق المضخة (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" name="pump_flow_rate_m3h" id="pump_flow_rate_m3h"
                                                class="form-control" step="0.01"
                                                value="{{ old('pump_flow_rate_m3h') }}" placeholder="مثال: 120">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_head">ارتفاع الضخ (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-arrows-alt-v"></i></span></div>
                                            <input type="number" name="pump_head" id="pump_head" class="form-control"
                                                step="0.01" value="{{ old('pump_head') }}" placeholder="مثال: 100">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-industry"></i></span></div>
                                            <select name="pump_brand_model" id="pump_brand_model" class="form-control">
                                                <option value="">-- اختر الماركة --</option>
                                                @foreach (['HALLER & SCHNEIDER', 'German Made', 'Italian Made', 'Turkish Made', 'STANDART', 'MEZ', 'CAPRARI', 'GAMAK', 'SEMPA', 'SEVER', 'Czech Made', 'WATT', 'European', 'SKM', 'GRUNDFOS', 'MAS', 'SIEMENS', 'ROVATTI', 'Spanish Made', 'DEMAK', 'Iranian Made', 'KLN', 'ELK', 'PENTAX', 'Chinese Made', 'LOWARA', 'JET', 'FLOWSERVE', 'KSB', 'ATURIA', 'غير معروف'] as $brand)
                                                    <option value="{{ $brand }}"
                                                        {{ old('pump_brand_model') == $brand ? 'selected' : '' }}>
                                                        {{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الطاقة والملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-bolt text-warning ml-2"></i>الطاقة والملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="energy_source">مصدر الطاقة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <select name="energy_source" id="energy_source" class="form-control">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="4"
                                            placeholder="أدخل أي ملاحظات إضافية هنا">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ البيانات
                            </button>
                            <a href="{{ route('horizontal-pumps.index') }}" class="btn btn-secondary btn-lg">
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
        });
    </script>
@endpush
