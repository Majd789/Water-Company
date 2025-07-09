@extends('layouts.app')
@section('title', 'تعديل بيانات منهل')

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
                <h1 class="m-0">تعديل بيانات منهل: <span class="text-primary">{{ $manhole->manhole_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.manholes.index') }}">المناهل</a></li>
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
                            بيانات المنهل
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.manholes.update', $manhole->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $manhole->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="manhole_name">اسم المنهل</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="manhole_name" id="manhole_name" class="form-control"
                                                value="{{ old('manhole_name', $manhole->manhole_name) }}"
                                                placeholder="أدخل اسم المنهل">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_id">الوحدة<span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-control select2" required>
                                            <option value="">-- اختر الوحدة --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id', $manhole->unit_id) == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town_id">البلدة<span class="text-danger">*</span></label>
                                        <select name="town_id" id="town_id" class="form-control select2" required>
                                            <option value="">-- اختر البلدة --</option>
                                            @foreach ($towns as $town)
                                                <option value="{{ $town->id }}"
                                                    {{ old('town_id', $manhole->town_id) == $town->id ? 'selected' : '' }}>
                                                    {{ $town->town_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">الحالة التشغيلية<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="يعمل"
                                                    {{ old('status', $manhole->status) == 'يعمل' ? 'selected' : '' }}>يعمل
                                                </option>
                                                <option value="متوقف"
                                                    {{ old('status', $manhole->status) == 'متوقف' ? 'selected' : '' }}>
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
                                            <input type="text" name="stop_reason" id="stop_reason" class="form-control"
                                                value="{{ old('stop_reason', $manhole->stop_reason) }}"
                                                placeholder="أدخل سبب التوقف">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات عداد الغزارة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-tachometer-alt text-success ml-2"></i>بيانات عداد الغزارة</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="has_flow_meter">هل يوجد عداد للغزارة؟<span
                                                class="text-danger">*</span></label>
                                        <select name="has_flow_meter" id="has_flow_meter" class="form-control" required>
                                            <option value="1"
                                                {{ old('has_flow_meter', $manhole->has_flow_meter) == 1 ? 'selected' : '' }}>
                                                نعم</option>
                                            <option value="0"
                                                {{ old('has_flow_meter', $manhole->has_flow_meter) == 0 ? 'selected' : '' }}>
                                                لا</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="flow_meter_details_container" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="chassis_number">رقم الشاسيه</label>
                                        <input type="text" name="chassis_number" class="form-control"
                                            value="{{ old('chassis_number', $manhole->chassis_number) }}"
                                            placeholder="أدخل رقم الشاسيه">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_diameter">قطر العداد (بوصة)</label>
                                        <input type="number" step="0.1" name="meter_diameter" class="form-control"
                                            value="{{ old('meter_diameter', $manhole->meter_diameter) }}"
                                            placeholder="أدخل قطر العداد">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_status">حالة العداد</label>
                                        <select name="meter_status" class="form-control">
                                            <option value="يعمل"
                                                {{ old('meter_status', $manhole->meter_status) == 'يعمل' ? 'selected' : '' }}>
                                                يعمل</option>
                                            <option value="متوقف"
                                                {{ old('meter_status', $manhole->meter_status) == 'متوقف' ? 'selected' : '' }}>
                                                متوقف</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_operation_method_in_meter">طريقة عمل العداد</label>
                                        <input type="text" name="meter_operation_method_in_meter" class="form-control"
                                            value="{{ old('meter_operation_method_in_meter', $manhole->meter_operation_method_in_meter) }}"
                                            placeholder="أدخل طريقة عمل العداد">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. الخزان التجميعي والملاحظات --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-database text-info ml-2"></i>الخزان التجميعي والملاحظات</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_storage_tank">هل يوجد خزان تجميعي؟<span
                                                class="text-danger">*</span></label>
                                        <select name="has_storage_tank" id="has_storage_tank" class="form-control"
                                            required>
                                            <option value="1"
                                                {{ old('has_storage_tank', $manhole->has_storage_tank) == 1 ? 'selected' : '' }}>
                                                نعم</option>
                                            <option value="0"
                                                {{ old('has_storage_tank', $manhole->has_storage_tank) == 0 ? 'selected' : '' }}>
                                                لا</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" id="tank_capacity_container" style="display:none;">
                                    <div class="form-group">
                                        <label for="tank_capacity">سعة الخزان (م³)</label>
                                        <input type="number" step="1" name="tank_capacity" class="form-control"
                                            value="{{ old('tank_capacity', $manhole->tank_capacity) }}"
                                            placeholder="أدخل سعة الخزان">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">ملاحظات عامة</label>
                                        <textarea name="general_notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('general_notes', $manhole->general_notes) }}</textarea>
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
                            <a href="{{ route('dashboard.manholes.index') }}" class="btn btn-secondary btn-lg">
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

            // إظهار وإخفاء الحقول الشرطية
            const statusSelect = $('#status');
            const stopReasonContainer = $('#stop_reason_container');

            const hasFlowMeterSelect = $('#has_flow_meter');
            const flowMeterDetailsContainer = $('#flow_meter_details_container');

            const hasStorageTankSelect = $('#has_storage_tank');
            const tankCapacityContainer = $('#tank_capacity_container');

            function toggleStopReason() {
                if (statusSelect.val() === 'متوقف') {
                    stopReasonContainer.slideDown();
                } else {
                    stopReasonContainer.slideUp();
                }
            }

            function toggleFlowMeterDetails() {
                if (hasFlowMeterSelect.val() == '1') { // 1 for "Yes"
                    flowMeterDetailsContainer.slideDown();
                } else {
                    flowMeterDetailsContainer.slideUp();
                }
            }

            function toggleTankCapacity() {
                if (hasStorageTankSelect.val() == '1') { // 1 for "Yes"
                    tankCapacityContainer.slideDown();
                } else {
                    tankCapacityContainer.slideUp();
                }
            }

            // ربط الأحداث
            statusSelect.on('change', toggleStopReason);
            hasFlowMeterSelect.on('change', toggleFlowMeterDetails);
            hasStorageTankSelect.on('change', toggleTankCapacity);

            // التشغيل عند تحميل الصفحة لضبط الحالة الأولية
            toggleStopReason();
            toggleFlowMeterDetails();
            toggleTankCapacity();
        });
    </script>
@endpush
