@extends('layouts.app')

@section('title', 'عرض تقرير المحطة')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>عرض تقرير المحطة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.station-reports.index') }}">تقارير المحطات</a></li>
                        <li class="breadcrumb-item active">عرض التقرير</li>
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
                            <h3 class="card-title">تفاصيل التقرير</h3>
                            <div class="card-tools">
                                @can('station-reports.edit')
                                    <a href="{{ route('dashboard.station-reports.edit', $stationReport) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                @endcan
                                <a href="{{ route('dashboard.station-reports.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> العودة
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Basic Information --}}
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">المعلومات الأساسية</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>تاريخ التقرير:</strong>
                                            <p class="text-muted">{{ $stationReport->report_date ? $stationReport->report_date->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>المحطة:</strong>
                                            <p class="text-muted">{{ $stationReport->station->station_name ?? 'غير محدد' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>المشغل:</strong>
                                            <p class="text-muted">{{ $stationReport->operator->name ?? 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>الحالة التشغيلية:</strong>
                                            <p class="text-muted">
                                                @if($stationReport->status)
                                                    <span class="badge badge-{{ $stationReport->status->getColor() }}">
                                                        {{ $stationReport->status->getLabel() }}
                                                    </span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>الجهة المشغلة:</strong>
                                            <p class="text-muted">
                                                @if($stationReport->operating_entity)
                                                    {{ $stationReport->operating_entity->getLabel() }}
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>اسم الجهة المشغلة:</strong>
                                            <p class="text-muted">{{ $stationReport->operating_entity_name ?? 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Wells Information --}}
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">معلومات الآبار والتشغيل</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>عدد الآبار:</strong>
                                            <p class="text-muted">{{ $stationReport->number_well ?? 0 }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>إجمالي ساعات التشغيل:</strong>
                                            <p class="text-muted">{{ $stationReport->operating_hours ?? 0 }} ساعة</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>كمية المياه المضخوخة:</strong>
                                            <p class="text-muted">{{ $stationReport->water_pumped_m3 ?? 0 }} م³</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>كمية المياه المنتجة:</strong>
                                            <p class="text-muted">{{ $stationReport->Water_production_m3 ?? 0 }} م³</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Energy Information --}}
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">معلومات الطاقة</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>مصدر الطاقة الرئيسي:</strong>
                                            <p class="text-muted">
                                                @if($stationReport->power_source)
                                                    {{ $stationReport->power_source->getLabel() }}
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>ساعات الكهرباء:</strong>
                                            <p class="text-muted">{{ $stationReport->electricity_hours ?? 0 }} ساعة</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>استهلاك الكهرباء:</strong>
                                            <p class="text-muted">{{ $stationReport->electricity_power_kwh ?? 0 }} كيلو واط ساعة</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>ساعات الطاقة الشمسية:</strong>
                                            <p class="text-muted">{{ $stationReport->solar_hours ?? 0 }} ساعة</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>ساعات المولدة:</strong>
                                            <p class="text-muted">{{ $stationReport->generator_hours ?? 0 }} ساعة</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>استهلاك الديزل:</strong>
                                            <p class="text-muted">{{ $stationReport->diesel_consumed_liters ?? 0 }} لتر</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Diesel Information --}}
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">معلومات الديزل</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>إجمالي الديزل المتوفر:</strong>
                                            <p class="text-muted">{{ $stationReport->Total_desil_liters ?? 0 }} لتر</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>هل تم استلام ديزل:</strong>
                                            <p class="text-muted">
                                                @if($stationReport->is_diesel_received === true)
                                                    <span class="badge badge-success">نعم</span>
                                                @elseif($stationReport->is_diesel_received === false)
                                                    <span class="badge badge-danger">لا</span>
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>كمية الديزل المستلمة:</strong>
                                            <p class="text-muted">{{ $stationReport->quantity_of_diesel_received_liters ?? 0 }} لتر</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>مصدر الديزل:</strong>
                                            <p class="text-muted">{{ $stationReport->diesel_source ?? 'غير محدد' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modifications --}}
                            @if($stationReport->has_station_been_modified || $stationReport->station_modification_type || $stationReport->station_modification_notes)
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">التعديلات على المحطة</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>هل تم تعديل المحطة:</strong>
                                                <p class="text-muted">
                                                    @if($stationReport->has_station_been_modified === true)
                                                        <span class="badge badge-success">نعم</span>
                                                    @elseif($stationReport->has_station_been_modified === false)
                                                        <span class="badge badge-danger">لا</span>
                                                    @else
                                                        غير محدد
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <strong>نوع التعديلات:</strong>
                                                <p class="text-muted">{{ $stationReport->station_modification_type ?? 'غير محدد' }}</p>
                                            </div>
                                        </div>
                                        @if($stationReport->station_modification_notes)
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong>ملاحظات التعديلات:</strong>
                                                    <p class="text-muted">{{ $stationReport->station_modification_notes }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Notes --}}
                            @if($stationReport->notes || $stationReport->stop_reason)
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">الملاحظات</h3>
                                    </div>
                                    <div class="card-body">
                                        @if($stationReport->stop_reason)
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong>سبب التوقف:</strong>
                                                    <p class="text-muted">{{ $stationReport->stop_reason }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($stationReport->notes)
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong>ملاحظات عامة:</strong>
                                                    <p class="text-muted">{{ $stationReport->notes }}</p>
                                                </div>
                                            </div>
                                        @endif
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
