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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.station-reports.index') }}">تقارير المحطات</a></li>
                        <li class="breadcrumb-item active">تعديل التقرير</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">بيانات التقرير</h3>
                        </div>

                        <form action="{{ route('dashboard.station-reports.update', $stationReport) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                {{-- Basic Information --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="report_date">تاريخ التقرير <span class="text-danger">*</span></label>
                                            <input type="date" name="report_date" id="report_date"
                                                class="form-control @error('report_date') is-invalid @enderror"
                                                value="{{ old('report_date', $stationReport->report_date ? $stationReport->report_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                            @error('report_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="station_id">المحطة</label>
                                            <select name="station_id" id="station_id" class="form-control select2 @error('station_id') is-invalid @enderror">
                                                <option value="">اختر المحطة</option>
                                                @foreach($stations as $station)
                                                    <option value="{{ $station->id }}" {{ old('station_id', $stationReport->station_id) == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('station_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">الحالة التشغيلية</label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="">اختر الحالة</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->value }}" {{ old('status', $stationReport->status?->value) == $status->value ? 'selected' : '' }}>
                                                        {{ $status->getLabel() }}
                                                    </option>
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
                                            <select name="operating_entity" id="operating_entity" class="form-control @error('operating_entity') is-invalid @enderror">
                                                <option value="">اختر الجهة المشغلة</option>
                                                @foreach($operatingEntities as $entity)
                                                    <option value="{{ $entity->value }}" {{ old('operating_entity', $stationReport->operating_entity?->value) == $entity->value ? 'selected' : '' }}>
                                                        {{ $entity->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('operating_entity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Wells Information --}}
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات الآبار</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="number_well">عدد الآبار</label>
                                                    <input type="number" name="number_well" id="number_well"
                                                        class="form-control @error('number_well') is-invalid @enderror"
                                                        value="{{ old('number_well', $stationReport->number_well) }}" min="0" max="7">
                                                    @error('number_well')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="operating_hours">إجمالي ساعات التشغيل</label>
                                                    <input type="number" name="operating_hours" id="operating_hours"
                                                        class="form-control @error('operating_hours') is-invalid @enderror"
                                                        value="{{ old('operating_hours', $stationReport->operating_hours) }}" step="0.01" min="0">
                                                    @error('operating_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="water_pumped_m3">كمية المياه المضخوخة (م³)</label>
                                                    <input type="number" name="water_pumped_m3" id="water_pumped_m3"
                                                        class="form-control @error('water_pumped_m3') is-invalid @enderror"
                                                        value="{{ old('water_pumped_m3', $stationReport->water_pumped_m3) }}" step="0.01" min="0">
                                                    @error('water_pumped_m3')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="Water_production_m3">كمية المياه المنتجة (م³)</label>
                                                    <input type="number" name="Water_production_m3" id="Water_production_m3"
                                                        class="form-control @error('Water_production_m3') is-invalid @enderror"
                                                        value="{{ old('Water_production_m3', $stationReport->Water_production_m3) }}" step="0.01" min="0">
                                                    @error('Water_production_m3')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Wells Hours Fields --}}
                                        <div id="wells-hours-section" style="display: none;">
                                            <hr>
                                            <h6 class="text-primary">ساعات تشغيل الآبار</h6>
                                            <div class="row" id="wells-hours-container">
                                                <!-- Dynamic wells fields will be generated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Horizontal Pump Information --}}
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات المضخة الأفقية</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="is_horizontal_pump">هل توجد مضخة أفقية؟</label>
                                                    <select name="is_horizontal_pump" id="is_horizontal_pump" class="form-control">
                                                        <option value="">اختر</option>
                                                        <option value="1" {{ old('is_horizontal_pump', $stationReport->is_horizontal_pump) == '1' ? 'selected' : '' }}>نعم</option>
                                                        <option value="0" {{ old('is_horizontal_pump', $stationReport->is_horizontal_pump) == '0' ? 'selected' : '' }}>لا</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="horizontal_pump_operating_hours">ساعات تشغيل المضخة الأفقية</label>
                                                    <input type="number" name="horizontal_pump_operating_hours" id="horizontal_pump_operating_hours"
                                                        class="form-control @error('horizontal_pump_operating_hours') is-invalid @enderror"
                                                        value="{{ old('horizontal_pump_operating_hours', $stationReport->horizontal_pump_operating_hours) }}" step="0.01" min="0" disabled>
                                                    @error('horizontal_pump_operating_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="pumping_sector_id">قطاع الضخ</label>
                                                    <select name="pumping_sector_id" id="pumping_sector_id" class="form-control select2">
                                                        <option value="">اختر قطاع الضخ</option>
                                                        @foreach($pumpingSectors as $sector)
                                                            <option value="{{ $sector->id }}" {{ old('pumping_sector_id', $stationReport->pumping_sector_id) == $sector->id ? 'selected' : '' }}>
                                                                {{ $sector->name ?? $sector->sector_name ?? 'قطاع ' . $sector->id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sterilization Information --}}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات التعقيم</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="is_sterile">هل يوجد تعقيم؟</label>
                                                    <select name="is_sterile" id="is_sterile" class="form-control">
                                                        <option value="">اختر</option>
                                                        <option value="1" {{ old('is_sterile', $stationReport->is_sterile) == '1' ? 'selected' : '' }}>نعم</option>
                                                        <option value="0" {{ old('is_sterile', $stationReport->is_sterile) == '0' ? 'selected' : '' }}>لا</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Energy Information --}}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات الطاقة</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="power_source">مصدر الطاقة الرئيسي</label>
                                                    <select name="power_source" id="power_source" class="form-control @error('power_source') is-invalid @enderror">
                                                        <option value="">اختر مصدر الطاقة</option>
                                                        @foreach($energyResources as $resource)
                                                            <option value="{{ $resource->value }}" {{ old('power_source', $stationReport->power_source?->value) == $resource->value ? 'selected' : '' }}>
                                                                {{ $resource->getLabel() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('power_source')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="energy_resource">مصدر الطاقة التشغيلية</label>
                                                    <input type="text" name="energy_resource" id="energy_resource"
                                                        class="form-control @error('energy_resource') is-invalid @enderror"
                                                        value="{{ old('energy_resource', $stationReport->energy_resource) }}">
                                                    @error('energy_resource')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Energy Fields Based on Power Source --}}
                                        <div id="energy-fields-section">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="electricity_hours">ساعات الكهرباء</label>
                                                        <input type="number" name="electricity_hours" id="electricity_hours"
                                                            class="form-control @error('electricity_hours') is-invalid @enderror"
                                                            value="{{ old('electricity_hours', $stationReport->electricity_hours) }}" step="0.01" min="0">
                                                        @error('electricity_hours')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="solar_hours">ساعات الطاقة الشمسية</label>
                                                        <input type="number" name="solar_hours" id="solar_hours"
                                                            class="form-control @error('solar_hours') is-invalid @enderror"
                                                            value="{{ old('solar_hours', $stationReport->solar_hours) }}" step="0.01" min="0">
                                                        @error('solar_hours')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="generator_hours">ساعات المولدة</label>
                                                        <input type="number" name="generator_hours" id="generator_hours"
                                                            class="form-control @error('generator_hours') is-invalid @enderror"
                                                            value="{{ old('generator_hours', $stationReport->generator_hours) }}" step="0.01" min="0">
                                                        @error('generator_hours')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Combined Energy Fields --}}
                                            <div id="combined-energy-fields" style="display: none;">
                                                <hr>
                                                <h6 class="text-primary">ساعات الدمج</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="electricity_solar_hours">ساعات الكهرباء + الطاقة الشمسية</label>
                                                            <input type="number" name="electricity_solar_hours" id="electricity_solar_hours"
                                                                class="form-control @error('electricity_solar_hours') is-invalid @enderror"
                                                                value="{{ old('electricity_solar_hours', $stationReport->electricity_solar_hours) }}" step="0.01" min="0">
                                                            @error('electricity_solar_hours')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="solar_generator_hours">ساعات الطاقة الشمسية + المولدة</label>
                                                            <input type="number" name="solar_generator_hours" id="solar_generator_hours"
                                                                class="form-control @error('solar_generator_hours') is-invalid @enderror"
                                                                value="{{ old('solar_generator_hours', $stationReport->solar_generator_hours) }}" step="0.01" min="0">
                                                            @error('solar_generator_hours')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Electricity Meter Fields --}}
                                            <div id="electricity-meter-fields" style="display: none;">
                                                <hr>
                                                <h6 class="text-primary">عداد الكهرباء</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="electricity_Counter_number_before">قراءة العداد قبل</label>
                                                            <input type="number" name="electricity_Counter_number_before" id="electricity_Counter_number_before"
                                                                class="form-control @error('electricity_Counter_number_before') is-invalid @enderror"
                                                                value="{{ old('electricity_Counter_number_before', $stationReport->electricity_Counter_number_before) }}" step="0.01" min="0">
                                                            @error('electricity_Counter_number_before')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="electricity_Counter_number_after">قراءة العداد بعد</label>
                                                            <input type="number" name="electricity_Counter_number_after" id="electricity_Counter_number_after"
                                                                class="form-control @error('electricity_Counter_number_after') is-invalid @enderror"
                                                                value="{{ old('electricity_Counter_number_after', $stationReport->electricity_Counter_number_after) }}" step="0.01" min="0">
                                                            @error('electricity_Counter_number_after')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="electricity_power_kwh">استهلاك الكهرباء (كيلو واط ساعة)</label>
                                                            <input type="number" name="electricity_power_kwh" id="electricity_power_kwh"
                                                                class="form-control @error('electricity_power_kwh') is-invalid @enderror"
                                                                value="{{ old('electricity_power_kwh', $stationReport->electricity_power_kwh) }}" step="0.01" min="0">
                                                            @error('electricity_power_kwh')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Diesel Information --}}
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات الديزل</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="Total_desil_liters">إجمالي الديزل المتوفر (لتر)</label>
                                                    <input type="number" name="Total_desil_liters" id="Total_desil_liters"
                                                        class="form-control @error('Total_desil_liters') is-invalid @enderror"
                                                        value="{{ old('Total_desil_liters', $stationReport->Total_desil_liters) }}" step="0.01" min="0">
                                                    @error('Total_desil_liters')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="is_diesel_received">هل تم استلام ديزل؟</label>
                                                    <select name="is_diesel_received" id="is_diesel_received" class="form-control">
                                                        <option value="">اختر</option>
                                                        <option value="1" {{ old('is_diesel_received', $stationReport->is_diesel_received) == '1' ? 'selected' : '' }}>نعم</option>
                                                        <option value="0" {{ old('is_diesel_received', $stationReport->is_diesel_received) == '0' ? 'selected' : '' }}>لا</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="diesel_consumed_liters">استهلاك الديزل (لتر)</label>
                                                    <input type="number" name="diesel_consumed_liters" id="diesel_consumed_liters"
                                                        class="form-control @error('diesel_consumed_liters') is-invalid @enderror"
                                                        value="{{ old('diesel_consumed_liters', $stationReport->diesel_consumed_liters) }}" step="0.01" min="0">
                                                    @error('diesel_consumed_liters')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Diesel Fields --}}
                                        <div id="diesel-fields" style="display: none;">
                                            <hr>
                                            <h6 class="text-primary">تفاصيل الديزل المستلم</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="quantity_of_diesel_received_liters">كمية الديزل المستلمة (لتر)</label>
                                                        <input type="number" name="quantity_of_diesel_received_liters" id="quantity_of_diesel_received_liters"
                                                            class="form-control @error('quantity_of_diesel_received_liters') is-invalid @enderror"
                                                            value="{{ old('quantity_of_diesel_received_liters', $stationReport->quantity_of_diesel_received_liters) }}" step="0.01" min="0">
                                                        @error('quantity_of_diesel_received_liters')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="diesel_source">مصدر الديزل</label>
                                                        <input type="text" name="diesel_source" id="diesel_source"
                                                            class="form-control @error('diesel_source') is-invalid @enderror"
                                                            value="{{ old('diesel_source', $stationReport->diesel_source) }}">
                                                        @error('diesel_source')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Station Modifications --}}
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">التعديلات على المحطة</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="has_station_been_modified">هل تم التعديل على المحطة؟</label>
                                                    <select name="has_station_been_modified" id="has_station_been_modified" class="form-control">
                                                        <option value="">اختر</option>
                                                        <option value="1" {{ old('has_station_been_modified', $stationReport->has_station_been_modified) == '1' ? 'selected' : '' }}>نعم</option>
                                                        <option value="0" {{ old('has_station_been_modified', $stationReport->has_station_been_modified) == '0' ? 'selected' : '' }}>لا</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Modification Fields --}}
                                        <div id="modification-fields" style="display: none;">
                                            <hr>
                                            <h6 class="text-primary">تفاصيل التعديلات</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="station_modification_type">نوع التعديلات</label>
                                                        <input type="text" name="station_modification_type" id="station_modification_type"
                                                            class="form-control @error('station_modification_type') is-invalid @enderror"
                                                            value="{{ old('station_modification_type', $stationReport->station_modification_type) }}">
                                                        @error('station_modification_type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="station_modification_notes">ملاحظات التعديلات</label>
                                                        <textarea name="station_modification_notes" id="station_modification_notes"
                                                            class="form-control @error('station_modification_notes') is-invalid @enderror"
                                                            rows="2">{{ old('station_modification_notes', $stationReport->station_modification_notes) }}</textarea>
                                                        @error('station_modification_notes')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Electricity Meter Charging --}}
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">شحن عداد الكهرباء</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="is_the_electricity_meter_charged">هل تم شحن العداد الكهربائي؟</label>
                                                    <select name="is_the_electricity_meter_charged" id="is_the_electricity_meter_charged" class="form-control">
                                                        <option value="">اختر</option>
                                                        <option value="1" {{ old('is_the_electricity_meter_charged', $stationReport->is_the_electricity_meter_charged) == '1' ? 'selected' : '' }}>نعم</option>
                                                        <option value="0" {{ old('is_the_electricity_meter_charged', $stationReport->is_the_electricity_meter_charged) == '0' ? 'selected' : '' }}>لا</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Charging Fields --}}
                                        <div id="charging-fields" style="display: none;">
                                            <hr>
                                            <h6 class="text-primary">تفاصيل الشحن</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="quantity_of_electricity_meter_charged_kwh">كمية الكهرباء المشحونة (كيلو واط ساعة)</label>
                                                        <input type="number" name="quantity_of_electricity_meter_charged_kwh" id="quantity_of_electricity_meter_charged_kwh"
                                                            class="form-control @error('quantity_of_electricity_meter_charged_kwh') is-invalid @enderror"
                                                            value="{{ old('quantity_of_electricity_meter_charged_kwh', $stationReport->quantity_of_electricity_meter_charged_kwh) }}" step="0.01" min="0">
                                                        @error('quantity_of_electricity_meter_charged_kwh')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Additional Information --}}
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات إضافية</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="stop_reason">سبب التوقف (إذا كانت المحطة متوقفة)</label>
                                                    <textarea name="stop_reason" id="stop_reason"
                                                        class="form-control @error('stop_reason') is-invalid @enderror"
                                                        rows="2">{{ old('stop_reason', $stationReport->stop_reason) }}</textarea>
                                                    @error('stop_reason')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="notes">ملاحظات عامة</label>
                                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                                        rows="2">{{ old('notes', $stationReport->notes) }}</textarea>
                                                    @error('notes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> تحديث التقرير
                                </button>
                                <a href="{{ route('dashboard.station-reports.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });

            // Dynamic Wells Fields Logic
            $('#number_well').on('change', function() {
                const wellCount = parseInt($(this).val()) || 0;
                generateWellsFields(wellCount);
            });

            // Horizontal Pump Logic
            $('#is_horizontal_pump').on('change', function() {
                const hasPump = $(this).val() === '1';
                $('#horizontal_pump_operating_hours').prop('disabled', !hasPump);
                if (!hasPump) {
                    $('#horizontal_pump_operating_hours').val('');
                }
            });

            // Power Source Logic
            $('#power_source').on('change', function() {
                const powerSource = $(this).val();
                showEnergyFields(powerSource);
            });

            // Diesel Received Logic
            $('#is_diesel_received').on('change', function() {
                const received = $(this).val() === '1';
                $('#diesel-fields').toggle(received);
                if (!received) {
                    $('#quantity_of_diesel_received_liters, #diesel_source').val('');
                }
            });

            // Station Modifications Logic
            $('#has_station_been_modified').on('change', function() {
                const modified = $(this).val() === '1';
                $('#modification-fields').toggle(modified);
                if (!modified) {
                    $('#station_modification_type, #station_modification_notes').val('');
                }
            });

            // Electricity Meter Charging Logic
            $('#is_the_electricity_meter_charged').on('change', function() {
                const charged = $(this).val() === '1';
                $('#charging-fields').toggle(charged);
                if (!charged) {
                    $('#quantity_of_electricity_meter_charged_kwh').val('');
                }
            });

            // Initialize fields on page load
            initializeFields();
        });

        // Generate wells fields based on count
        function generateWellsFields(wellCount) {
            const container = $('#wells-hours-container');
            container.empty();

            if (wellCount > 0) {
                $('#wells-hours-section').show();

                for (let i = 1; i <= wellCount; i++) {
                    const fieldHtml = `
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="well${i}_operating_hours">ساعات تشغيل البئر ${i}</label>
                                <input type="number" name="well${i}_operating_hours" id="well${i}_operating_hours"
                                    class="form-control" step="0.01" min="0"
                                    value="{{ old('well${i}_operating_hours', '$stationReport->well${i}_operating_hours') }}">
                            </div>
                        </div>
                    `;
                    container.append(fieldHtml);
                }
            } else {
                $('#wells-hours-section').hide();
            }
        }

        // Show energy fields based on power source
        function showEnergyFields(powerSource) {
            const combinedFields = $('#combined-energy-fields');
            const electricityFields = $('#electricity-meter-fields');

            // Hide all fields first
            combinedFields.hide();
            electricityFields.hide();

            // Show fields based on selection
            switch(powerSource) {
                case 'electricity_solar':
                case 'electricity_generator':
                case 'all_sources':
                    combinedFields.show();
                    electricityFields.show();
                    break;
                case 'electricity':
                    electricityFields.show();
                    break;
                case 'solar_generator':
                    combinedFields.show();
                    break;
            }
        }

        // Initialize fields on page load
        function initializeFields() {
            // Trigger change events for existing values
            $('#number_well').trigger('change');
            $('#is_horizontal_pump').trigger('change');
            $('#power_source').trigger('change');
            $('#is_diesel_received').trigger('change');
            $('#has_station_been_modified').trigger('change');
            $('#is_the_electricity_meter_charged').trigger('change');
        }
    </script>
@endpush
