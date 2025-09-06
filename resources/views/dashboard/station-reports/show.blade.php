@extends('layouts.app')

@section('title', 'عرض تقرير المحطة')

@section('content')
    <section class="content-header">
        {{-- ... (Header remains the same) ... --}}
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">تفاصيل التقرير</h3>
                            <div class="card-tools">
                                @can('station-reports.edit')
                                    <a href="{{ route('dashboard.station-reports.edit', $stationReport) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                @endcan
                                <a href="{{ route('dashboard.station-reports.paper', [
                                    'station' => $stationReport->station_id,
                                    'year' => $stationReport->report_date->year,
                                    'month' => $stationReport->report_date->month,
                                ]) }}"
                                    class="btn btn-info btn-sm" target="_blank">
                                    <i class="fas fa-print"></i> عرض التقرير الورقي
                                </a>
                                <a href="{{ route('dashboard.station-reports.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> العودة
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- ==================== Basic Information ==================== --}}
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">المعلومات الأساسية</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4"><strong>اسم المحطة:</strong>
                                            <p class="text-muted">{{ $stationReport->station->station_name ?? 'غير محدد' }}
                                            </p>
                                        </div>
                                        <div class="col-md-4"><strong>تاريخ التقرير:</strong>
                                            <p class="text-muted">
                                                {{ $stationReport->report_date ? $stationReport->report_date->translatedFormat('l, d F Y') : 'غير محدد' }}
                                            </p>
                                        </div>
                                        <div class="col-md-4"><strong>حالة التشغيل:</strong>
                                            <p class="text-muted">
                                                @if ($stationReport->status)
                                                    <span
                                                        class="badge badge-{{ $stationReport->status->value == 'working' ? 'success' : 'danger' }}">{{ $stationReport->status->getLabel() }}</span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        @if ($stationReport->status->value == 'stopped' && !empty($stationReport->stop_reason))
                                            <div class="col-md-4"><strong>سبب التوقف:</strong>
                                                <p class="text-muted">{{ $stationReport->stop_reason }}</p>
                                            </div>
                                        @endif
                                        <div class="col-md-4"><strong>المشغل:</strong>
                                            <p class="text-muted">{{ $stationReport->operator->name ?? 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-md-4"><strong>الجهة المشغلة:</strong>
                                            <p class="text-muted">
                                                {{ $stationReport->operating_entity?->getLabel() ?? 'غير محدد' }}</p>
                                        </div>
                                        @if (!empty($stationReport->operating_entity_name))
                                            <div class="col-md-4"><strong>الشريك:</strong>
                                                <p class="text-muted">{{ $stationReport->operating_entity_name }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ==================== Pumping and Wells Information (Conditional) ==================== --}}
                            @if ($stationReport->status->value == 'working')
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">بيانات الضخ والآبار</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @if ($stationReport->number_well > 0)
                                                <div class="col-md-4"><strong>عدد الآبار العاملة:</strong>
                                                    <p class="text-muted">{{ $stationReport->number_well }}</p>
                                                </div>
                                            @endif
                                            @if ($stationReport->water_pumped_m3 > 0)
                                                <div class="col-md-4"><strong>كمية المياه المضخوخة:</strong>
                                                    <p class="text-muted">{{ $stationReport->water_pumped_m3 }} م³</p>
                                                </div>
                                            @endif
                                            {{-- Note: water_production_m3 is not in your model --}}
                                            <div class="col-md-4"><strong>هل يوجد تعقيم؟:</strong>
                                                <p class="text-muted"><span
                                                        class="badge badge-{{ $stationReport->is_sterile ? 'success' : 'danger' }}">{{ $stationReport->is_sterile ? 'نعم' : 'لا' }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4"><strong>توجد مضخة أفقية؟:</strong>
                                                <p class="text-muted"><span
                                                        class="badge badge-{{ $stationReport->is_horizontal_pump ? 'success' : 'danger' }}">{{ $stationReport->is_horizontal_pump ? 'نعم' : 'لا' }}</span>
                                                </p>
                                            </div>
                                            @if ($stationReport->is_horizontal_pump && $stationReport->horizontal_pump_operating_hours > 0)
                                                <div class="col-md-4"><strong>ساعات عمل الأفقية:</strong>
                                                    <p class="text-muted">
                                                        {{ $stationReport->horizontal_pump_operating_hours }} ساعة</p>
                                                </div>
                                            @endif
                                            @if ($stationReport->pumpingSector)
                                                <div class="col-md-4"><strong>قطاع الضخ:</strong>
                                                    <p class="text-muted">
                                                        {{ $stationReport->pumpingSector->name ?? 'غير محدد' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Well Operating Hours --}}
                                        @if (!empty($stationReport->well_operating_hours) && max($stationReport->well_operating_hours) > 0)
                                            <hr>
                                            <h5>ساعات تشغيل الآبار</h5>
                                            <div class="row">
                                                @foreach ($stationReport->well_operating_hours as $index => $hours)
                                                    @if ($hours > 0)
                                                        <div class="col-md-3"><strong>ساعات البئر
                                                                {{ $index + 1 }}:</strong>
                                                            <p class="text-muted">{{ $hours }} ساعة</p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- ==================== Energy Information ==================== --}}
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">بيانات الطاقة</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if (!empty($stationReport->power_source))
                                            <div class="col-md-4"><strong>مصدر الطاقة الرئيسي:</strong>
                                                <p class="text-muted">{{ $stationReport->power_source->getLabel() }}</p>
                                            </div>
                                        @endif
                                        @if ($stationReport->electricity_hours > 0)
                                            <div class="col-md-4"><strong>ساعات تشغيل الكهرباء:</strong>
                                                <p class="text-muted">{{ $stationReport->electricity_hours }} ساعة</p>
                                            </div>
                                        @endif
                                        @if ($stationReport->solar_hours > 0)
                                            <div class="col-md-4"><strong>ساعات تشغيل الطاقة الشمسية:</strong>
                                                <p class="text-muted">{{ $stationReport->solar_hours }} ساعة</p>
                                            </div>
                                        @endif
                                        @if ($stationReport->generator_hours > 0)
                                            <div class="col-md-4"><strong>ساعات تشغيل المولدة:</strong>
                                                <p class="text-muted">{{ $stationReport->generator_hours }} ساعة</p>
                                            </div>
                                        @endif
                                        @if ($stationReport->electricity_power_kwh > 0)
                                            <div class="col-md-4"><strong>استهلاك الكهرباء:</strong>
                                                <p class="text-muted">{{ $stationReport->electricity_power_kwh }} KWH</p>
                                            </div>
                                        @endif
                                        @if (!empty($stationReport->electricity_Counter_number_before))
                                            <div class="col-md-4"><strong>عداد الكهرباء (قبل):</strong>
                                                <p class="text-muted">
                                                    {{ $stationReport->electricity_Counter_number_before }}</p>
                                            </div>
                                        @endif
                                        @if (!empty($stationReport->electricity_Counter_number_after))
                                            <div class="col-md-4"><strong>عداد الكهرباء (بعد):</strong>
                                                <p class="text-muted">
                                                    {{ $stationReport->electricity_Counter_number_after }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ==================== Diesel Information ==================== --}}
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">بيانات الديزل</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if ($stationReport->diesel_consumed_liters > 0)
                                            <div class="col-md-4"><strong>استهلاك الديزل:</strong>
                                                <p class="text-muted">{{ $stationReport->diesel_consumed_liters }} لتر</p>
                                            </div>
                                        @endif
                                        @if ($stationReport->Total_desil_liters > 0)
                                            <div class="col-md-4"><strong>الكمية الإجمالية:</strong>
                                                <p class="text-muted">{{ $stationReport->Total_desil_liters }} لتر</p>
                                            </div>
                                        @endif
                                        <div class="col-md-4"><strong>هل تم استلام ديزل؟:</strong>
                                            <p class="text-muted"><span
                                                    class="badge badge-{{ $stationReport->is_diesel_received ? 'success' : 'danger' }}">{{ $stationReport->is_diesel_received ? 'نعم' : 'لا' }}</span>
                                            </p>
                                        </div>
                                        @if ($stationReport->is_diesel_received)
                                            @if ($stationReport->quantity_of_diesel_received_liters > 0)
                                                <div class="col-md-6"><strong>الكمية المستلمة:</strong>
                                                    <p class="text-muted">
                                                        {{ $stationReport->quantity_of_diesel_received_liters }} لتر</p>
                                                </div>
                                            @endif
                                            @if (!empty($stationReport->diesel_source))
                                                <div class="col-md-6"><strong>مصدر الديزل:</strong>
                                                    <p class="text-muted">{{ $stationReport->diesel_source }}</p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ==================== Maintenance ==================== --}}
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">الصيانة</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4"><strong>هل تم شحن العداد؟:</strong>
                                            <p class="text-muted"><span
                                                    class="badge badge-{{ $stationReport->is_the_electricity_meter_charged ? 'success' : 'danger' }}">{{ $stationReport->is_the_electricity_meter_charged ? 'نعم' : 'لا' }}</span>
                                            </p>
                                        </div>
                                        @if ($stationReport->is_the_electricity_meter_charged && $stationReport->quantity_of_electricity_meter_charged_kwh > 0)
                                            <div class="col-md-8"><strong>كمية الشحن:</strong>
                                                <p class="text-muted">
                                                    {{ $stationReport->quantity_of_electricity_meter_charged_kwh }} KWH</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><strong>هل تم تغيير زيت؟:</strong>
                                            <p class="text-muted"><span
                                                    class="badge badge-{{ $stationReport->is_there_an_oil_change ? 'success' : 'danger' }}">{{ $stationReport->is_there_an_oil_change ? 'نعم' : 'لا' }}</span>
                                            </p>
                                        </div>
                                        @if ($stationReport->is_there_an_oil_change && $stationReport->quantity_of_oil_added > 0)
                                            <div class="col-md-8"><strong>كمية الزيت المضافة:</strong>
                                                <p class="text-muted">{{ $stationReport->quantity_of_oil_added }} لتر</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><strong>هل تم تعديل المحطة؟:</strong>
                                            <p class="text-muted"><span
                                                    class="badge badge-{{ $stationReport->has_station_been_modified ? 'success' : 'danger' }}">{{ $stationReport->has_station_been_modified ? 'نعم' : 'لا' }}</span>
                                            </p>
                                        </div>
                                        @if ($stationReport->has_station_been_modified)
                                            @if (!empty($stationReport->station_modification_type))
                                                <div class="col-md-4"><strong>نوع التعديل:</strong>
                                                    <p class="text-muted">{{ $stationReport->station_modification_type }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if (!empty($stationReport->station_modification_notes))
                                                <div class="col-md-12"><strong>ملاحظات التعديل:</strong>
                                                    <p class="text-muted">{{ $stationReport->station_modification_notes }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ==================== Notes ==================== --}}
                            @if (!empty($stationReport->notes))
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">ملاحظات عامة</h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">{{ $stationReport->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
