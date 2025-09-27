@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@push('styles')
    {{-- مكتبات التصميم --}}
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    {{-- تنسيقات مخصصة للصفحة --}}
    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
        }
        .info-box-icon {
            font-size: 2.5rem;
        }
        .timeline>div>.timeline-item {
            margin-right: 60px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- ========================================================== --}}
    {{-- القسم العلوي: العنوان وفلتر المحطات (مشترك) --}}
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

    @if(!$selectedStation)
        {{-- ================================================================= --}}
        {{-- عرض لوحة التحكم العامة (الخارقة) --}}
        {{-- ================================================================= --}}

        {{-- مؤشرات الأداء الرئيسية (KPIs) --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-users"></i></span><div class="info-box-content"><span class="info-box-text">العائلات المستفيدة</span><span class="info-box-number">{{ number_format($statistics['beneficiary_families'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-database"></i></span><div class="info-box-content"><span class="info-box-text">سعة التخزين (م³)</span><span class="info-box-number">{{ number_format($statistics['total_water_storage_m3'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-bolt"></i></span><div class="info-box-content"><span class="info-box-text">القدرة التوليدية للمولدات (KVA)</span><span class="info-box-number">{{ number_format($statistics['total_generation_capacity_kva'] ?? 0) }}</span></div></div></div>
            <div class="col-md-3 col-sm-6 col-12"><div class="info-box"><span class="info-box-icon bg-danger"><i class="fas fa-tools"></i></span><div class="info-box-content"><span class="info-box-text">صيانة قيد التنفيذ</span><span class="info-box-number">{{ $statistics['maintenance_in_progress'] ?? 0 }}</span></div></div></div>
        </div>
        <h4 class="mb-3">جرد مكونات النظام</h4>
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
                <div class="card card-success"><div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> حالة المحطات</h3></div><div class="card-body"><canvas id="stationStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas></div></div>
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
        {{-- عرض لوحة التحكم الخاصة بمحطة (القائمة الكاملة) --}}
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
    {{-- مكتبات JavaScript --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function () {
            // تفعيل فلتر البحث
            $('.select2bs4').select2({ theme: 'bootstrap4', dir: 'rtl' });

            @if(!$selectedStation)
            // ===============================================
            // تهيئة المخططات البيانية (تعمل فقط في الوضع العام)
            // ===============================================

            // 1. مخطط حالة المحطات (دائري)
            var pieData = {
                labels: @json(array_keys($statistics['stations_by_status']->toArray())),
                datasets: [{
                    data: @json(array_values($statistics['stations_by_status']->toArray())),
                    backgroundColor: ['#28a745', '#dc3545', '#6c757d', '#ffc107'], // عاملة, متوقفة, خارج الخدمة, ...
                }]
            };
            new Chart($('#stationStatusChart').get(0).getContext('2d'), { type: 'doughnut', data: pieData, options: {maintainAspectRatio: false, responsive: true, legend: {position: 'bottom'}} });

            // 2. مخطط مصادر الطاقة (أعمدة)
            var barData = {
                labels: @json(array_keys($statistics['energy_source_distribution']->toArray())),
                datasets: [{
                    label: 'عدد المحطات',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    data: @json(array_values($statistics['energy_source_distribution']->toArray()))
                }]
            };
            new Chart($('#energySourceChart').get(0).getContext('2d'), { type: 'bar', data: barData, options: {responsive: true, maintainAspectRatio: false, scales: {yAxes: [{ticks: {beginAtZero: true, stepSize: 1}}]}}});
            @endif
        });
    </script>
@endpush
