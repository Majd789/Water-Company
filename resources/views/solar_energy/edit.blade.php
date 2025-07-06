@extends('layouts.app')
@section('title', 'تعديل بيانات الطاقة الشمسية')

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
                <h1 class="m-0">تعديل بيانات الطاقة الشمسية</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('solar_energy.index') }}">الطاقة الشمسية</a></li>
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
                            بيانات منظومة الطاقة الشمسية
                        </h3>
                    </div>

                    <form action="{{ route('solar_energy.update', $solarEnergy->id) }}" method="POST" novalidate>
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
                                                    {{ old('station_id', $solarEnergy->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="manufacturer">الجهة المنشئة / الماركة<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-industry"></i></span></div>
                                            <input type="text" name="manufacturer" id="manufacturer" class="form-control"
                                                value="{{ old('manufacturer', $solarEnergy->manufacturer) }}"
                                                placeholder="أدخل الجهة المنشئة" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="base_type">نوع القاعدة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-cog"></i></span></div>
                                            <select name="base_type" id="base_type" class="form-control" required>
                                                <option value="">-- اختر نوع القاعدة --</option>
                                                <option value="ثابتة"
                                                    {{ old('base_type', $solarEnergy->base_type) == 'ثابتة' ? 'selected' : '' }}>
                                                    ثابتة</option>
                                                <option value="متحركة"
                                                    {{ old('base_type', $solarEnergy->base_type) == 'متحركة' ? 'selected' : '' }}>
                                                    متحركة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="technical_condition">الحالة الفنية<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tools"></i></span></div>
                                            <input type="text" name="technical_condition" id="technical_condition"
                                                class="form-control"
                                                value="{{ old('technical_condition', $solarEnergy->technical_condition) }}"
                                                placeholder="أدخل الحالة الفنية (مثال: جيدة، تحتاج صيانة)" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات الألواح والطاقة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-solar-panel text-success ml-2"></i>بيانات الألواح والطاقة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="panel_size">استطاعة اللوح (واط)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-combined"></i></span></div>
                                            <input type="number" name="panel_size" id="panel_size" class="form-control"
                                                value="{{ old('panel_size', $solarEnergy->panel_size) }}"
                                                placeholder="أدخل استطاعة اللوح الواحد" required>
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
                                                class="form-control"
                                                value="{{ old('panel_count', $solarEnergy->panel_count) }}"
                                                placeholder="أدخل عدد الألواح" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_power">الاستطاعة الإجمالية (كيلو واط)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="text" id="total_power" class="form-control" readonly
                                                style="background-color: #e9ecef;">
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
                                                class="form-control"
                                                value="{{ old('wells_supplied_count', $solarEnergy->wells_supplied_count) }}"
                                                placeholder="أدخل عدد الآبار" required>
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
                                            <input type="number" step="any" name="latitude" id="latitude"
                                                class="form-control"
                                                value="{{ old('latitude', $solarEnergy->latitude) }}"
                                                placeholder="مثال: 34.7335">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">خط الطول (Longitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="number" step="any" name="longitude" id="longitude"
                                                class="form-control"
                                                value="{{ old('longitude', $solarEnergy->longitude) }}"
                                                placeholder="مثال: 36.7135">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">ملاحظات عامة</label>
                                        <textarea name="general_notes" id="general_notes" class="form-control" rows="3"
                                            placeholder="أدخل أي ملاحظات إضافية">{{ old('general_notes', $solarEnergy->general_notes) }}</textarea>
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
                            <a href="{{ route('solar_energy.index') }}" class="btn btn-secondary btn-lg">
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

            // حساب الاستطاعة الإجمالية
            const panelSizeInput = $('#panel_size');
            const panelCountInput = $('#panel_count');
            const totalPowerInput = $('#total_power');

            function calculateTotalPower() {
                const size = parseFloat(panelSizeInput.val()) || 0;
                const count = parseInt(panelCountInput.val()) || 0;
                if (size > 0 && count > 0) {
                    const totalWatts = size * count;
                    const totalKiloWatts = (totalWatts / 1000).toFixed(2); // تحويل إلى كيلو واط مع رقمين عشريين
                    totalPowerInput.val(totalKiloWatts + ' kW');
                } else {
                    totalPowerInput.val('');
                }
            }

            // إضافة مستمعي الأحداث
            panelSizeInput.on('input', calculateTotalPower);
            panelCountInput.on('input', calculateTotalPower);

            // حساب القيمة عند تحميل الصفحة
            calculateTotalPower();
        });
    </script>
@endpush
