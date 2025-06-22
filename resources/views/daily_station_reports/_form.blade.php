@csrf
{{-- ============================================== --}}
{{-- معلومات التقرير والمحطة (محددة مسبقًا للإنشاء) --}}
{{-- ============================================== --}}
<div class="card mb-4">
    <div class="card-header">
        <h5>معلومات التقرير الأساسية والمحطة</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label for="report_date" class="form-label">تاريخ التقرير <span
                        class="text-danger always-visible">*</span></label>
                <input type="date" class="form-control @error('report_date') is-invalid @enderror" id="report_date"
                    name="report_date"
                    value="{{ old('report_date', isset($dailyStationReport) ? $dailyStationReport->report_date->format('Y-m-d') : date('Y-m-d')) }}"
                    required>
                @error('report_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="report_time" class="form-label">التوقيت (H:i:s)</label>
                <input type="time" class="form-control @error('report_time') is-invalid @enderror" id="report_time"
                    name="report_time"
                    value="{{ old('report_time', isset($dailyStationReport) && $dailyStationReport->report_time ? $dailyStationReport->report_time->format('H:i:s') : '') }}"
                    step="1">
                @error('report_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">المُشغل المناوب</label>
                <input type="text" class="form-control"
                    value="{{ $operatorName ?? (isset($dailyStationReport) && $dailyStationReport->operator ? $dailyStationReport->operator->name : Auth::user()->name) }}"
                    readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">المحطة <span class="text-danger always-visible">*</span></label>
                <input type="text" class="form-control"
                    value="{{ $preselectedStationName ?? (isset($dailyStationReport) && $dailyStationReport->station ? $dailyStationReport->station->station_name : 'غير محددة') }}"
                    readonly>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <label class="form-label">كود المحطة</label>
                <input type="text" class="form-control" name="station_code_snapshot_display"
                    value="{{ $preselectedStationCode ?? (isset($dailyStationReport) ? $dailyStationReport->station_code_snapshot : '') }}"
                    readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">وحدة المياه</label>
                <input type="text" class="form-control"
                    value="{{ $preselectedUnitName ?? (isset($dailyStationReport) && $dailyStationReport->unit ? $dailyStationReport->unit->unit_name : 'غير محددة') }}"
                    readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">البلدة</label>
                <input type="text" class="form-control"
                    value="{{ $preselectedTownName ?? (isset($dailyStationReport) && $dailyStationReport->town ? $dailyStationReport->town->town_name : 'غير محددة') }}"
                    readonly>
            </div>
            <div class="col-md-3">
                <label for="pumping_sector_id" class="form-label">قطاع الضخ المستهدف</label>
                <select class="form-select @error('pumping_sector_id') is-invalid @enderror" id="pumping_sector_id"
                    name="pumping_sector_id"
                    {{ isset($pumpingSectors) && $pumpingSectors->isEmpty() && !isset($dailyStationReport) ? 'disabled' : '' }}>
                    <option value="">-- اختر قطاع الضخ --</option>
                    @php
                        $sectorsToListInForm = null;
                        if (!isset($dailyStationReport) && isset($pumpingSectors) && !$pumpingSectors->isEmpty()) {
                            $sectorsToListInForm = $pumpingSectors;
                        } elseif (isset($dailyStationReport) && $dailyStationReport->station) {
                            if (isset($pumpingSectorsForEdit) && !$pumpingSectorsForEdit->isEmpty()) {
                                $sectorsToListInForm = $pumpingSectorsForEdit;
                            } elseif ($dailyStationReport->station->relationLoaded('pumpingSectors')) {
                                $sectorsToListInForm = $dailyStationReport->station
                                    ->pumpingSectors()
                                    ->orderBy('sector_name')
                                    ->get();
                            } else {
                                $sectorsToListInForm = $dailyStationReport->station
                                    ->pumpingSectors()
                                    ->orderBy('sector_name')
                                    ->get();
                            }
                        }
                    @endphp
                    @if ($sectorsToListInForm && !$sectorsToListInForm->isEmpty())
                        @foreach ($sectorsToListInForm as $sector)
                            <option value="{{ $sector->id }}"
                                {{ old('pumping_sector_id', $dailyStationReport->pumping_sector_id ?? '') == $sector->id ? 'selected' : '' }}>
                                {{ $sector->sector_name }}
                            </option>
                        @endforeach
                    @elseif (isset($dailyStationReport) && $dailyStationReport->pumpingSector)
                        <option value="{{ $dailyStationReport->pumping_sector_id }}" selected>
                            {{ $dailyStationReport->pumpingSector->sector_name }}
                        </option>
                    @endif
                </select>
                @error('pumping_sector_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>الوضع التشغيلي والجهة المشغلة</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-4">
                <label for="daily_operational_status" class="form-label">الوضع التشغيلي <span
                        class="text-danger always-visible">*</span></label>
                <select class="form-select @error('daily_operational_status') is-invalid @enderror"
                    id="daily_operational_status" name="daily_operational_status" required>
                    <option value="عاملة"
                        {{ old('daily_operational_status', $dailyStationReport->daily_operational_status ?? 'عاملة') == 'عاملة' ? 'selected' : '' }}>
                        عاملة</option>
                    <option value="متوقفة"
                        {{ old('daily_operational_status', $dailyStationReport->daily_operational_status ?? '') == 'متوقفة' ? 'selected' : '' }}>
                        متوقفة</option>
                    <option value="خارج الخدمة"
                        {{ old('daily_operational_status', $dailyStationReport->daily_operational_status ?? '') == 'خارج الخدمة' ? 'selected' : '' }}>
                        خارج الخدمة</option>
                </select>
                @error('daily_operational_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-8" id="stop_reason_div" style="display:none;">
                <label for="daily_stop_reason" class="form-label">سبب التوقف <span
                        class="text-danger d-none">*</span></label>
                <input type="text" class="form-control @error('daily_stop_reason') is-invalid @enderror"
                    id="daily_stop_reason" name="daily_stop_reason"
                    value="{{ old('daily_stop_reason', $dailyStationReport->daily_stop_reason ?? '') }}">
                @error('daily_stop_reason')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="daily_operator_entity" class="form-label">الجهة المشغلة</label>
                <select class="form-select @error('daily_operator_entity') is-invalid @enderror"
                    id="daily_operator_entity" name="daily_operator_entity">
                    <option value="">-- اختر الجهة --</option>
                    <option value="تشغيل تشاركي"
                        {{ old('daily_operator_entity', $dailyStationReport->daily_operator_entity ?? '') == 'تشغيل تشاركي' ? 'selected' : '' }}>
                        تشغيل تشاركي</option>
                    <option value="المؤسسة العامة لمياه الشرب"
                        {{ old('daily_operator_entity', $dailyStationReport->daily_operator_entity ?? '') == 'المؤسسة العامة لمياه الشرب' ? 'selected' : '' }}>
                        المؤسسة العامة لمياه الشرب</option>
                </select>
                @error('daily_operator_entity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6" id="operator_entity_name_container">
                <label for="daily_operator_entity_name" class="form-label">أسم الجهة المُشغلة <span
                        class="text-danger d-none" id="operator_entity_name_required_star">*</span></label>
                <input type="text" class="form-control @error('daily_operator_entity_name') is-invalid @enderror"
                    id="daily_operator_entity_name" name="daily_operator_entity_name"
                    value="{{ old('daily_operator_entity_name', $dailyStationReport->daily_operator_entity_name ?? '') }}">
                @error('daily_operator_entity_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div id="operational_details_section">
    <div class="card mb-4">
        <div class="card-header">
            <h5>معلومات تشغيل الآبار والمضخات</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="active_wells_during_pumping_count" class="form-label">عدد الآبار المُشغَلة</label>
                    <input type="number" min="0" max="7"
                        class="form-control @error('active_wells_during_pumping_count') is-invalid @enderror"
                        id="active_wells_during_pumping_count" name="active_wells_during_pumping_count"
                        value="{{ old('active_wells_during_pumping_count', $dailyStationReport->active_wells_during_pumping_count ?? 0) }}">
                    @error('active_wells_during_pumping_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="total_station_pumping_hours" class="form-label">إجمالي ساعات التشغيل/الضخ</label>
                    <input type="number" step="0.01" min="0"
                        class="form-control @error('total_station_pumping_hours') is-invalid @enderror"
                        name="total_station_pumping_hours"
                        value="{{ old('total_station_pumping_hours', $dailyStationReport->total_station_pumping_hours ?? '') }}">
                    @error('total_station_pumping_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row" id="wells_operating_hours_div">
                @for ($i = 1; $i <= 7; $i++)
                    <div class="col-md-3 mb-2 well-hours-input" id="well_{{ $i }}_div"
                        style="display:none;">
                        <label for="well_{{ $i }}_operating_hours" class="form-label">ساعات تشغيل بئر
                            {{ $i }} <span class="text-danger d-none">*</span></label>
                        <input type="number" step="0.01" min="0" max="24"
                            class="form-control @error('well_' . $i . '_operating_hours') is-invalid @enderror"
                            id="well_{{ $i }}_operating_hours"
                            name="well_{{ $i }}_operating_hours"
                            value="{{ old('well_' . $i . '_operating_hours', $dailyStationReport->{'well_' . $i . '_operating_hours'} ?? '') }}">
                        @error('well_' . $i . '_operating_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endfor
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="form-label">هل يوجد مضخة أفقية؟</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_horizontal_pump"
                                id="has_horizontal_pump_yes" value="1"
                                {{ old('has_horizontal_pump', isset($dailyStationReport) && $dailyStationReport->has_horizontal_pump ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_horizontal_pump_yes">نعم</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_horizontal_pump"
                                id="has_horizontal_pump_no" value="0"
                                {{ old('has_horizontal_pump', isset($dailyStationReport) && $dailyStationReport->has_horizontal_pump ? '1' : '0') == '0' || (!isset($dailyStationReport) && old('has_horizontal_pump') === null) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_horizontal_pump_no">لا</label>
                        </div>
                    </div>
                    @error('has_horizontal_pump')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4" id="horizontal_pump_hours_div" style="display:none;">
                    <label for="horizontal_pump_operating_hours" class="form-label">ساعات تشغيل المضخة الأفقية <span
                            class="text-danger d-none">*</span></label>
                    <input type="number" step="0.01" min="0" max="24"
                        class="form-control @error('horizontal_pump_operating_hours') is-invalid @enderror"
                        id="horizontal_pump_operating_hours" name="horizontal_pump_operating_hours"
                        value="{{ old('horizontal_pump_operating_hours', $dailyStationReport->horizontal_pump_operating_hours ?? '') }}">
                    @error('horizontal_pump_operating_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="station_operation_method_notes" class="form-label">طريقة عمل المحطة</label>
                    <input type="text"
                        class="form-control @error('station_operation_method_notes') is-invalid @enderror"
                        name="station_operation_method_notes"
                        value="{{ old('station_operation_method_notes', $dailyStationReport->station_operation_method_notes ?? '') }}">
                    @error('station_operation_method_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>التعقيم ومصدر الطاقة</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="form-label">هل يوجد تعقيم؟</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="daily_has_disinfection"
                                id="daily_has_disinfection_yes" value="1"
                                {{ old('daily_has_disinfection', isset($dailyStationReport) && $dailyStationReport->daily_has_disinfection ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="daily_has_disinfection_yes">نعم</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="daily_has_disinfection"
                                id="daily_has_disinfection_no" value="0"
                                {{ old('daily_has_disinfection', isset($dailyStationReport) && $dailyStationReport->daily_has_disinfection ? '1' : '0') == '0' || (!isset($dailyStationReport) && old('daily_has_disinfection') === null) ? 'checked' : '' }}>
                            <label class="form-check-label" for="daily_has_disinfection_no">لا</label>
                        </div>
                    </div>
                    @error('daily_has_disinfection')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-8" id="no_disinfection_reason_div" style="display:none;">
                    <label for="daily_no_disinfection_reason" class="form-label">سبب عدم وجود تعقيم <span
                            class="text-danger d-none">*</span></label>
                    <input type="text"
                        class="form-control @error('daily_no_disinfection_reason') is-invalid @enderror"
                        id="daily_no_disinfection_reason" name="daily_no_disinfection_reason"
                        value="{{ old('daily_no_disinfection_reason', $dailyStationReport->daily_no_disinfection_reason ?? '') }}">
                    @error('daily_no_disinfection_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="daily_energy_source" class="form-label">مصدر الطاقة التشغيلية <span
                            class="text-danger always-visible">*</span></label>
                    <select class="form-select @error('daily_energy_source') is-invalid @enderror"
                        id="daily_energy_source" name="daily_energy_source" required>
                        <option value="">-- اختر مصدر الطاقة --</option>
                        <option value="كهرباء"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'كهرباء' ? 'selected' : '' }}>
                            كهرباء</option>
                        <option value="مولدة"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'مولدة' ? 'selected' : '' }}>
                            مولدة</option>
                        <option value="طاقة شمسية"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'طاقة شمسية' ? 'selected' : '' }}>
                            طاقة شمسية</option>
                        <option value="دمج كهرباء وطاقة شمسية"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'دمج كهرباء وطاقة شمسية' ? 'selected' : '' }}>
                            دمج كهرباء وطاقة شمسية</option>
                        <option value="دمج مولدة وطاقة شمسية"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'دمج مولدة وطاقة شمسية' ? 'selected' : '' }}>
                            دمج مولدة وطاقة شمسية</option>
                        <option value="كهرباء ومولدة"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'كهرباء ومولدة' ? 'selected' : '' }}>
                            كهرباء ومولدة</option>
                        <option value="كهرباء ومولدة وطاقة شمسية"
                            {{ old('daily_energy_source', $dailyStationReport->daily_energy_source ?? '') == 'كهرباء ومولدة وطاقة شمسية' ? 'selected' : '' }}>
                            كهرباء ومولدة وطاقة شمسية</option>
                    </select>
                    @error('daily_energy_source')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="energy_details_container">
                <div id="blend_electric_solar_fields_group" class="energy-group" style="display:none;">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="hours_electric_solar_blend" class="form-label">ساعات (دمج) كهرباء + طاقة <span
                                    class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0" max="24"
                                class="form-control @error('hours_electric_solar_blend') is-invalid @enderror"
                                id="hours_electric_solar_blend" name="hours_electric_solar_blend"
                                value="{{ old('hours_electric_solar_blend', $dailyStationReport->hours_electric_solar_blend ?? '') }}">
                            @error('hours_electric_solar_blend')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div id="blend_generator_solar_fields_group" class="energy-group" style="display:none;">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="hours_generator_solar_blend" class="form-label">ساعات (دمج) مولدة + طاقة <span
                                    class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0" max="24"
                                class="form-control @error('hours_generator_solar_blend') is-invalid @enderror"
                                id="hours_generator_solar_blend" name="hours_generator_solar_blend"
                                value="{{ old('hours_generator_solar_blend', $dailyStationReport->hours_generator_solar_blend ?? '') }}">
                            @error('hours_generator_solar_blend')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="solar_fields_group" class="energy-group" style="display:none;">
                    <h6 class="mt-3 text-muted">تفاصيل الطاقة الشمسية</h6>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="hours_on_solar" class="form-label">ساعات تشغيل طاقة شمسية <span
                                    class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0" max="24"
                                class="form-control @error('hours_on_solar') is-invalid @enderror"
                                id="hours_on_solar" name="hours_on_solar"
                                value="{{ old('hours_on_solar', $dailyStationReport->hours_on_solar ?? '') }}">
                            @error('hours_on_solar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="electricity_fields_group" class="energy-group" style="display:none;">
                    <h6 class="mt-3 text-muted">تفاصيل استهلاك الكهرباء</h6>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="hours_on_electricity" class="form-label">ساعات تشغيل كهرباء <span
                                    class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0" max="24"
                                class="form-control @error('hours_on_electricity') is-invalid @enderror"
                                id="hours_on_electricity" name="hours_on_electricity"
                                value="{{ old('hours_on_electricity', $dailyStationReport->hours_on_electricity ?? '') }}">
                            @error('hours_on_electricity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="electricity_consumed_kwh" class="form-label">كمية الكهرباء المُستهلكة (kWh)
                                <span class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('electricity_consumed_kwh') is-invalid @enderror"
                                id="electricity_consumed_kwh" name="electricity_consumed_kwh"
                                value="{{ old('electricity_consumed_kwh', $dailyStationReport->electricity_consumed_kwh ?? '') }}">
                            @error('electricity_consumed_kwh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="electric_meter_reading_start" class="form-label">قراءة عداد الكهرباء (قبل)
                                <span class="text-danger d-none">*</span></label>
                            <input type="text"
                                class="form-control @error('electric_meter_reading_start') is-invalid @enderror"
                                id="electric_meter_reading_start" name="electric_meter_reading_start"
                                value="{{ old('electric_meter_reading_start', $dailyStationReport->electric_meter_reading_start ?? '') }}">
                            @error('electric_meter_reading_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="electric_meter_reading_end" class="form-label">قراءة عداد الكهرباء (بعد) <span
                                    class="text-danger d-none">*</span></label>
                            <input type="text"
                                class="form-control @error('electric_meter_reading_end') is-invalid @enderror"
                                id="electric_meter_reading_end" name="electric_meter_reading_end"
                                value="{{ old('electric_meter_reading_end', $dailyStationReport->electric_meter_reading_end ?? '') }}">
                            @error('electric_meter_reading_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-label">هل تم شحن عداد الكهرباء؟</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="electricity_meter_recharged_today"
                                        id="electricity_meter_recharged_today_yes" value="1"
                                        {{ old('electricity_meter_recharged_today', isset($dailyStationReport) && $dailyStationReport->electricity_meter_recharged_today ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="electricity_meter_recharged_today_yes">نعم</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="electricity_meter_recharged_today"
                                        id="electricity_meter_recharged_today_no" value="0"
                                        {{ old('electricity_meter_recharged_today', isset($dailyStationReport) && $dailyStationReport->electricity_meter_recharged_today ? '1' : '0') == '0' || (!isset($dailyStationReport) && old('electricity_meter_recharged_today') === null) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="electricity_meter_recharged_today_no">لا</label>
                                </div>
                            </div>
                            @error('electricity_meter_recharged_today')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="electricity_recharge_amount_div" style="display:none;">
                            <label for="electricity_recharged_amount_kwh" class="form-label">كمية الكهرباء المشحونة
                                (kWh) <span class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('electricity_recharged_amount_kwh') is-invalid @enderror"
                                id="electricity_recharged_amount_kwh" name="electricity_recharged_amount_kwh"
                                value="{{ old('electricity_recharged_amount_kwh', $dailyStationReport->electricity_recharged_amount_kwh ?? '') }}">
                            @error('electricity_recharged_amount_kwh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="generator_fields_group" class="energy-group" style="display:none;">
                    <h6 class="mt-3 text-muted">تفاصيل استهلاك الديزل وصيانة المولدة</h6>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="hours_on_generator" class="form-label">ساعات تشغيل المولدة <span
                                    class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0" max="24"
                                class="form-control @error('hours_on_generator') is-invalid @enderror"
                                id="hours_on_generator" name="hours_on_generator"
                                value="{{ old('hours_on_generator', $dailyStationReport->hours_on_generator ?? '') }}">
                            @error('hours_on_generator')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="diesel_consumed_liters_during_operation" class="form-label">كمية الديزل
                                المُستهلكة (لتر) <span class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('diesel_consumed_liters_during_operation') is-invalid @enderror"
                                id="diesel_consumed_liters_during_operation"
                                name="diesel_consumed_liters_during_operation"
                                value="{{ old('diesel_consumed_liters_during_operation', $dailyStationReport->diesel_consumed_liters_during_operation ?? '') }}">
                            @error('diesel_consumed_liters_during_operation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="generator_oil_changed_status" class="form-label">استبدال زيت للمولدة</label>
                            <select class="form-select @error('generator_oil_changed_status') is-invalid @enderror"
                                id="generator_oil_changed_status" name="generator_oil_changed_status">
                                <option value="لا يوجد"
                                    {{ old('generator_oil_changed_status', ($dailyStationReport->generator_oil_changed ?? false) == false || ($dailyStationReport->generator_oil_changed && ($dailyStationReport->oil_added_to_generator_liters ?? 0) == 0) ? 'لا يوجد' : ($dailyStationReport->oil_added_to_generator_liters > 0 ? 'زيادة الزيت' : 'استبدال الزيت بالكامل')) == 'لا يوجد' ? 'selected' : '' }}>
                                    لا يوجد</option>
                                <option value="زيادة الزيت"
                                    {{ old('generator_oil_changed_status', '') == 'زيادة الزيت' ? 'selected' : '' }}>
                                    زيادة الزيت</option>
                                <option value="استبدال الزيت بالكامل"
                                    {{ old('generator_oil_changed_status', '') == 'استبدال الزيت بالكامل' ? 'selected' : '' }}>
                                    استبدال الزيت بالكامل</option>
                            </select>
                            <input type="hidden" name="generator_oil_changed" id="generator_oil_changed_hidden"
                                value="{{ old('generator_oil_changed', $dailyStationReport->generator_oil_changed ?? false) ? '1' : '0' }}">
                        </div>
                        <div class="col-md-3" id="oil_added_div" style="display:none;">
                            <label for="oil_added_to_generator_liters" class="form-label">كمية الزيت المضافة (لتر)
                                <span class="text-danger d-none">*</span></label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('oil_added_to_generator_liters') is-invalid @enderror"
                                id="oil_added_to_generator_liters" name="oil_added_to_generator_liters"
                                value="{{ old('oil_added_to_generator_liters', $dailyStationReport->oil_added_to_generator_liters ?? '') }}">
                            @error('oil_added_to_generator_liters')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>كميات الديزل والمياه وتعديلات التجهيزات</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="water_pumped_to_network_m3" class="form-label">كمية المياه المضخة للشبكة (م³)</label>
                    <input type="number" step="0.01" min="0"
                        class="form-control @error('water_pumped_to_network_m3') is-invalid @enderror"
                        id="water_pumped_to_network_m3" name="water_pumped_to_network_m3"
                        value="{{ old('water_pumped_to_network_m3', $dailyStationReport->water_pumped_to_network_m3 ?? '') }}">
                    @error('water_pumped_to_network_m3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="diesel_in_station_total_liters" class="form-label">كامل كمية الديزل بالمحطة
                        (لتر)</label>
                    <input type="number" step="0.01" min="0"
                        class="form-control @error('diesel_in_station_total_liters') is-invalid @enderror"
                        id="diesel_in_station_total_liters" name="diesel_in_station_total_liters"
                        value="{{ old('diesel_in_station_total_liters', $dailyStationReport->diesel_in_station_total_liters ?? '') }}">
                    @error('diesel_in_station_total_liters')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="form-label">هل تم استلام ديزل جديد؟</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="new_diesel_shipment_received"
                                id="new_diesel_shipment_received_yes" value="1"
                                {{ old('new_diesel_shipment_received', isset($dailyStationReport) && $dailyStationReport->new_diesel_shipment_received ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="new_diesel_shipment_received_yes">نعم</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="new_diesel_shipment_received"
                                id="new_diesel_shipment_received_no" value="0"
                                {{ old('new_diesel_shipment_received', isset($dailyStationReport) && $dailyStationReport->new_diesel_shipment_received ? '1' : '0') == '0' || (!isset($dailyStationReport) && old('new_diesel_shipment_received') === null) ? 'checked' : '' }}>
                            <label class="form-check-label" for="new_diesel_shipment_received_no">لا</label>
                        </div>
                    </div>
                    @error('new_diesel_shipment_received')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4" id="diesel_shipment_quantity_div" style="display:none;">
                    <label for="new_diesel_shipment_quantity_liters" class="form-label">الكمية الجديدة المستلمة (لتر)
                        <span class="text-danger d-none">*</span></label>
                    <input type="number" step="0.01" min="0"
                        class="form-control @error('new_diesel_shipment_quantity_liters') is-invalid @enderror"
                        id="new_diesel_shipment_quantity_liters" name="new_diesel_shipment_quantity_liters"
                        value="{{ old('new_diesel_shipment_quantity_liters', $dailyStationReport->new_diesel_shipment_quantity_liters ?? '') }}">
                    @error('new_diesel_shipment_quantity_liters')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4" id="diesel_shipment_supplier_div" style="display:none;">
                    <label for="diesel_shipment_supplier" class="form-label">الجهة المُسلِمة للديزل <span
                            class="text-danger d-none">*</span></label>
                    <input type="text"
                        class="form-control @error('diesel_shipment_supplier') is-invalid @enderror"
                        id="diesel_shipment_supplier" name="diesel_shipment_supplier"
                        value="{{ old('diesel_shipment_supplier', $dailyStationReport->diesel_shipment_supplier ?? '') }}">
                    @error('diesel_shipment_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="form-label">هل تم تعديل تجهيزات المحطة؟</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="station_equipment_modified_today"
                                id="station_equipment_modified_today_yes" value="1"
                                {{ old('station_equipment_modified_today', isset($dailyStationReport) && $dailyStationReport->station_equipment_modified_today ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="station_equipment_modified_today_yes">نعم</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="station_equipment_modified_today"
                                id="station_equipment_modified_today_no" value="0"
                                {{ old('station_equipment_modified_today', isset($dailyStationReport) && $dailyStationReport->station_equipment_modified_today ? '1' : '0') == '0' || (!isset($dailyStationReport) && old('station_equipment_modified_today') === null) ? 'checked' : '' }}>
                            <label class="form-check-label" for="station_equipment_modified_today_no">لا</label>
                        </div>
                    </div>
                    @error('station_equipment_modified_today')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div id="equipment_modification_details_div" class="form-group row" style="display:none;">
                <div class="col-md-4">
                    <label for="equipment_modification_location_type" class="form-label">موقع التغيير ونوعه <span
                            class="text-danger d-none">*</span></label>
                    <input type="text"
                        class="form-control @error('equipment_modification_location_type') is-invalid @enderror"
                        id="equipment_modification_location_type" name="equipment_modification_location_type"
                        value="{{ old('equipment_modification_location_type', $dailyStationReport->equipment_modification_location_type ?? '') }}">
                    @error('equipment_modification_location_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="equipment_transferred_to_entity" class="form-label">الجهة التي تم النقل إليها (إن
                        وجد)</label>
                    <input type="text"
                        class="form-control @error('equipment_transferred_to_entity') is-invalid @enderror"
                        id="equipment_transferred_to_entity" name="equipment_transferred_to_entity"
                        value="{{ old('equipment_transferred_to_entity', $dailyStationReport->equipment_transferred_to_entity ?? '') }}">
                    @error('equipment_transferred_to_entity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 mt-2">
                    <label for="equipment_modification_description_reason" class="form-label">أسرد طبيعة التغيير وسببه
                        <span class="text-danger d-none">*</span></label>
                    <textarea class="form-control @error('equipment_modification_description_reason') is-invalid @enderror"
                        id="equipment_modification_description_reason" name="equipment_modification_description_reason" rows="3">{{ old('equipment_modification_description_reason', $dailyStationReport->equipment_modification_description_reason ?? '') }}</textarea>
                    @error('equipment_modification_description_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>ملاحظات إضافية</h5>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="shift_operator_notes" class="form-label">ملاحظات المُشغل المناوب في المحطة</label>
            <textarea class="form-control @error('shift_operator_notes') is-invalid @enderror" id="shift_operator_notes"
                name="shift_operator_notes" rows="4">{{ old('shift_operator_notes', $dailyStationReport->shift_operator_notes ?? '') }}</textarea>
            @error('shift_operator_notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        // Helper function to show/hide, clear, and manage 'required' attribute
        function toggleElement(elementOrId, show, clearValue = true, elementType = 'block', makeRequired = false) {
            const element = (typeof elementOrId === 'string') ? document.getElementById(elementOrId) : elementOrId;
            if (element) {
                element.style.display = show ? elementType : 'none';
                const inputs = element.querySelectorAll('input:not([type=radio]):not([type=checkbox]), select, textarea');

                inputs.forEach(input => {
                    if (!show && clearValue) {
                        if (input.tagName === 'SELECT') {
                            if (!['daily_energy_source', 'generator_oil_changed_status', 'daily_operational_status',
                                    'daily_operator_entity'
                                ].includes(input.id)) {
                                input.selectedIndex = 0;
                            }
                        } else {
                            input.value = '';
                        }
                    }
                    if (input.type !== 'hidden' && !input.readOnly) {
                        const label = document.querySelector(`label[for="${input.id}"]`);
                        const asterisk = label ? label.querySelector('.text-danger') : null;

                        if (show && makeRequired) {
                            input.setAttribute('required', 'required');
                            if (asterisk && asterisk.classList.contains('d-none')) asterisk.classList.remove(
                                'd-none');
                        } else {
                            input.removeAttribute('required');
                            if (asterisk && !asterisk.classList.contains('always-visible')) asterisk.classList.add(
                                'd-none');
                        }
                    }
                });

                if (!show && clearValue) {
                    const radioCheckboxes = element.querySelectorAll('input[type=radio], input[type=checkbox]');
                    radioCheckboxes.forEach(rc => {
                        rc.checked = false;
                        if (rc.type !== 'hidden' && !rc.readOnly) {
                            rc.removeAttribute('required');
                            const label = document.querySelector(`label[for="${rc.id}"]`) || rc.closest(
                                '.form-check')?.querySelector('label');
                            if (label) {
                                const asterisk = label.querySelector('.text-danger');
                                if (asterisk && !asterisk.classList.contains('always-visible')) asterisk.classList
                                    .add('d-none');
                            }
                        }
                    });
                }

                if (show && makeRequired && inputs.length === 0 && element.id) {
                    const mainLabel = document.querySelector(`label[for="${element.id}"]`);
                    if (mainLabel) {
                        const asterisk = mainLabel.querySelector('.text-danger.d-none');
                        if (asterisk) asterisk.classList.remove('d-none');
                    }
                } else if (!show && element.id && inputs.length === 0) {
                    const mainLabel = document.querySelector(`label[for="${element.id}"]`);
                    if (mainLabel) {
                        const asterisk = mainLabel.querySelector('.text-danger');
                        if (asterisk && !asterisk.classList.contains('always-visible')) asterisk.classList.add('d-none');
                    }
                }
            }
        }

        function updateFormVisibility() {
            const operationalStatusEl = document.getElementById('daily_operational_status');
            const operationalStatus = operationalStatusEl ? operationalStatusEl.value : 'عاملة';
            const operationalDetailsSection = document.getElementById('operational_details_section');

            toggleElement('stop_reason_div', operationalStatus === 'متوقفة' || operationalStatus === 'خارج الخدمة', true,
                'block', operationalStatus === 'متوقفة' || operationalStatus === 'خارج الخدمة');
            if (operationalDetailsSection) {
                operationalDetailsSection.style.display = (operationalStatus === 'عاملة') ? 'block' : 'none';
                if (operationalStatus !== 'عاملة') {
                    const inputsToClear = operationalDetailsSection.querySelectorAll(
                        'input:not([type=radio]):not([type=checkbox]), select, textarea');
                    inputsToClear.forEach(input => {
                        if (input.tagName === 'SELECT') {
                            if (!['daily_energy_source', 'generator_oil_changed_status'].includes(input.id)) input
                                .selectedIndex = 0;
                        } else {
                            input.value = '';
                        }
                        input.removeAttribute('required');
                        const label = document.querySelector(`label[for="${input.id}"]`);
                        if (label) {
                            const asterisk = label.querySelector('.text-danger');
                            if (asterisk && !asterisk.classList.contains('always-visible')) asterisk.classList.add(
                                'd-none');
                        }
                    });
                    operationalDetailsSection.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(rc => {
                        rc.checked = false;
                        if (rc.name === 'has_horizontal_pump' && rc.value === '0') rc.checked = true;
                        if (rc.name === 'daily_has_disinfection' && rc.value === '0') rc.checked = true;
                        if (rc.name === 'electricity_meter_recharged_today' && rc.value === '0') rc.checked = true;
                        if (rc.name === 'new_diesel_shipment_received' && rc.value === '0') rc.checked = true;
                        if (rc.name === 'station_equipment_modified_today' && rc.value === '0') rc.checked = true;
                        rc.removeAttribute('required');
                        const label = document.querySelector(`label[for="${rc.id}"]`) || rc.closest('.form-check')
                            ?.querySelector('label');
                        if (label) {
                            const asterisk = label.querySelector('.text-danger');
                            if (asterisk && !asterisk.classList.contains('always-visible')) asterisk.classList.add(
                                'd-none');
                        }
                    });
                    const oilChangeSelect = document.getElementById('generator_oil_changed_status');
                    if (oilChangeSelect) oilChangeSelect.value = 'لا يوجد';
                }
            }

            const operatorEntitySelect = document.getElementById('daily_operator_entity');
            const operatorEntity = operatorEntitySelect ? operatorEntitySelect.value : '';
            const entityNameInputContainer = document.getElementById('operator_entity_name_container');
            const partnershipOperators = ['IYD', 'أهالي البلدة', 'الرؤيا العالمية', 'الهاند', 'امل', 'غول', 'شركة العمران',
                'محمد الشمالية', 'عبدالله العتيق', 'أبو ادريس', 'احسان', 'شفق', 'متبرع خاص', 'مستثمر', 'عقد',
                'ياسر مدراتي', 'عبدالغفور عبدالجبار الحسن', 'محمد عبدالرحمن العمر', 'sdi'
            ];
            const entityNameRequiredStar = document.getElementById('operator_entity_name_required_star');

            if (entityNameInputContainer) {
                const existingInput = entityNameInputContainer.querySelector('#daily_operator_entity_name');
                const currentNameValue = existingInput ? existingInput.value :
                    "{{ old('daily_operator_entity_name', $dailyStationReport->daily_operator_entity_name ?? '') }}";

                // Remove only the input/select, not the label
                while (entityNameInputContainer.children.length > 1) { // Keep the first child (label)
                    entityNameInputContainer.removeChild(entityNameInputContainer.lastChild);
                }


                let newElement;
                if (operatorEntity === 'المؤسسة العامة لمياه الشرب') {
                    newElement = document.createElement('input');
                    newElement.type = 'text';
                    newElement.className = 'form-control';
                    newElement.id = 'daily_operator_entity_name';
                    newElement.name = 'daily_operator_entity_name';
                    newElement.value = 'المؤسسة العامة لمياه الشرب';
                    newElement.readOnly = true;
                    if (entityNameRequiredStar) entityNameRequiredStar.classList.add('d-none');
                } else if (operatorEntity === 'تشغيل تشاركي') {
                    newElement = document.createElement('select');
                    newElement.className =
                        'form-select @error('daily_operator_entity_name') is-invalid @enderror';
                    newElement.id = 'daily_operator_entity_name';
                    newElement.name = 'daily_operator_entity_name';
                    newElement.setAttribute('required', 'required');
                    if (entityNameRequiredStar) entityNameRequiredStar.classList.remove('d-none');

                    const defaultOption = document.createElement('option');
                    defaultOption.value = "";
                    defaultOption.textContent = "-- اختر الجهة الشريكة --";
                    newElement.appendChild(defaultOption);

                    partnershipOperators.forEach(op => {
                        const option = document.createElement('option');
                        option.value = op;
                        option.textContent = op;
                        if (op === currentNameValue || op === "{{ old('daily_operator_entity_name') }}") {
                            option.selected = true;
                        }
                        newElement.appendChild(option);
                    });
                } else {
                    newElement = document.createElement('input');
                    newElement.type = 'text';
                    newElement.className =
                        'form-control @error('daily_operator_entity_name') is-invalid @enderror';
                    newElement.id = 'daily_operator_entity_name';
                    newElement.name = 'daily_operator_entity_name';
                    newElement.value = (operatorEntity === '') ? '' : currentNameValue;
                    if (entityNameRequiredStar) entityNameRequiredStar.classList.add('d-none');
                }
                entityNameInputContainer.appendChild(newElement);
            }

            if (operationalStatus !== 'عاملة') return;

            const activeWellsCountInput = document.getElementById('active_wells_during_pumping_count');
            const activeWellsCount = activeWellsCountInput ? (parseInt(activeWellsCountInput.value) || 0) : 0;
            for (let i = 1; i <= 7; i++) {
                toggleElement(`well_${i}_div`, i <= activeWellsCount, true, 'block', i <= activeWellsCount);
            }

            const hasHorizontalPumpRadio = document.querySelector('input[name="has_horizontal_pump"]:checked');
            const hasHorizontalPump = hasHorizontalPumpRadio ? hasHorizontalPumpRadio.value === '1' : false;
            toggleElement('horizontal_pump_hours_div', hasHorizontalPump, true, 'block', hasHorizontalPump);

            const hasDisinfectionRadio = document.querySelector('input[name="daily_has_disinfection"]:checked');
            const hasDisinfection = hasDisinfectionRadio ? hasDisinfectionRadio.value === '1' : false;
            toggleElement('no_disinfection_reason_div', !hasDisinfection, true, 'block', !hasDisinfection);

            const energySourceSelect = document.getElementById('daily_energy_source');
            const energySource = energySourceSelect ? energySourceSelect.value : '';
            document.querySelectorAll('#energy_details_container .energy-group').forEach(group => {
                toggleElement(group.id, false, true);
            });

            const showElectricity = energySource.includes('كهرباء');
            const showGenerator = energySource.includes('مولدة');
            const showSolar = energySource.includes('طاقة شمسية');

            toggleElement('electricity_fields_group', showElectricity, !showElectricity);
            toggleElement('generator_fields_group', showGenerator, !showGenerator);
            toggleElement('solar_fields_group', showSolar, !showSolar);

            toggleElement('blend_electric_solar_fields_group', energySource === 'دمج كهرباء وطاقة شمسية', true, 'block',
                energySource === 'دمج كهرباء وطاقة شمسية');
            toggleElement('blend_generator_solar_fields_group', energySource === 'دمج مولدة وطاقة شمسية', true, 'block',
                energySource === 'دمج مولدة وطاقة شمسية');

            if (showElectricity) {
                ['hours_on_electricity', 'electricity_consumed_kwh', 'electric_meter_reading_start',
                    'electric_meter_reading_end'
                ].forEach(id => {
                    toggleElement(document.getElementById(id), true, false, 'block', true);
                });
            }
            if (showSolar) {
                toggleElement('hours_on_solar', true, false, 'block', true);
            }
            if (showGenerator) {
                ['hours_on_generator', 'diesel_consumed_liters_during_operation'].forEach(id => {
                    toggleElement(document.getElementById(id), true, false, 'block', true);
                });
            }

            const oilChangeStatusSelect = document.getElementById('generator_oil_changed_status');
            const oilChangeStatus = oilChangeStatusSelect ? oilChangeStatusSelect.value : 'لا يوجد';
            const generatorOilChangedHiddenInput = document.getElementById('generator_oil_changed_hidden');
            const showOilAdded = (oilChangeStatus === 'زيادة الزيت' || oilChangeStatus === 'استبدال الزيت بالكامل');
            toggleElement('oil_added_div', showGenerator && showOilAdded, true, 'block', showGenerator && showOilAdded);
            if (generatorOilChangedHiddenInput) {
                generatorOilChangedHiddenInput.value = (showGenerator && oilChangeStatus !== 'لا يوجد') ? '1' : '0';
            }

            const electricityRechargedRadio = document.querySelector(
                'input[name="electricity_meter_recharged_today"]:checked');
            const electricityRecharged = electricityRechargedRadio ? electricityRechargedRadio.value === '1' : false;
            toggleElement('electricity_recharge_amount_div', showElectricity && electricityRecharged, true, 'block',
                showElectricity && electricityRecharged);

            const newDieselRadio = document.querySelector('input[name="new_diesel_shipment_received"]:checked');
            const newDieselReceived = newDieselRadio ? newDieselRadio.value === '1' : false;
            toggleElement('diesel_shipment_quantity_div', newDieselReceived, true, 'block', newDieselReceived);
            toggleElement('diesel_shipment_supplier_div', newDieselReceived, true, 'block', newDieselReceived);

            const equipmentModifiedRadio = document.querySelector('input[name="station_equipment_modified_today"]:checked');
            const equipmentModified = equipmentModifiedRadio ? equipmentModifiedRadio.value === '1' : false;
            toggleElement('equipment_modification_details_div', equipmentModified, !equipmentModified, 'flex');
            if (equipmentModified) {
                ['equipment_modification_location_type', 'equipment_modification_description_reason'].forEach(id => {
                    toggleElement(document.getElementById(id), true, false, 'block', true);
                });
            } else {
                ['equipment_modification_location_type', 'equipment_modification_description_reason',
                    'equipment_transferred_to_entity'
                ].forEach(id => {
                    toggleElement(document.getElementById(id), false, true); // Hide, clear, and remove required
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const controlsToWatch = [
                'daily_operational_status', 'daily_operator_entity',
                'active_wells_during_pumping_count',
                'daily_energy_source', 'generator_oil_changed_status'
                // Radio buttons are handled by name below
            ];

            controlsToWatch.forEach(controlId => {
                const control = document.getElementById(controlId);
                if (control) {
                    control.addEventListener('change', updateFormVisibility);
                }
            });
            document.getElementsByName('has_horizontal_pump').forEach(radio => radio.addEventListener('change',
                updateFormVisibility));
            document.getElementsByName('daily_has_disinfection').forEach(radio => radio.addEventListener('change',
                updateFormVisibility));
            document.getElementsByName('electricity_meter_recharged_today').forEach(radio => radio.addEventListener(
                'change', updateFormVisibility));
            document.getElementsByName('new_diesel_shipment_received').forEach(radio => radio.addEventListener(
                'change', updateFormVisibility));
            document.getElementsByName('station_equipment_modified_today').forEach(radio => radio.addEventListener(
                'change', updateFormVisibility));

            updateFormVisibility(); // Initial call
        });
    </script>
@endPushOnce
