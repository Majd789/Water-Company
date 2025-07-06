@extends('layouts.app')
@section('title', 'إضافة منهل جديد')

{{-- استيراد نفس الأنماط التفاعلية من التصميم المحفوظ --}}
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
                <h1 class="m-0">إضافة منهل جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manholes.index') }}">المناهل</a></li>
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

                <!-- قسم استيراد المناهل (بنفس التصميم الأخضر) -->
                @if (auth()->check() && auth()->user()->role_id == 'admin')
                    <div class="card card-success collapsed-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-excel ml-1"></i>
                                استيراد من ملف Excel
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus "></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('manholes.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالمناهل دفعة واحدة من ملف إكسل.</p>
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

                <!-- الفورم الرئيسي لإضافة منهل جديد -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات المنهل الجديد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('manholes.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية والتبعية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="manhole_name">اسم المنهل</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="manhole_name" id="manhole_name" class="form-control"
                                                value="{{ old('manhole_name') }}" placeholder="أدخل اسم المنهل">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">حالة المنهل<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="status" id="status" class="form-control select2" required>
                                                <option value="يعمل" {{ old('status') == 'يعمل' ? 'selected' : '' }}>يعمل
                                                </option>
                                                <option value="متوقف" {{ old('status') == 'متوقف' ? 'selected' : '' }}>
                                                    متوقف</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sitemap"></i></span></div>
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town_id">البلدة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-marker-alt"></i></span></div>
                                            <select name="town_id" id="town_id" class="form-control select2" required>
                                                <option value="" disabled selected>-- اختر البلدة --</option>
                                                @foreach ($towns as $town)
                                                    <option value="{{ $town->id }}"
                                                        {{ old('town_id') == $town->id ? 'selected' : '' }}>
                                                        {{ $town->town_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>الوحدة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-building"></i></span></div>
                                            <input type="text" class="form-control"
                                                value="{{ $unit ? $unit->unit_name : 'غير محددة' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stop_reason">سبب التوقف (إن وجد)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span></div>
                                            <input type="text" name="stop_reason" id="stop_reason"
                                                class="form-control" value="{{ old('stop_reason') }}"
                                                placeholder="أدخل سبب التوقف">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات عداد المنهل --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-tachometer-alt text-success ml-2"></i>بيانات عداد الغزارة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_flow_meter">هل يوجد عداد غزارة؟<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-check-circle"></i></span></div>
                                            <select name="has_flow_meter" id="has_flow_meter"
                                                class="form-control select2" required>
                                                <option value="1"
                                                    {{ old('has_flow_meter') == '1' ? 'selected' : '' }}>نعم</option>
                                                <option value="0"
                                                    {{ old('has_flow_meter', '0') == '0' ? 'selected' : '' }}>لا</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_status">حالة العداد<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-toggle-on"></i></span></div>
                                            <select name="meter_status" id="meter_status" class="form-control select2"
                                                required>
                                                <option value="يعمل"
                                                    {{ old('meter_status') == 'يعمل' ? 'selected' : '' }}>يعمل</option>
                                                <option value="متوقف"
                                                    {{ old('meter_status') == 'متوقف' ? 'selected' : '' }}>متوقف</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="chassis_number">رقم الشاسيه</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-barcode"></i></span></div>
                                            <input type="text" name="chassis_number" id="chassis_number"
                                                class="form-control" value="{{ old('chassis_number') }}"
                                                placeholder="أدخل رقم الشاسيه">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_diameter">قطر العداد (إنش)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-vertical"></i></span></div>
                                            <input type="number" step="any" name="meter_diameter"
                                                id="meter_diameter" class="form-control"
                                                value="{{ old('meter_diameter') }}" placeholder="أدخل قطر العداد بالإنش">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="meter_operation_method_in_meter">طريقة عمل العداد</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-cogs"></i></span></div>
                                            <input type="text" name="meter_operation_method_in_meter"
                                                id="meter_operation_method_in_meter" class="form-control"
                                                value="{{ old('meter_operation_method_in_meter') }}"
                                                placeholder="أدخل طريقة عمل العداد">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. بيانات الخزان والملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-box-open text-info ml-2"></i>بيانات الخزان والملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_storage_tank">هل يوجد خزان تجميعي؟<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-box"></i></span></div>
                                            <select name="has_storage_tank" id="has_storage_tank"
                                                class="form-control select2" required>
                                                <option value="1"
                                                    {{ old('has_storage_tank') == '1' ? 'selected' : '' }}>نعم</option>
                                                <option value="0"
                                                    {{ old('has_storage_tank', '0') == '0' ? 'selected' : '' }}>لا</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tank_capacity">سعة الخزان (م³)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-combined"></i></span></div>
                                            <input type="number" step="0.01" name="tank_capacity" id="tank_capacity"
                                                class="form-control" value="{{ old('tank_capacity') }}"
                                                placeholder="أدخل سعة الخزان بالمتر المكعب">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات العامة</label>
                                        <textarea name="general_notes" id="general_notes" class="form-control" rows="3"
                                            placeholder="أدخل أي ملاحظات إضافية">{{ old('general_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ المنهل
                            </button>
                            <a href="{{ route('manholes.index') }}" class="btn btn-secondary btn-lg">
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
            // تفعيل Select2 مع دعم كامل للغة العربية ومطابقة شكل bootstrap
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --",
                allowClear: true
            });

            // تفعيل bs-custom-file-input لإظهار اسم الملف المختار
            bsCustomFileInput.init();
        });
    </script>
@endpush
