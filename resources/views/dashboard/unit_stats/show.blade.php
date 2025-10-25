@extends('layouts.app')

@section('title', 'تفاصيل السجل الإحصائي')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                تفاصيل السجل الإحصائي للوحدة: <strong>{{ $unitMonthlyStat->unit->unit_name }}</strong> - شهر: <strong>{{ $unitMonthlyStat->month }}/{{ $unitMonthlyStat->year }}</strong>
            </h3>
            <div class="card-tools">
                <a href="{{ route('dashboard.unit-stats.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> العودة للقائمة
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Section 1: Key Performance Indicators (KPIs) --}}
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">نسبة الهدر</span>
                            <span class="info-box-number">{{ number_format($unitMonthlyStat->water_loss_percentage, 2) }}<small>%</small></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-hand-holding-water"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">حصة الفرد الفعّال</span>
                            <span class="info-box-number">{{ number_format($unitMonthlyStat->per_capita_share, 2) }} <small>م³/مشترك</small></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cash-register"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">كفاءة الجباية</span>
                            <span class="info-box-number">{{ number_format($unitMonthlyStat->collection_efficiency_percentage, 2) }}<small>%</small></span>
                        </div>
                    </div>
                </div>
                 <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-industry"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">إجمالي الإنتاج</span>
                            <span class="info-box-number">{{ number_format($unitMonthlyStat->produced_water_m3) }} <small>م³</small></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Detailed Data in Tabs --}}
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-water">إحصائيات المياه</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-counts">أعداد المشتركين</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-segments">شرائح المشتركين</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-financials">البيانات المالية</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-notes">ملاحظات</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Water Stats Tab -->
                        <div class="tab-pane fade show active" id="tab-water" role="tabpanel">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr><th style="width: 200px;">إجمالي الإنتاج</th><td>{{ number_format($unitMonthlyStat->produced_water_m3) }} م³</td></tr>
                                    <tr><th>إجمالي الهدر</th><td>{{ number_format($unitMonthlyStat->lost_water_m3) }} م³</td></tr>
                                    <tr><th>المياه الموزعة للمستفيدين</th><td>{{ number_format($unitMonthlyStat->distributed_water_m3) }} م³</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Subscriber Counts Tab -->
                        <div class="tab-pane fade" id="tab-counts" role="tabpanel">
                             <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr><th style="width: 200px;">عدد المشتركين الكلي</th><td>{{ number_format($unitMonthlyStat->total_subscribers) }}</td></tr>
                                    <tr><th>عدد الفعالين</th><td>{{ number_format($unitMonthlyStat->active_subscribers) }}</td></tr>
                                    <tr><th>بجباية على العداد</th><td>{{ number_format($unitMonthlyStat->metered_subscribers) }}</td></tr>
                                    <tr><th>بجباية مقطوعة</th><td>{{ number_format($unitMonthlyStat->flat_rate_subscribers) }}</td></tr>
                                    <tr><th>عدد المغادرين</th><td>{{ number_format($unitMonthlyStat->departed_subscribers) }}</td></tr>
                                    <tr><th>الملغى خطوطهم</th><td>{{ number_format($unitMonthlyStat->canceled_subscribers) }}</td></tr>
                                    <tr><th>المقطوع خطوطهم</th><td>{{ number_format($unitMonthlyStat->disconnected_subscribers) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Subscriber Segments Tab -->
                        <div class="tab-pane fade" id="tab-segments" role="tabpanel">
                            <table class="table table-bordered table-striped">
                                <thead><tr><th>الشريحة</th><th>عدد المشتركين</th><th>عدد المتخلفين</th></tr></thead>
                                <tbody>
                                    <tr><td>بناء إسكان</td><td>{{ number_format($unitMonthlyStat->housing_project_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->housing_project_defaulters) }}</td></tr>
                                    <tr><td>أبنية حكومية</td><td>{{ number_format($unitMonthlyStat->gov_building_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->gov_building_defaulters) }}</td></tr>
                                    <tr><td>بناء ملكية</td><td>{{ number_format($unitMonthlyStat->owned_property_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->owned_property_defaulters) }}</td></tr>
                                    <tr><td>بناء مستأجر</td><td>{{ number_format($unitMonthlyStat->rented_property_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->rented_property_defaulters) }}</td></tr>
                                    <tr><td>اشتراك منزلي</td><td>{{ number_format($unitMonthlyStat->domestic_subscription_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->domestic_subscription_defaulters) }}</td></tr>
                                    <tr><td>اشتراك تجاري</td><td>{{ number_format($unitMonthlyStat->commercial_subscription_subscribers) }}</td><td>{{ number_format($unitMonthlyStat->commercial_subscription_defaulters) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Financials Tab -->
                        <div class="tab-pane fade" id="tab-financials" role="tabpanel">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr><th style="width: 200px;">عدد المسددين الإجمالي</th><td>{{ number_format($unitMonthlyStat->total_paid_count) }}</td></tr>
                                    <tr><th>قيمة التسديد</th><td>{{ number_format($unitMonthlyStat->total_paid_amount, 2) }}</td></tr>
                                    <tr><th>عدد المتخلفين الإجمالي</th><td>{{ number_format($unitMonthlyStat->total_defaulters_count) }}</td></tr>
                                    <tr><th>قيمة التخلف</th><td>{{ number_format($unitMonthlyStat->total_defaulters_amount, 2) }}</td></tr>
                                    <tr><th>عدد المعفيين</th><td>{{ number_format($unitMonthlyStat->exempted_count) }}</td></tr>
                                    <tr><th>القيمة المعفاة</th><td>{{ number_format($unitMonthlyStat->exempted_amount, 2) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Notes Tab -->
                        <div class="tab-pane fade" id="tab-notes" role="tabpanel">
                            <p>{{ $unitMonthlyStat->notes ?? 'لا توجد ملاحظات مسجلة لهذا الشهر.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
