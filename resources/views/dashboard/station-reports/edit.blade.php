@extends('layouts.app')

@section('title', 'تعديل تقرير محطة')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تعديل تقرير محطة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.station-reports.index') }}">تقارير
                                المحطات</a></li>
                        <li class="breadcrumb-item active">تعديل التقرير</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('dashboard.station-reports.update', $stationReport) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <!-- Basic Information Card -->
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">المعلومات الأساسية</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="report_date">تاريخ التقرير <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="report_date" id="report_date"
                                                class="form-control @error('report_date') is-invalid @enderror"
                                                value="{{ old('report_date', $stationReport->report_date ? $stationReport->report_date->format('Y-m-d') : date('Y-m-d')) }}"
                                                required>
                                            @error('report_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="station_id">المحطة</label>
                                            <select name="station_id" id="station_id"
                                                class="form-control select2 @error('station_id') is-invalid @enderror">
                                                @foreach ($stations as $station)
                                                    <option value="{{ $station->id }}"
                                                        {{ old('station_id', $stationReport->station_id) == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('station_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">الحالة التشغيلية</label>
                                            <select name="status" id="status"
                                                class="form-control @error('status') is-invalid @enderror">
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->value }}"
                                                        {{ old('status', $stationReport->status?->value) == $status->value ? 'selected' : '' }}>
                                                        {{ $status->getLabel() }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="operating_entity">الجهة المشغلة</label>
                                            <select name="operating_entity" id="operating_entity"
                                                class="form-control @error('operating_entity') is-invalid @enderror">
                                                @foreach ($operatingEntities as $entity)
                                                    <option value="{{ $entity->value }}"
                                                        {{ old('operating_entity', $stationReport->operating_entity?->value) == $entity->value ? 'selected' : '' }}>
                                                        {{ $entity->getLabel() }}</option>
                                                @endforeach
                                            </select>
                                            @error('operating_entity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="operating_entity_name">اسم الشريك</label>
                                            <input type="text" name="operating_entity_name" class="form-control"
                                                value="{{ old('operating_entity_name', $stationReport->operating_entity_name) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pumping and Wells Information Card -->
                        <div class="card card-success card-outline" id="working_status_fields">
                            <div class="card-header">
                                <h3 class="card-title">بيانات الضخ والآبار</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group"><label for="number_well">عدد الآبار العاملة</label><input
                                                type="number" name="number_well" id="number_well" class="form-control"
                                                value="{{ old('number_well', $stationReport->number_well) }}"
                                                min="0" max="7"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group"><label for="water_pumped_m3">المياه المضخوخة
                                                (م³)</label><input type="number" name="water_pumped_m3"
                                                class="form-control"
                                                value="{{ old('water_pumped_m3', $stationReport->water_pumped_m3) }}"
                                                step="0.01" min="0"></div>
                                    </div>
                                    {{-- <div class="col-md-3"><div class="form-group"><label for="Water_production_m3">المياه المنتجة (م³)</label><input type="number" name="Water_production_m3" class="form-control" value="{{ old('Water_production_m3', $stationReport->Water_production_m3) }}" step="0.01" min="0"></div></div> --}}
                                    <div class="col-md-3">
                                        <div class="form-group"><label for="operating_hours">إجمالي ساعات
                                                التشغيل</label><input type="number" name="operating_hours"
                                                class="form-control"
                                                value="{{ old('operating_hours', $stationReport->operating_hours) }}"
                                                step="0.01" min="0"></div>
                                    </div>
                                </div>
                                <div class="row" id="wells-hours-container"></div>

                                <hr>
                                <h6 class="mt-4">المضخة الأفقية والتعقيم</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group"><label for="is_horizontal_pump">توجد مضخة
                                                أفقية؟</label><select name="is_horizontal_pump" id="is_horizontal_pump"
                                                class="form-control">
                                                <option value="0"
                                                    {{ old('is_horizontal_pump', $stationReport->is_horizontal_pump) == '0' ? 'selected' : '' }}>
                                                    لا</option>
                                                <option value="1"
                                                    {{ old('is_horizontal_pump', $stationReport->is_horizontal_pump) == '1' ? 'selected' : '' }}>
                                                    نعم</option>
                                            </select></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="horizontal_pump_fields"><label
                                                for="horizontal_pump_operating_hours">ساعات عمل الأفقية</label><input
                                                type="number" name="horizontal_pump_operating_hours"
                                                class="form-control"
                                                value="{{ old('horizontal_pump_operating_hours', $stationReport->horizontal_pump_operating_hours) }}"
                                                step="0.01" min="0"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pumping_sector_id">قطاع الضخ</label>
                                            <select name="pumping_sector_id" id="pumping_sector_id"
                                                class="form-control select2 @error('pumping_sector_id') is-invalid @enderror">
                                                <option value="">اختر قطاع الضخ</option>
                                                @foreach ($pumpingSectors as $sector)
                                                    <option value="{{ $sector->id }}"
                                                        {{ old('pumping_sector_id', $stationReport->pumping_sector_id) == $sector->id ? 'selected' : '' }}>
                                                        {{ $sector->name ?? 'قطاع ' . $sector->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pumping_sector_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Energy Information Card -->
                                <div class="card card-warning card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">بيانات الطاقة</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="power_source">مصدر الطاقة
                                                        الرئيسي</label><select name="power_source" id="power_source"
                                                        class="form-control">
                                                        @foreach ($energyResources as $resource)
                                                            <option value="{{ $resource->value }}"
                                                                {{ old('power_source', $stationReport->power_source?->value) == $resource->value ? 'selected' : '' }}>
                                                                {{ $resource->getLabel() }}</option>
                                                        @endforeach
                                                    </select></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="electricity_hours">ساعات
                                                        الكهرباء</label><input type="number" name="electricity_hours"
                                                        class="form-control"
                                                        value="{{ old('electricity_hours', $stationReport->electricity_hours) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="solar_hours">ساعات الطاقة
                                                        الشمسية</label><input type="number" name="solar_hours"
                                                        class="form-control"
                                                        value="{{ old('solar_hours', $stationReport->solar_hours) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="generator_hours">ساعات
                                                        المولدة</label><input type="number" name="generator_hours"
                                                        class="form-control"
                                                        value="{{ old('generator_hours', $stationReport->generator_hours) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="electricity_power_kwh">استهلاك
                                                        الكهرباء
                                                        (KWH)</label><input type="number" name="electricity_power_kwh"
                                                        class="form-control"
                                                        value="{{ old('electricity_power_kwh', $stationReport->electricity_power_kwh) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label
                                                        for="electricity_Counter_number_before">عداد
                                                        الكهرباء (قبل)</label><input type="number"
                                                        name="electricity_Counter_number_before" class="form-control"
                                                        value="{{ old('electricity_Counter_number_before', $stationReport->electricity_Counter_number_before) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="electricity_Counter_number_after">عداد
                                                        الكهرباء (بعد)</label><input type="number"
                                                        name="electricity_Counter_number_after" class="form-control"
                                                        value="{{ old('electricity_Counter_number_after', $stationReport->electricity_Counter_number_after) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Diesel Information Card -->
                                <div class="card card-danger card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">بيانات الديزل</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="diesel_consumed_liters">استهلاك الديزل
                                                        (لتر)</label><input type="number" name="diesel_consumed_liters"
                                                        class="form-control"
                                                        value="{{ old('diesel_consumed_liters', $stationReport->diesel_consumed_liters) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="Total_desil_liters">الكمية الإجمالية
                                                        (لتر)</label><input type="number" name="Total_desil_liters"
                                                        class="form-control"
                                                        value="{{ old('Total_desil_liters', $stationReport->Total_desil_liters) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="is_diesel_received">هل تم استلام
                                                        ديزل؟</label><select name="is_diesel_received"
                                                        id="is_diesel_received" class="form-control">
                                                        <option value="0"
                                                            {{ old('is_diesel_received', $stationReport->is_diesel_received) == '0' ? 'selected' : '' }}>
                                                            لا</option>
                                                        <option value="1"
                                                            {{ old('is_diesel_received', $stationReport->is_diesel_received) == '1' ? 'selected' : '' }}>
                                                            نعم</option>
                                                    </select></div>
                                            </div>
                                        </div>
                                        <div id="diesel-fields">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label
                                                            for="quantity_of_diesel_received_liters">الكمية
                                                            المستلمة (لتر)</label><input type="number"
                                                            name="quantity_of_diesel_received_liters" class="form-control"
                                                            value="{{ old('quantity_of_diesel_received_liters', $stationReport->quantity_of_diesel_received_liters) }}"
                                                            step="0.01" min="0"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label for="diesel_source">مصدر
                                                            الديزل</label><input type="text" name="diesel_source"
                                                            class="form-control"
                                                            value="{{ old('diesel_source', $stationReport->diesel_source) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Maintenance and Modifications Card -->
                                <div class="card card-secondary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">الصيانة والتعديلات</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="is_the_electricity_meter_charged">هل
                                                        تم شحن
                                                        العداد؟</label><select name="is_the_electricity_meter_charged"
                                                        id="is_the_electricity_meter_charged" class="form-control">
                                                        <option value="0"
                                                            {{ old('is_the_electricity_meter_charged', $stationReport->is_the_electricity_meter_charged) == '0' ? 'selected' : '' }}>
                                                            لا</option>
                                                        <option value="1"
                                                            {{ old('is_the_electricity_meter_charged', $stationReport->is_the_electricity_meter_charged) == '1' ? 'selected' : '' }}>
                                                            نعم</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-8" id="charging-fields">
                                                <div class="form-group"><label
                                                        for="quantity_of_electricity_meter_charged_kwh">كمية الشحن
                                                        (KWH)</label><input type="number"
                                                        name="quantity_of_electricity_meter_charged_kwh"
                                                        class="form-control"
                                                        value="{{ old('quantity_of_electricity_meter_charged_kwh', $stationReport->quantity_of_electricity_meter_charged_kwh) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="is_there_an_oil_change">هل تم تغيير
                                                        زيت؟</label><select name="is_there_an_oil_change"
                                                        id="is_there_an_oil_change" class="form-control">
                                                        <option value="0"
                                                            {{ old('is_there_an_oil_change', $stationReport->is_there_an_oil_change) == '0' ? 'selected' : '' }}>
                                                            لا</option>
                                                        <option value="1"
                                                            {{ old('is_there_an_oil_change', $stationReport->is_there_an_oil_change) == '1' ? 'selected' : '' }}>
                                                            نعم</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-8" id="oil-change-fields">
                                                <div class="form-group"><label for="quantity_of_oil_added">كمية الزيت
                                                        المضافة
                                                        (لتر)</label><input type="number" name="quantity_of_oil_added"
                                                        class="form-control"
                                                        value="{{ old('quantity_of_oil_added', $stationReport->quantity_of_oil_added) }}"
                                                        step="0.01" min="0"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"><label for="has_station_been_modified">هل تم تعديل
                                                        المحطة؟</label><select name="has_station_been_modified"
                                                        id="has_station_been_modified" class="form-control">
                                                        <option value="0"
                                                            {{ old('has_station_been_modified', $stationReport->has_station_been_modified) == '0' ? 'selected' : '' }}>
                                                            لا</option>
                                                        <option value="1"
                                                            {{ old('has_station_been_modified', $stationReport->has_station_been_modified) == '1' ? 'selected' : '' }}>
                                                            نعم</option>
                                                    </select></div>
                                            </div>
                                            <div class="col-md-8" id="modification-fields">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label for="station_modification_type">نوع
                                                                التعديل</label><input type="text"
                                                                name="station_modification_type" class="form-control"
                                                                value="{{ old('station_modification_type', $stationReport->station_modification_type) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label
                                                                for="station_modification_notes">ملاحظات
                                                                التعديل</label>
                                                            <textarea name="station_modification_notes" class="form-control" rows="1">{{ old('station_modification_notes', $stationReport->station_modification_notes) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes Card -->
                                <div class="card card-dark card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">الملاحظات وأسباب التوقف</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6" id="stopped_status_fields">
                                                <div class="form-group">
                                                    <label for="stop_reason">سبب التوقف</label>
                                                    <textarea name="stop_reason" class="form-control" rows="2">{{ old('stop_reason', $stationReport->stop_reason) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="notes">ملاحظات عامة</label>
                                                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $stationReport->notes) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> تحديث
                                        التقرير</button>
                                    <a href="{{ route('dashboard.station-reports.index') }}" class="btn btn-secondary"><i
                                            class="fas fa-times"></i> إلغاء</a>
                                </div>
                            </div>
                        </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    {{-- 
        الخطوة 1: نُجهز البيانات من PHP ونحولها إلى كائن JavaScript.
        هذا الكود يتم تنفيذه على الخادم مرة واحدة.
    --}}
    @php
        $wellHoursData = [];
        for ($i = 1; $i <= 7; $i++) {
            $fieldName = "well{$i}_operating_hours";
            // الدالة old() تأخذ القيمة القديمة، وإن لم توجد، تأخذ القيمة الافتراضية (من المودل)
            $wellHoursData[$i] = old($fieldName, $stationReport->{$fieldName});
        }
    @endphp
    <script>
        const wellHours = @json($wellHoursData);

        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });

            // --- Event Listeners ---
            $('#status').on('change', toggleStatusFields);
            $('#number_well').on('input', function() {
                generateWellsFields($(this).val());
            });
            $('#is_horizontal_pump').on('change', function() {
                toggleDynamicField($(this).val() === '1', '#horizontal_pump_fields');
            });
            $('#is_diesel_received').on('change', function() {
                toggleDynamicField($(this).val() === '1', '#diesel-fields');
            });
            $('#is_the_electricity_meter_charged').on('change', function() {
                toggleDynamicField($(this).val() === '1', '#charging-fields');
            });
            $('#is_there_an_oil_change').on('change', function() {
                toggleDynamicField($(this).val() === '1', '#oil-change-fields');
            });
            $('#has_station_been_modified').on('change', function() {
                toggleDynamicField($(this).val() === '1', '#modification-fields');
            });

            // --- Initializer ---
            initializeFields();
        });

        function initializeFields() {
            $('#status').trigger('change');
            $('#number_well').trigger('input');
            $('#is_horizontal_pump').trigger('change');
            $('#is_diesel_received').trigger('change');
            $('#is_the_electricity_meter_charged').trigger('change');
            $('#is_there_an_oil_change').trigger('change');
            $('#has_station_been_modified').trigger('change');
        }

        // --- Logic Functions ---
        function toggleStatusFields() {
            const isWorking = $('#status').val() === 'working';
            $('#working_status_fields').toggle(isWorking);
            $('#stopped_status_fields').toggle(!isWorking);
        }

        function toggleDynamicField(show, selector) {
            const element = $(selector);
            if (show) {
                element.show();
            } else {
                element.hide();
                // مسح القيم عند إخفاء الحقول لتجنب حفظها بالخطأ
                element.find('input, textarea').val('');
            }
        }

        // --- الدالة المصححة ---
        function generateWellsFields(wellCount) {
            const container = $('#wells-hours-container');
            container.empty();
            wellCount = parseInt(wellCount) || 0;

            if (wellCount > 0) {
                let row = $('<div class="row"></div>');
                for (let i = 1; i <= wellCount; i++) {
                    // الخطوة 2: نستخدم كائن JavaScript الذي جهزناه للحصول على القيمة
                    const existingValue = wellHours[i] || ''; // نستخدم wellHours[i] بدلاً من استدعاء Blade

                    const fieldHtml = `
                        <div class="col-md-3"><div class="form-group">
                            <label>ساعات بئر ${i}</label>
                            <input type="number" name="well${i}_operating_hours" class="form-control" step="0.01" min="0" value="${existingValue}">
                        </div></div>`;
                    row.append(fieldHtml);
                }
                container.append(row);
            }
        }
    </script>
@endpush
