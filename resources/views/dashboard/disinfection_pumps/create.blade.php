@extends('layouts.app')

@section('title', 'إضافة مضخة تعقيم جديدة')

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
                <h1 class="m-0">إضافة مضخة تعقيم جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.disinfection_pumps.index') }}">مضخات التعقيم</a>
                    </li>
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
                            <form action="{{ route('dashboard.disinfection_pumps.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بمضخات التعقيم دفعة واحدة.</p>
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
                            <i class="fas fa-tint ml-1"></i>
                            بيانات مضخة التعقيم
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.disinfection_pumps.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>البيانات الأساسية</h5>
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
                                        <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-industry"></i></span></div>
                                            <select name="pump_brand_model" id="pump_brand_model" class="form-control">
                                                <option value="">-- اختر الماركة --</option>
                                                <option value="TEKNA EVO"
                                                    {{ old('pump_brand_model') == 'TEKNA EVO' ? 'selected' : '' }}>TEKNA EVO
                                                </option>
                                                <option value="SEKO"
                                                    {{ old('pump_brand_model') == 'SEKO' ? 'selected' : '' }}>SEKO</option>
                                                <option value="AQUA"
                                                    {{ old('pump_brand_model') == 'AQUA' ? 'selected' : '' }}>AQUA</option>
                                                <option value="BETA"
                                                    {{ old('pump_brand_model') == 'BETA' ? 'selected' : '' }}>BETA</option>
                                                <option value="Sempom"
                                                    {{ old('pump_brand_model') == 'Sempom' ? 'selected' : '' }}>Sempom
                                                </option>
                                                <option value="SACO"
                                                    {{ old('pump_brand_model') == 'SACO' ? 'selected' : '' }}>SACO</option>
                                                <option value="Grundfos"
                                                    {{ old('pump_brand_model') == 'Grundfos' ? 'selected' : '' }}>Grundfos
                                                </option>
                                                <option value="Antech"
                                                    {{ old('pump_brand_model') == 'Antech' ? 'selected' : '' }}>Antech
                                                </option>
                                                <option value="FCE"
                                                    {{ old('pump_brand_model') == 'FCE' ? 'selected' : '' }}>FCE</option>
                                                <option value="SEL"
                                                    {{ old('pump_brand_model') == 'SEL' ? 'selected' : '' }}>SEL</option>
                                                <option value="غير معروف"
                                                    {{ old('pump_brand_model') == 'غير معروف' ? 'selected' : '' }}>غير
                                                    معروف</option>
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
                                        <label for="disinfection_pump_status">الوضع التشغيلي</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="disinfection_pump_status" id="disinfection_pump_status"
                                                class="form-control">
                                                <option value="">-- اختر الوضع --</option>
                                                <option value="يعمل"
                                                    {{ old('disinfection_pump_status') == 'يعمل' ? 'selected' : '' }}>يعمل
                                                </option>
                                                <option value="متوقف"
                                                    {{ old('disinfection_pump_status') == 'متوقف' ? 'selected' : '' }}>
                                                    متوقف</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_flow_rate">غزارة المضخة (لتر/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" name="pump_flow_rate" id="pump_flow_rate"
                                                class="form-control" value="{{ old('pump_flow_rate') }}" step="0.01"
                                                placeholder="أدخل غزارة المضخة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operating_pressure">ضغط العمل (بار)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-compress-arrows-alt"></i></span></div>
                                            <input type="number" name="operating_pressure" id="operating_pressure"
                                                class="form-control" value="{{ old('operating_pressure') }}"
                                                step="0.01" placeholder="أدخل ضغط العمل">
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
                                                placeholder="وصف الحالة الفنية للمضخة">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-sticky-note text-warning ml-2"></i>الملاحظات</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات عامة</label>
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
                            <a href="{{ route('dashboard.disinfection_pumps.index') }}" class="btn btn-secondary btn-lg">
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
