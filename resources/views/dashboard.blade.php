@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@push('styles')
    {{-- مكتبات التصميم الأساسية --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{-- مكتبات الخريطة التفاعلية --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-measure@3.1.0/dist/leaflet-measure.css">

    {{-- تنسيقات مخصصة للصفحة --}}
    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single { height: calc(2.25rem + 2px); padding: .375rem .75rem; }
        .info-box-icon { font-size: 2.5rem; }
        .timeline>div>.timeline-item { margin-right: 60px; }
        #map-container { position: relative; height: 550px; }
        #map { height: 100%; width: 100%; }
        #map-sidebar { position: absolute; top: 10px; right: -350px; width: 330px; height: calc(100% - 20px); background: rgba(255, 255, 255, 0.95); z-index: 1000; transition: right 0.3s ease-in-out; border-radius: 5px; box-shadow: -2px 0 10px rgba(0,0,0,0.2); overflow-y: auto; }
        #map-sidebar.open { right: 10px; }
        .sidebar-close { position: absolute; top: 5px; left: 10px; font-size: 1.5rem; cursor: pointer; color: #555; }
        .sidebar-close:hover { color: #000; }
        .h-100 { height: 100% !important; }
        .bg-gradient-purple { background-color: #6f42c1 !important; color: #fff; }
        .bg-gradient-teal { background-color: #20c997 !important; color: #fff; }
        .bg-gradient-maroon { background-color: #d81b60 !important; color: #fff; }
        .bg-gradient-dark { background-color: #343a40 !important; color: #fff; }
        .project-stat-card { transition: transform 0.2s; }
        .project-stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- ========================================================== --}}
    {{-- القسم العلوي: العنوان وفلتر المحطات --}}
    {{-- ========================================================== --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0 text-dark">{{ $message }}</h3>
        </div>
        <div class="col-md-6">
            <form action="{{ route('dashboard.index') }}" method="GET" id="stationFilterForm">
                <div class="form-group mb-0">
                    <select class="form-control select2bs4" name="station_id" onchange="this.form.submit()">
                        <option value="">-- عرض النظرة العامة للنظام والمشاريع --</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ optional($selectedStation)->id == $station->id ? 'selected' : '' }}>
                                {{ $station->station_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    <hr>

    @if(!$selectedStation)
        {{-- ================================================================= --}}
        {{-- قسم إحصائيات المشاريع (جديد ومحسن) --}}
        {{-- ================================================================= --}}
        @if(isset($statistics['projects_kpi']))
        <div class="card card-outline card-indigo shadow-sm mb-4">
            <div class="card-header border-0">
                <h3 class="card-title text-indigo font-weight-bold">
                    <i class="fas fa-chart-line mr-1"></i> لوحة مؤشرات المشاريع
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body pt-2">
                {{-- الصف الأول: المؤشرات الرقمية --}}
                <div class="row">
                    <!-- إجمالي المشاريع -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3 bg-light project-stat-card">
                            <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-folder"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">إجمالي المشاريع</span>
                                <span class="info-box-number display-4 text-indigo">{{ $statistics['projects_kpi']['total_count'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- القيمة المالية -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3 bg-light project-stat-card">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">القيمة الإجمالية</span>
                                <span class="info-box-number text-success">${{ number_format($statistics['projects_kpi']['total_value'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- مشاريع قيد التنفيذ -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3 bg-light project-stat-card">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-hard-hat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">قيد التنفيذ</span>
                                <span class="info-box-number text-info">{{ $statistics['projects_kpi']['active_count'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- تنبيهات ومخالفات -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3 bg-light project-stat-card">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">مخالفات العقود</span>
                                <span class="info-box-number text-danger">{{ $statistics['contract_alerts'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الصف الثاني: المخططات البيانية --}}
                <div class="row mt-2">
                    <!-- الرسم البياني للاتجاه السنوي -->
                    <div class="col-md-8">
                        <div class="card shadow-none border">
                            <div class="card-header border-0">
                                <h3 class="card-title text-muted">النمو السنوي للمشاريع (القيمة والعدد)</h3>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="projectsTrendChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- الرسم البياني لتوزيع الحالات -->
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-header border-0">
                                <h3 class="card-title text-muted">توزيع حالات المشاريع</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="projectStatusChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الصف الثالث: الجداول التفصيلية --}}
                <div class="row mt-2">
                    <!-- المشاريع القريبة من الانتهاء -->
                    <div class="col-md-6">
                        <div class="card card-outline card-warning h-100">
                            <div class="card-header">
                                <h3 class="card-title text-bold text-dark">
                                    <i class="fas fa-stopwatch mr-1 text-warning"></i> مشاريع تنتهي قريباً (45 يوم)
                                </h3>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>المشروع</th>
                                            <th>تاريخ النهاية</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($statistics['upcoming_projects'] as $project)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('dashboard.projects.show', $project->id) }}" class="text-dark font-weight-bold">
                                                        {{ Str::limit($project->name, 30) }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $project->organization->name ?? '' }}</small>
                                                </td>
                                                <td class="text-danger font-weight-bold align-middle">
                                                    {{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge badge-warning">{{ $project->mainStatus->name ?? '' }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">لا توجد مشاريع مقاربة على الانتهاء</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- أهم المنظمات حسب التمويل -->
                    <div class="col-md-6">
                        <div class="card card-outline card-success h-100">
                            <div class="card-header">
                                <h3 class="card-title text-bold text-dark">
                                    <i class="fas fa-hand-holding-usd mr-1 text-success"></i> أعلى المنظمات تمويلاً
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @forelse($statistics['projects_by_org'] as $org)
                                        <li class="item">
                                            <div class="product-info ml-2">
                                                <a href="javascript:void(0)" class="product-title">
                                                    {{ $org->organization->name ?? 'غير محدد' }}
                                                    <span class="badge badge-success float-right">${{ number_format($org->total_value) }}</span>
                                                </a>
                                                <span class="product-description">
                                                    عدد المشاريع: {{ $org->count }}
                                                </span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="item text-center p-3">لا توجد بيانات متاحة.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif {{-- تم إضافة هذا الإغلاق للشرط الأول الخاص بـ !$selectedStation --}}

    {{-- ================================================================= --}}
    {{-- الخريطة والمخطط الرئيسي --}}
    {{-- ================================================================= --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-info">
                <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-map-marked-alt mr-1"></i> الخريطة التفاعلية</h3></div>
                <div class="card-body p-0">
                    <div id="map-container">
                        <div id="map"></div>
                        <div id="map-sidebar"><span class="sidebar-close" onclick="closeSidebar()">&times;</span><div class="p-3" id="sidebar-content"></div></div>
                    </div>
                </div>
            </div>
        </div>
        @if(!$selectedStation)
            <div class="col-lg-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> حالة المحطات</h3>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="stationStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(!$selectedStation)
        {{-- ================================================================= --}}
        {{-- محتوى لوحة التحكم العامة (المياه) --}}
        {{-- ================================================================= --}}
        <hr>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-users"></i></span><div class="info-box-content"><span class="info-box-text">العائلات المستفيدة</span><span class="info-box-number">{{ number_format($statistics['beneficiary_families'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-database"></i></span><div class="info-box-content"><span class="info-box-text">سعة التخزين (م³)</span><span class="info-box-number">{{ number_format($statistics['total_water_storage_m3'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-bolt"></i></span><div class="info-box-content"><span class="info-box-text">القدرة التوليدية (KVA)</span><span class="info-box-number">{{ number_format($statistics['total_generation_capacity_kva'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-danger"><i class="fas fa-tools"></i></span><div class="info-box-content"><span class="info-box-text">صيانة قيد التنفيذ</span><span class="info-box-number">{{ $statistics['maintenance_in_progress'] ?? 0 }}</span></div></div></div>
        </div>

        <h4 class="mb-3 mt-4">جرد مكونات النظام</h4>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-industry"></i></span><div class="info-box-content"><span class="info-box-text">المحطات</span><span class="info-box-number">{{ $statistics['stations_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-building"></i></span><div class="info-box-content"><span class="info-box-text">الوحدات الإدارية</span><span class="info-box-number">{{ $statistics['units_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-success"><i class="fas fa-city"></i></span><div class="info-box-content"><span class="info-box-text">البلدات</span><span class="info-box-number">{{ $statistics['towns_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-warning"><i class="fas fa-user-shield"></i></span><div class="info-box-content"><span class="info-box-text">المستخدمين</span><span class="info-box-number">{{ $statistics['users_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-water"></i></span><div class="info-box-content"><span class="info-box-text">الآبار</span><span class="info-box-number">{{ $statistics['wells_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-secondary"><i class="fas fa-bolt"></i></span><div class="info-box-content"><span class="info-box-text">مجموعات التوليد</span><span class="info-box-number">{{ $statistics['generation_groups_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-dark"><i class="fas fa-gas-pump"></i></span><div class="info-box-content"><span class="info-box-text">خزانات الديزل</span><span class="info-box-number">{{ $statistics['diesel_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-warning"><i class="fas fa-solar-panel"></i></span><div class="info-box-content"><span class="info-box-text">الطاقة الشمسية</span><span class="info-box-number">{{ $statistics['solar_energy_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-shield-virus"></i></span><div class="info-box-content"><span class="info-box-text">مضخات التعقيم</span><span class="info-box-number">{{ $statistics['disinfection_pumps_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-exchange-alt"></i></span><div class="info-box-content"><span class="info-box-text">المضخات الأفقية</span><span class="info-box-number">{{ $statistics['horizontal_pumps_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-dark"><i class="fas fa-box-open"></i></span><div class="info-box-content"><span class="info-box-text">الخزانات الأرضية</span><span class="info-box-number">{{ $statistics['ground_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-light text-dark"><i class="fas fa-archway"></i></span><div class="info-box-content"><span class="info-box-text">الخزانات العالية</span><span class="info-box-number">{{ $statistics['elevated_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-danger"><i class="fas fa-plug"></i></span><div class="info-box-content"><span class="info-box-text">محولات الكهرباء</span><span class="info-box-number">{{ $statistics['electricity_transformers_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-success"><i class="fas fa-vial"></i></span><div class="info-box-content"><span class="info-box-text">الانفلترات</span><span class="info-box-number">{{ $statistics['infiltrators_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-filter"></i></span><div class="info-box-content"><span class="info-box-text">المرشحات</span><span class="info-box-number">{{ $statistics['filters_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-secondary"><i class="fas fa-dungeon"></i></span><div class="info-box-content"><span class="info-box-text">المناهل</span><span class="info-box-number">{{ $statistics['manholes_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-file-contract"></i></span><div class="info-box-content"><span class="info-box-text">تراخيص الآبار</span><span class="info-box-number">{{ $statistics['well_licenses_count'] ?? 0 }}</span></div></div></div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary"><div class="card-header"><h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> توزيع مصادر الطاقة</h3></div><div class="card-body"><canvas id="energySourceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div>
                <div class="card card-info"><div class="card-header"><h3 class="card-title"><i class="fas fa-trophy mr-1"></i> أكثر المحطات امتلاكاً للآبار</h3></div><div class="card-body p-0"><ul class="products-list product-list-in-card pl-2 pr-2">@forelse($statistics['top_stations_by_wells'] as $station)<li class="item"><div class="product-info ml-2"><a href="{{ route('dashboard.stations.show', $station->id) }}" class="product-title">{{ $station->station_name }}<span class="badge badge-info float-right">{{ $station->wells_count }} بئر</span></a><span class="product-description">وحدة: {{ optional(optional($station->town)->unit)->unit_name }}</span></div></li>@empty<li class="item text-center p-3">لا توجد بيانات كافية للعرض.</li>@endforelse</ul></div></div>
            </div>

            <div class="col-lg-4">
                <div class="card card-warning"><div class="card-header"><h3 class="card-title"><i class="fas fa-wrench mr-1"></i> أبرز أسباب توقف الآبار</h3></div><div class="card-body">@forelse($statistics['top_well_stop_reasons'] as $reason)<div class="progress-group">{{ Str::limit($reason->stop_reason, 25) }}<span class="float-right"><b>{{ $reason->count }}</b></span></div>@empty<p class="text-center">لا توجد أسباب مسجلة حالياً.</p>@endforelse</div></div>
                <div class="card card-info"><div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> توزيع أنواع التراخيص</h3></div><div class="card-body"><canvas id="licenseTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div>
                <div class="card card-danger"><div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> تنبيهات الجاهزية المنخفضة</h3></div><div class="card-body p-0"><ul class="products-list product-list-in-card pl-2 pr-2">@forelse($statistics['low_readiness_diesel_tanks'] as $tank)<li class="item"><div class="product-info ml-2"><a href="{{ route('dashboard.diesel_tanks.edit', $tank->id) }}" class="product-title text-danger">جاهزية منخفضة لخزان: {{ $tank->tank_name }}<span class="badge badge-danger float-right">{{ $tank->readiness_percentage }}%</span></a><span class="product-description">محطة: {{ optional($tank->station)->station_name }}</span></div></li>@empty<li class="item text-center p-3 text-success"><i class="fas fa-check-circle"></i> لا توجد تنبيهات حالياً.</li>@endforelse</ul></div><div class="card-footer text-center"><a href="{{ route('dashboard.diesel_tanks.index') }}">عرض جميع خزانات الديزل</a></div></div>
            </div>
        </div>

        <div class="row">
            {{-- آخر النشاطات --}}
            <div class="col-12">
                <div class="card"><div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-1"></i> آخر النشاطات في النظام</h3></div><div class="card-body"><div class="timeline">@forelse($statistics['recent_activities'] as $activity)<div><i class="fas fa-user bg-blue"></i><div class="timeline-item"><span class="time"><i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}</span><h3 class="timeline-header"><a href="#">{{ optional($activity->causer)->name ?? 'نظام' }}</a></h3><div class="timeline-body">{{ $activity->description }} لـ ({{ class_basename($activity->subject_type) }})</div></div></div>@empty<div><i class="fas fa-info bg-gray"></i><div class="timeline-item"><div class="timeline-body">لا توجد نشاطات مسجلة.</div></div></div>@endforelse<div><i class="fas fa-clock bg-gray"></i></div></div></div></div>
            </div>
        </div>

    @else
        {{-- ================================================================= --}}
        {{-- محتوى لوحة التحكم الخاصة بمحطة (ELSE for !$selectedStation) --}}
        {{-- ================================================================= --}}
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-water"></i></span><div class="info-box-content"><span class="info-box-text">الآبار</span><span class="info-box-number">{{ $statistics['wells_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-secondary"><i class="fas fa-bolt"></i></span><div class="info-box-content"><span class="info-box-text">مجموعات التوليد</span><span class="info-box-number">{{ $statistics['generation_groups_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-exchange-alt"></i></span><div class="info-box-content"><span class="info-box-text">المضخات الأفقية</span><span class="info-box-number">{{ $statistics['horizontal_pumps_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-dark"><i class="fas fa-box-open"></i></span><div class="info-box-content"><span class="info-box-text">الخزانات الأرضية</span><span class="info-box-number">{{ $statistics['ground_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-light text-dark"><i class="fas fa-archway"></i></span><div class="info-box-content"><span class="info-box-text">الخزانات العالية</span><span class="info-box-number">{{ $statistics['elevated_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-network-wired"></i></span><div class="info-box-content"><span class="info-box-text">قطاعات الضخ</span><span class="info-box-number">{{ $statistics['pumping_sectors_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span><div class="info-box-content"><span class="info-box-text">ساعات الكهرباء</span><span class="info-box-number">{{ $statistics['electricity_hours_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-danger"><i class="fas fa-plug"></i></span><div class="info-box-content"><span class="info-box-text">محولات الكهرباء</span><span class="info-box-number">{{ $statistics['electricity_transformers_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-success"><i class="fas fa-vial"></i></span><div class="info-box-content"><span class="info-box-text">الانفلترات</span><span class="info-box-number">{{ $statistics['infiltrators_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-info"><i class="fas fa-filter"></i></span><div class="info-box-content"><span class="info-box-text">المرشحات</span><span class="info-box-number">{{ $statistics['filters_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-secondary"><i class="fas fa-dungeon"></i></span><div class="info-box-content"><span class="info-box-text">المناهل</span><span class="info-box-number">{{ $statistics['manholes_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-warning"><i class="fas fa-solar-panel"></i></span><div class="info-box-content"><span class="info-box-text">الطاقة الشمسية</span><span class="info-box-number">{{ $statistics['solar_energy_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-dark"><i class="fas fa-gas-pump"></i></span><div class="info-box-content"><span class="info-box-text">خزانات الديزل</span><span class="info-box-number">{{ $statistics['diesel_tanks_count'] ?? 0 }}</span></div></div></div>
            <div class="col-12 col-sm-6 col-md-3"><div class="info-box mb-3"><span class="info-box-icon bg-primary"><i class="fas fa-shield-virus"></i></span><div class="info-box-content"><span class="info-box-text">مضخات التعقيم</span><span class="info-box-number">{{ $statistics['disinfection_pumps_count'] ?? 0 }}</span></div></div></div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
    {{-- مكتبات JavaScript الأساسية --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- مكتبات الخريطة التفاعلية --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-measure@3.1.0/dist/leaflet-measure.js"></script>

    <script>
        // دوال مساعدة للتحكم في اللوحة الجانبية للخريطة
        function openSidebar(content) { document.getElementById('sidebar-content').innerHTML = content; document.getElementById('map-sidebar').classList.add('open'); }
        function closeSidebar() { document.getElementById('map-sidebar').classList.remove('open'); }

        $(function () {
            // تهيئة فلتر البحث عن المحطات
            $('.select2bs4').select2({ theme: 'bootstrap4', dir: 'rtl' });

            // تهيئة الخريطة
            if ($('#map').length) {
                var mapCenter = [36.1, 36.7];
                var map = L.map('map').setView(mapCenter, 9);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

                var geoJsonData = @json($geoJsonData);
                var allFeaturesGroup = L.featureGroup();
                let selectedLayer = null;

                function createCircleMarker(color, isSelected = false) { return { radius: isSelected ? 11 : 7, fillColor: color, color: isSelected ? '#ff3838' : '#000', weight: isSelected ? 3 : 1, opacity: 1, fillOpacity: 0.9 }; }

                Object.keys(geoJsonData).forEach(key => {
                    let data = geoJsonData[key];
                    if (data && data.features.length > 0) {
                        L.geoJSON(data, {
                            pointToLayer: (feature, latlng) => L.circleMarker(latlng, createCircleMarker(feature.properties.color)),
                            onEachFeature: (feature, layer) => {
                                allFeaturesGroup.addLayer(layer);
                                layer.on('click', function (e) {
                                    if (selectedLayer) { selectedLayer.setStyle(createCircleMarker(selectedLayer.feature.properties.color)); }
                                    layer.setStyle(createCircleMarker(feature.properties.color, true));
                                    selectedLayer = layer;
                                    let content = `<h5 class="border-bottom pb-2 mb-3">${feature.properties.name || 'تفاصيل العنصر'}</h5><ul class="list-unstyled"><li><strong>النوع:</strong> ${feature.properties.type}</li>${feature.properties.station_name ? `<li><strong>المحطة:</strong> ${feature.properties.station_name}</li>` : ''}<li><strong>الحالة:</strong> ${feature.properties.status}</li></ul><a href="${feature.properties.detail_url}" target="_blank" class="btn btn-primary btn-block mt-4">عرض التفاصيل الكاملة</a>`;
                                    openSidebar(content);
                                    L.DomEvent.stopPropagation(e);
                                });
                            }
                        }).addTo(map);
                    }
                });

                new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', localization: 'ar' }).addTo(map);
                map.on('click', function() { if (selectedLayer) { selectedLayer.setStyle(createCircleMarker(selectedLayer.feature.properties.color)); selectedLayer = null; } closeSidebar(); });
                if (allFeaturesGroup.getLayers().length > 0) { map.fitBounds(allFeaturesGroup.getBounds().pad(0.2)); }
            }

            // تهيئة المخططات البيانية (في الوضع العام فقط)
            @if(!$selectedStation)
                if ($('#stationStatusChart').length && @json($statistics['stations_by_status'] ?? null)) {
                    var pieData = { labels: @json(array_keys($statistics['stations_by_status']->toArray())), datasets: [{ data: @json(array_values($statistics['stations_by_status']->toArray())), backgroundColor: ['#28a745', '#dc3545', '#6c757d', '#ffc107'] }] };
                    new Chart($('#stationStatusChart').get(0).getContext('2d'), { type: 'doughnut', data: pieData, options: { maintainAspectRatio: false, responsive: true, legend: { position: 'bottom' }} });
                }

                if ($('#energySourceChart').length && @json($statistics['energy_source_distribution'] ?? null)) {
                    var barData = { labels: @json(array_keys($statistics['energy_source_distribution']->toArray())), datasets: [{ label: 'عدد المحطات', backgroundColor: 'rgba(60,141,188,0.9)', data: @json(array_values($statistics['energy_source_distribution']->toArray())) }] };
                    var barOptions = { responsive: true, maintainAspectRatio: false, scales: { yAxes: [{ ticks: { beginAtZero: true, callback: function(value) {if (Number.isInteger(value)) {return value;}} }}] }, legend: { display: false } };
                    new Chart($('#energySourceChart').get(0).getContext('2d'), { type: 'bar', data: barData, options: barOptions });
                }

                if ($('#licenseTypeChart').length && @json($statistics['licenses_by_type'] ?? null)) {
                    var pieDataLicenses = { labels: @json(array_keys($statistics['licenses_by_type']->toArray())), datasets: [{ data: @json(array_values($statistics['licenses_by_type']->toArray())), backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545'] }] };
                    new Chart($('#licenseTypeChart').get(0).getContext('2d'), { type: 'doughnut', data: pieDataLicenses, options: { maintainAspectRatio: false, responsive: true, legend: { position: 'bottom' }} });
                }

                // مخططات المشاريع (جديد)
                if(document.getElementById('projectsTrendChart') && @json($statistics['projects_yearly_trend'] ?? null)) {
                    var trendCtx = document.getElementById('projectsTrendChart').getContext('2d');
                    var trendData = @json($statistics['projects_yearly_trend'] ?? []);

                    if(trendData.length > 0) {
                         // قلب المصفوفة لعرض السنوات تصاعدياً
                        trendData.reverse();

                        var years = trendData.map(item => item.year);
                        var values = trendData.map(item => item.total_value);
                        var counts = trendData.map(item => item.count);

                        new Chart(trendCtx, {
                            type: 'line',
                            data: {
                                labels: years,
                                datasets: [
                                    {
                                        label: 'القيمة المالية ($)',
                                        data: values,
                                        borderColor: '#28a745',
                                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                        yAxisID: 'y-axis-1',
                                    },
                                    {
                                        label: 'عدد المشاريع',
                                        data: counts,
                                        borderColor: '#6610f2',
                                        backgroundColor: 'transparent',
                                        borderDash: [5, 5],
                                        yAxisID: 'y-axis-2',
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                scales: {
                                    'y-axis-1': { type: 'linear', display: true, position: 'left', title: {display: true, text: 'القيمة ($)'} },
                                    'y-axis-2': { type: 'linear', display: true, position: 'right', title: {display: true, text: 'العدد'}, grid: {drawOnChartArea: false} }
                                }
                            }
                        });
                    }
                }

                if(document.getElementById('projectStatusChart') && @json($statistics['projects_by_status'] ?? null)) {
                    var statusCtx = document.getElementById('projectStatusChart').getContext('2d');
                    var statusRawData = @json($statistics['projects_by_status'] ?? []);
                    if(statusRawData.length > 0) {
                        var statusLabels = statusRawData.map(item => item.general_status ? item.general_status.name : 'N/A');
                        var statusCounts = statusRawData.map(item => item.count);

                        new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: statusLabels,
                                datasets: [{
                                    data: statusCounts,
                                    backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545', '#6c757d', '#007bff'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { position: 'right' } }
                            }
                        });
                    }
                }
            @endif
        });
    </script>
@endpush
