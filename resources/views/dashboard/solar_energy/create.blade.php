@extends('layouts.app')

@section('title', 'إضافة بيانات الطاقة الشمسية')

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
                <h1 class="m-0">إضافة بيانات طاقة شمسية</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.solar_energy.index') }}">الطاقة الشمسية</a></li>
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

                <!-- قسم استيراد بيانات الطاقة الشمسية -->
             
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
                            <form action="{{ route('dashboard.import.solar_energies') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة ببيانات الطاقة الشمسية دفعة واحدة.</p>
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
              

                <!-- الفورم الرئيسي لإضافة البيانات -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-solar-panel ml-1"></i>
                            بيانات منظومة الطاقة الشمسية
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.solar_energy.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية ومواصفات الألواح --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المواصفات الأساسية</h5>
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
                                        <label for="manufacturer">الجهة المنشئة / المصنعة<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-industry"></i></span></div>
                                            <input type="text" name="manufacturer" id="manufacturer" class="form-control"
                                                value="{{ old('manufacturer') }}" placeholder="أدخل اسم الجهة المصنعة"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="panel_size">قياس اللوح (واط)<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-combined"></i></span></div>
                                            <input type="number" name="panel_size" id="panel_size" class="form-control"
                                                value="{{ old('panel_size') }}" placeholder="مثال: 550" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="panel_count">عدد الألواح<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-th"></i></span></div>
                                            <input type="number" name="panel_count" id="panel_count"
                                                class="form-control" value="{{ old('panel_count') }}"
                                                placeholder="أدخل العدد الإجمالي للألواح" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="base_type">نوع القاعدة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-cogs"></i></span></div>
                                            <select name="base_type" id="base_type" class="form-control" required>
                                                <option value="">-- اختر نوع القاعدة --</option>
                                                <option value="ثابتة"
                                                    {{ old('base_type') == 'ثابتة' ? 'selected' : '' }}>ثابتة</option>
                                                <option value="متحركة"
                                                    {{ old('base_type') == 'متحركة' ? 'selected' : '' }}>متحركة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. الحالة والتشغيل --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-check-circle text-success ml-2"></i>الحالة والتشغيل</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="technical_condition">الحالة الفنية<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tools"></i></span></div>
                                            <input type="number" name="technical_condition" id="technical_condition"
                                                class="form-control" value="{{ old('technical_condition') }}"
                                                placeholder="رقم نسبة مئوية" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="wells_supplied_count">عدد الآبار المغذاة<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-water"></i></span></div>
                                            <input type="number" name="wells_supplied_count" id="wells_supplied_count"
                                                class="form-control" value="{{ old('wells_supplied_count') }}"
                                                placeholder="أدخل عدد الآبار التي تغذيها المنظومة" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الموقع والملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-map-marked-alt text-warning ml-2"></i>الموقع والملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">خط العرض (Latitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="text" name="latitude" id="latitude" class="form-control"
                                                value="{{ old('latitude') }}" placeholder="مثال: 34.7335">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">خط الطول (Longitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="text" name="longitude" id="longitude" class="form-control"
                                                value="{{ old('longitude') }}" placeholder="مثال: 36.7135">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات</label>
                                        <textarea name="general_notes" id="general_notes" class="form-control" rows="4"
                                            placeholder="أدخل أي ملاحظات إضافية هنا">{{ old('general_notes') }}</textarea>
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
                            <a href="{{ route('dashboard.solar_energy.index') }}" class="btn btn-secondary btn-lg">
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
