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
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
        }
        .info-box-icon { font-size: 2.5rem; }
        .timeline>div>.timeline-item { margin-right: 60px; }

        /* **تنسيقات الخريطة الجديدة واللوحة الجانبية** */
        #map-container {
            position: relative;
            height: 550px; /* ارتفاع الحاوية الكلية للخريطة */
        }
        #map {
            height: 100%;
            width: 100%;
        }
        #map-sidebar {
            position: absolute;
            top: 10px;
            right: -350px; /* ابدأ مخفياً خارج الشاشة */
            width: 330px;
            height: calc(100% - 20px);
            background: rgba(255, 255, 255, 0.95);
            z-index: 1000; /* للتأكد من أنها فوق الخريطة */
            transition: right 0.3s ease-in-out;
            border-radius: 5px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            overflow-y: auto;
        }
        #map-sidebar.open {
            right: 10px; /* إظهاره عند إضافة كلاس 'open' */
        }
        .sidebar-close {
            position: absolute;
            top: 5px;
            left: 10px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #555;
        }
        .sidebar-close:hover {
            color: #000;
        }
        .powerbi-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding-top: 62.25%; /* Aspect Ratio: (373.5 / 600) * 100 */
        }

        .powerbi-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
        .powerbi-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f6f9; /* لون خلفية قريب من تصميم المنصة */
            color: #6c757d;
            font-size: 1.1rem;
            border: 1px dashed #ddd;
            border-radius: .25rem;
            z-index: 1; /* للتأكد من أنه يظهر فوق الـ iframe الفارغ */
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- ========================================================== --}}
    {{-- القسم العلوي: العنوان وفلتر المحطات --}}
    {{-- ========================================================== --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0">{{ $message }}</h3>
        </div>
        <div class="col-md-6">
            <form action="{{ route('dashboard.index') }}" method="GET" id="stationFilterForm">
                <div class="form-group mb-0">
                    <select class="form-control select2bs4" name="station_id" onchange="this.form.submit()">
                        <option value="">-- عرض الإحصائيات العامة للنظام --</option>
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
      <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        التقرير التفاعلي (Power BI)
                    </h3>
                </div>
                <div class="card-body">
                    {{-- حاوية لجعل الـ iframe متجاوب --}}
                 <div class="powerbi-container" id="powerbi-wrapper">
    {{-- 1. عنصر نائب يظهر قبل تحميل التقرير --}}
    <div class="powerbi-placeholder">
        <i class="fas fa-spinner fa-spin mr-2"></i>
        <span>جاري تحميل التقرير التفاعلي...</span>
    </div>

    {{-- 2. الـ iframe بدون src، ولكن مع data-src --}}
    <iframe id="powerbi-frame" title="Water_Station_2025_Phase1"
            data-src="https://app.powerbi.com/view?r=eyJrIjoiNDI5NTlhNmQtOTA1Zi00OTA2LThmNmMtYjgyM2ZjODU4N2FiIiwidCI6ImU5ZTdmYjA0LWYzZTAtNDZjMC1hNjZlLTBiZTAxNzljOWFiMiIsImMiOjl9"
            frameborder="0" allowFullScreen="true"></iframe>
</div>
                </div>
            </div>
        </div>
    </div>
    {{-- ================================================================= --}}
    {{-- عرض الخريطة (مشترك بين الوضع العام ووضع المحطة) --}}
    {{-- ================================================================= --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title mb-0"><i class="fas fa-map-marked-alt mr-1"></i> الخريطة التفاعلية</h3>
                </div>
                <div class="card-body p-0">
                    <div id="map-container">
                        <div id="map"></div>
                        <div id="map-sidebar">
                            <span class="sidebar-close" onclick="closeSidebar()">&times;</span>
                            <div class="p-3" id="sidebar-content">
                                {{-- سيتم تعبئة هذا القسم عبر JavaScript --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- في الوضع العام، نعرض مخطط حالة المحطات بجانب الخريطة --}}
        @if(!$selectedStation)
        <div class="col-lg-4">
            <div class="card card-success h-100">
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
        {{-- عرض محتوى لوحة التحكم العامة (الخارقة) --}}
        {{-- ================================================================= --}}
        <hr>
        {{-- مؤشرات الأداء الرئيسية (KPIs) --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-users"></i></span><div class="info-box-content"><span class="info-box-text">العائلات المستفيدة</span><span class="info-box-number">{{ number_format($statistics['beneficiary_families'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-database"></i></span><div class="info-box-content"><span class="info-box-text">سعة التخزين (م³)</span><span class="info-box-number">{{ number_format($statistics['total_water_storage_m3'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-bolt"></i></span><div class="info-box-content"><span class="info-box-text">القدرة التوليدية (KVA)</span><span class="info-box-number">{{ number_format($statistics['total_generation_capacity_kva'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-danger"><i class="fas fa-tools"></i></span><div class="info-box-content"><span class="info-box-text">صيانة قيد التنفيذ</span><span class="info-box-number">{{ $statistics['maintenance_in_progress'] ?? 0 }}</span></div></div></div>
        </div>

        <h4 class="mb-3 mt-4">جرد مكونات النظام</h4>
        <div class="row">
            {{-- بطاقات الجرد --}}
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
        </div>
        <hr>
        <div class="row">
            {{-- العمود الأيمن (8 أعمدة) للمخططات والقوائم الرئيسية --}}
            <div class="col-lg-8">
                <div class="card card-primary"><div class="card-header"><h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> توزيع مصادر الطاقة</h3></div><div class="card-body"><canvas id="energySourceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div>
                <div class="card card-info"><div class="card-header"><h3 class="card-title"><i class="fas fa-trophy mr-1"></i> أكثر المحطات امتلاكاً للآبار</h3></div><div class="card-body p-0"><ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($statistics['top_stations_by_wells'] as $station)<li class="item"><div class="product-info ml-2"><a href="{{ route('dashboard.stations.show', $station->id) }}" class="product-title">{{ $station->station_name }}</a><span class="badge badge-info float-right">{{ $station->wells_count }} بئر</span><span class="product-description">وحدة: {{ $station->town->unit->unit_name ?? 'غير محدد' }}</span></div></li>
                    @empty<li class="item text-center p-3">لا توجد بيانات كافية للعرض.</li>@endforelse
                </ul></div></div>
            </div>

            {{-- العمود الأيسر (4 أعمدة) للملخصات --}}
            <div class="col-lg-4">
                <div class="card card-warning"><div class="card-header"><h3 class="card-title"><i class="fas fa-wrench mr-1"></i> أبرز أسباب توقف الآبار</h3></div><div class="card-body">
                    @forelse($statistics['top_well_stop_reasons'] as $reason)<div class="progress-group">{{ Str::limit($reason->stop_reason, 25) }}<span class="float-right"><b>{{ $reason->count }}</b></span></div>
                    @empty <p class="text-center">لا توجد أسباب مسجلة حالياً.</p> @endforelse
                </div></div>
            </div>
        </div>

        <div class="row">
            {{-- تنبيهات الجاهزية --}}
            <div class="col-md-6">
                <div class="card card-danger"><div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> تنبيهات الجاهزية المنخفضة</h3></div><div class="card-body p-0"><ul class="products-list product-list-in-card pl-2 pr-2">
                    @forelse($statistics['low_readiness_diesel_tanks'] as $tank)<li class="item"><div class="product-info ml-2"><a href="{{ route('dashboard.diesel_tanks.edit', $tank->id) }}" class="product-title text-danger">جاهزية منخفضة لخزان: {{ $tank->tank_name }}<span class="badge badge-danger float-right">{{ $tank->readiness_percentage }}%</span></a><span class="product-description">محطة: {{ $tank->station->station_name ?? 'غير محددة' }}</span></div></li>
                    @empty<li class="item text-center p-3 text-success"><i class="fas fa-check-circle"></i> لا توجد تنبيهات حالياً.</li>@endforelse
                </ul></div><div class="card-footer text-center"><a href="{{ route('dashboard.diesel_tanks.index') }}">عرض جميع خزانات الديزل</a></div></div>
            </div>
            {{-- آخر النشاطات --}}
            <div class="col-md-6">
                <div class="card"><div class="card-header"><h3 class="card-title"><i class="fas fa-history mr-1"></i> آخر النشاطات في النظام</h3></div><div class="card-body"><div class="timeline">
                    @forelse($statistics['recent_activities'] as $activity)
                    <div><i class="fas fa-user bg-blue"></i><div class="timeline-item"><span class="time"><i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}</span><h3 class="timeline-header"><a href="#">{{ $activity->causer->name ?? 'نظام' }}</a></h3><div class="timeline-body">{{ $activity->description }} لـ ({{ class_basename($activity->subject_type) }})</div></div></div>
                    @empty <div><i class="fas fa-info bg-gray"></i><div class="timeline-item"><div class="timeline-body">لا توجد نشاطات مسجلة.</div></div></div> @endforelse
                    <div><i class="fas fa-clock bg-gray"></i></div>
                </div></div></div>
            </div>
        </div>

    @else
        {{-- ================================================================= --}}
        {{-- عرض لوحة التحكم الخاصة بمحطة --}}
        {{-- ================================================================= --}}
        <div class="row">
            {{-- بطاقات الجرد الخاصة بالمحطة --}}
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
        // دوال مساعدة عامة للتحكم في اللوحة الجانبية للخريطة
        function openSidebar(content) {
            document.getElementById('sidebar-content').innerHTML = content;
            document.getElementById('map-sidebar').classList.add('open');
        }
        function closeSidebar() {
            document.getElementById('map-sidebar').classList.remove('open');
        }

        // دالة يتم تنفيذها عند تحميل الصفحة بالكامل
        $(function () {

            // ===============================================
            // 1. تهيئة فلتر البحث عن المحطات
            // ===============================================
            $('.select2bs4').select2({ theme: 'bootstrap4', dir: 'rtl' });


            // ===============================================
            // 2. تهيئة الخريطة التفاعلية
            // ===============================================

            // التحقق من وجود عنصر الخريطة في الصفحة
            if ($('#map').length) {
                var mapCenter = [36.1, 36.7]; // مركز تقريبي للمنطقة (إدلب/حلب)
                var map = L.map('map').setView(mapCenter, 9);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var geoJsonData = @json($geoJsonData);
                var layers = {};
                var allFeaturesGroup = L.featureGroup();
                let selectedLayer = null; // لتتبع العنصر المحدد حاليًا

                // دالة مساعدة لإنشاء أيقونات دائرية ملونة (مع حالة التحديد)
                function createCircleMarker(color, isSelected = false) {
                    return {
                        radius: isSelected ? 11 : 7,
                        fillColor: color,
                        color: isSelected ? '#ff3838' : '#000',
                        weight: isSelected ? 3 : 1,
                        opacity: 1,
                        fillOpacity: 0.9
                    };
                }

                // دالة مساعدة لإنشاء طبقة GeoJSON
                function createGeoJsonLayer(key, data) {
                    if (data && data.features.length > 0) {
                        layers[key] = L.geoJSON(data, {
                            pointToLayer: (feature, latlng) => L.circleMarker(latlng, createCircleMarker(feature.properties.color)),
                            onEachFeature: (feature, layer) => {
                                allFeaturesGroup.addLayer(layer);
                                layer.on('click', function (e) {
                                    if (selectedLayer) {
                                        selectedLayer.setStyle(createCircleMarker(selectedLayer.feature.properties.color));
                                    }
                                    layer.setStyle(createCircleMarker(feature.properties.color, true));
                                    selectedLayer = layer;

                                    let content = `
                                        <h5 class="border-bottom pb-2 mb-3">${feature.properties.name || 'تفاصيل العنصر'}</h5>
                                        <ul class="list-unstyled">
                                            <li><strong>النوع:</strong> ${feature.properties.type}</li>
                                            ${feature.properties.station_name ? `<li><strong>المحطة:</strong> ${feature.properties.station_name}</li>` : ''}
                                            <li><strong>الحالة:</strong> ${feature.properties.status}</li>
                                        </ul>
                                        <a href="${feature.properties.detail_url}" target="_blank" class="btn btn-primary btn-block mt-4">عرض التفاصيل الكاملة</a>
                                    `;
                                    openSidebar(content);
                                    L.DomEvent.stopPropagation(e);
                                });
                            }
                        }).addTo(map);
                    }
                }

                // إنشاء طبقات البيانات
                createGeoJsonLayer('stations', geoJsonData.stations);
                createGeoJsonLayer('wells', geoJsonData.wells);
                createGeoJsonLayer('solar_energies', geoJsonData.solar_energies);
                createGeoJsonLayer('ground_tanks', geoJsonData.ground_tanks);
                createGeoJsonLayer('elevated_tanks', geoJsonData.elevated_tanks);

                // إنشاء أداة التحكم بالفلاتر ووضعها على الخريطة
                var filterControl = L.control({position: 'topleft'});
                filterControl.onAdd = function (map) {
                    var div = L.DomUtil.create('div', 'leaflet-bar');
                    div.innerHTML = `
                        <div class="btn-group-vertical btn-group-toggle" data-toggle="buttons" style="background-color: white; border-radius: 4px;">
                            <label class="btn btn-light btn-sm active" title="المحطات"><input type="checkbox" name="layer-toggle" value="stations" checked autocomplete="off"><i class="fas fa-industry"></i></label>
                            <label class="btn btn-light btn-sm active" title="الآبار"><input type="checkbox" name="layer-toggle" value="wells" checked autocomplete="off"><i class="fas fa-water"></i></label>
                            <label class="btn btn-light btn-sm active" title="الطاقة الشمسية"><input type="checkbox" name="layer-toggle" value="solar_energies" checked autocomplete="off"><i class="fas fa-solar-panel"></i></label>
                            <label class="btn btn-light btn-sm active" title="خزانات أرضية"><input type="checkbox" name="layer-toggle" value="ground_tanks" checked autocomplete="off"><i class="fas fa-box-open"></i></label>
                            <label class="btn btn-light btn-sm active" title="خزانات عالية"><input type="checkbox" name="layer-toggle" value="elevated_tanks" checked autocomplete="off"><i class="fas fa-archway"></i></label>
                        </div>
                    `;
                    L.DomEvent.disableClickPropagation(div); // منع النقر من الوصول للخريطة
                    return div;
                };
                filterControl.addTo(map);

                // ربط أزرار الفلاتر بالطبقات
                $('input[name="layer-toggle"]').on('change', function() {
                    var layerKey = $(this).val();
                    if (layers[layerKey]) {
                        if (this.checked) {
                            map.addLayer(layers[layerKey]);
                        } else {
                            map.removeLayer(layers[layerKey]);
                        }
                    }
                });

                // إخفاء أزرار الفلاتر للطبقات الفارغة
                $('.btn-group-vertical .btn').each(function() {
                    var layerKey = $(this).find('input').val();
                    if (!layers[layerKey] || layers[layerKey].getLayers().length === 0) {
                        $(this).hide();
                    }
                });

                // إضافة أداة القياس
                var measureControl = new L.Control.Measure({ position: 'topright', primaryLengthUnit: 'meters', secondaryLengthUnit: 'kilometers', primaryAreaUnit: 'sqmeters', activeColor: '#db4a39', completedColor: '#9b2d20', localization: 'ar' });
                measureControl.addTo(map);

                // إغلاق اللوحة الجانبية عند النقر على الخريطة وإلغاء تحديد العنصر
                map.on('click', function() {
                    if (selectedLayer) {
                        selectedLayer.setStyle(createCircleMarker(selectedLayer.feature.properties.color));
                        selectedLayer = null;
                    }
                    closeSidebar();
                });

                // تكبير الخريطة تلقائيًا لتناسب جميع النقاط
                if (allFeaturesGroup.getLayers().length > 0) {
                    map.fitBounds(allFeaturesGroup.getBounds().pad(0.2));
                }
            }

            // ===============================================
            // 3. تهيئة المخططات البيانية (تعمل فقط في الوضع العام)
            // ===============================================
            @if(!$selectedStation)
                if ($('#stationStatusChart').length && @json($statistics['stations_by_status'] ?? null)) {
                    var pieData = {
                        labels: @json(array_keys($statistics['stations_by_status']->toArray())),
                        datasets: [{
                            data: @json(array_values($statistics['stations_by_status']->toArray())),
                            backgroundColor: ['#28a745', '#dc3545', '#6c757d', '#ffc107'], // عاملة, متوقفة, خارج الخدمة, ...
                        }]
                    };
                    new Chart($('#stationStatusChart').get(0).getContext('2d'), {
                        type: 'doughnut',
                        data: pieData,
                        options: { maintainAspectRatio: false, responsive: true, legend: { position: 'bottom' }}
                    });
                }

                if ($('#energySourceChart').length && @json($statistics['energy_source_distribution'] ?? null)) {
                    var barData = {
                        labels: @json(array_keys($statistics['energy_source_distribution']->toArray())),
                        datasets: [{
                            label: 'عدد المحطات',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            borderWidth: 1,
                            data: @json(array_values($statistics['energy_source_distribution']->toArray()))
                        }]
                    };
                    var barOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{ ticks: { beginAtZero: true, callback: function(value) {if (value % 1 === 0) {return value;}} }}],
                            xAxes: [{ ticks: { autoSkip: false } }]
                        },
                        legend: { display: false }
                    };
                    new Chart($('#energySourceChart').get(0).getContext('2d'), { type: 'bar', data: barData, options: barOptions});
                }
            @endif

        });
        document.addEventListener('DOMContentLoaded', function() {
            const powerBiWrapper = document.getElementById('powerbi-wrapper');
            const powerBiFrame = document.getElementById('powerbi-frame');

            // التأكد من وجود العنصر وأن المتصفح يدعم IntersectionObserver
            if (powerBiWrapper && powerBiFrame && 'IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        // إذا أصبح العنصر مرئياً في الشاشة
                        if (entry.isIntersecting) {
                            // 1. خذ الرابط من data-src وضعه في src لبدء التحميل
                            powerBiFrame.src = powerBiFrame.dataset.src;

                            // 2. عند اكتمال تحميل التقرير، قم بإخفاء العنصر النائب
                            powerBiFrame.onload = () => {
                                const placeholder = powerBiWrapper.querySelector('.powerbi-placeholder');
                                if (placeholder) {
                                    placeholder.style.display = 'none';
                                }
                            };

                            // 3. أوقف المراقبة لأننا لم نعد بحاجة إليها
                            observer.unobserve(powerBiWrapper);
                        }
                    });
                }, { rootMargin: '100px' }); // ابدأ التحميل 100 بكسل قبل أن يظهر العنصر بالكامل

                // ابدأ بمراقبة حاوية التقرير
                observer.observe(powerBiWrapper);
            } else if (powerBiFrame) {
                // حل بديل للمتصفحات القديمة جداً: قم بتحميله مباشرة
                powerBiFrame.src = powerBiFrame.dataset.src;
            }
        });
    </script>
@endpush
