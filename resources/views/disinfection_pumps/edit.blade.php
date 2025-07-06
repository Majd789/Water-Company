@extends('layouts.app')
@section('title', 'تعديل بيانات مضخة التعقيم')

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
                <h1 class="m-0">تعديل بيانات مضخة تعقيم</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('disinfection_pumps.index') }}">مضخات التعقيم</a></li>
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
                            بيانات مضخة التعقيم
                        </h3>
                    </div>

                    <form action="{{ route('disinfection_pumps.update', $disinfectionPump->id) }}" method="POST"
                        novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- 1. البيانات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>البيانات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $disinfectionPump->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_brand_model">ماركة وطراز المضخة<span
                                                class="text-danger">*</span></label>
                                        <select name="pump_brand_model" id="pump_brand_model" class="form-control select2"
                                            required>
                                            <option value="">-- اختر الماركة --</option>
                                            <option value="TEKNA EVO"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'TEKNA EVO' ? 'selected' : '' }}>
                                                TEKNA EVO</option>
                                            <option value="SEKO"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SEKO' ? 'selected' : '' }}>
                                                SEKO</option>
                                            <option value="AQUA"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'AQUA' ? 'selected' : '' }}>
                                                AQUA</option>
                                            <option value="BETA"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'BETA' ? 'selected' : '' }}>
                                                BETA</option>
                                            <option value="Sempom"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Sempom' ? 'selected' : '' }}>
                                                Sempom</option>
                                            <option value="SACO"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SACO' ? 'selected' : '' }}>
                                                SACO</option>
                                            <option value="Grundfos"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Grundfos' ? 'selected' : '' }}>
                                                Grundfos</option>
                                            <option value="Antech"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Antech' ? 'selected' : '' }}>
                                                Antech</option>
                                            <option value="FCE"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'FCE' ? 'selected' : '' }}>
                                                FCE</option>
                                            <option value="SEL"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SEL' ? 'selected' : '' }}>
                                                SEL</option>
                                            <option value="غير معروف"
                                                {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'غير معروف' ? 'selected' : '' }}>
                                                غير معروف</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disinfection_pump_status">الوضع التشغيلي<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="disinfection_pump_status" id="disinfection_pump_status"
                                                class="form-control" required>
                                                <option value="يعمل"
                                                    {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'يعمل' ? 'selected' : '' }}>
                                                    يعمل</option>
                                                <option value="متوقف"
                                                    {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'متوقف' ? 'selected' : '' }}>
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
                                            <input type="text" name="stop_reason" class="form-control"
                                                value="{{ old('stop_reason', $disinfectionPump->stop_reason) }}"
                                                placeholder="أدخل سبب التوقف">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. المواصفات الفنية --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-success ml-2"></i>المواصفات الفنية والحالة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pump_flow_rate">غزارة المضخة (لتر/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" name="pump_flow_rate"
                                                class="form-control"
                                                value="{{ old('pump_flow_rate', $disinfectionPump->pump_flow_rate) }}"
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
                                            <input type="number" step="0.01" name="operating_pressure"
                                                class="form-control"
                                                value="{{ old('operating_pressure', $disinfectionPump->operating_pressure) }}"
                                                placeholder="أدخل ضغط العمل">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="technical_condition">الحالة الفنية</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tools"></i></span></div>
                                            <input type="text" name="technical_condition" class="form-control"
                                                value="{{ old('technical_condition', $disinfectionPump->technical_condition) }}"
                                                placeholder="مثال: جيدة، تحتاج صيانة ...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $disinfectionPump->notes) }}</textarea>
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
                            <a href="{{ route('disinfection_pumps.index') }}" class="btn btn-secondary btn-lg">
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
            const statusSelect = $('#disinfection_pump_status');
            const reasonContainer = $('#stop_reason_container');

            function toggleReasonField() {
                if (statusSelect.val() === 'متوقف') {
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
