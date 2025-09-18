@extends('layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--bootstrap4[dir="rtl"] .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
        }
        .info-box-icon {
            font-size: 2.5rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- قسم فلترة المحطات --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('dashboard.index') }}" method="GET" id="stationFilterForm">
                <div class="form-group">
                    <label for="station_id">اختر محطة لعرض إحصائياتها</label>
                    <select class="form-control select2bs4" id="station_id" name="station_id" onchange="this.form.submit()">
                        <option value="">عرض الإحصائيات العامة</option>
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

    {{-- عنوان الصفحة --}}
    <h3 class="mb-3 text-center">{{ $message }}</h3>
    <hr>

    {{-- كروت الإحصائيات --}}
    <div class="row">

        {{-- ========================================================== --}}
        {{-- كروت تظهر فقط في وضع الإحصائيات العامة --}}
        {{-- ========================================================== --}}
        @if(!$selectedStation)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-info">
                    <span class="info-box-icon"><i class="fas fa-industry"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">عدد المحطات</span>
                        <span class="info-box-number">{{ $statistics['stations_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-danger">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">عدد المستخدمين</span>
                        <span class="info-box-number">{{ $statistics['users_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-success">
                    <span class="info-box-icon"><i class="fas fa-building"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">عدد الوحدات</span>
                        <span class="info-box-number">{{ $statistics['units_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3 bg-warning">
                    <span class="info-box-icon"><i class="fas fa-city"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">عدد البلدات</span>
                        <span class="info-box-number">{{ $statistics['towns_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================================== --}}
        {{-- كروت مشتركة (تظهر في الوضع العام والمخصص للمحطة) --}}
        {{-- ========================================================== --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary"><i class="fas fa-water"></i></span>
                <div class="info-box-content"><span class="info-box-text">الآبار</span><span class="info-box-number">{{ $statistics['wells_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary"><i class="fas fa-bolt"></i></span>
                <div class="info-box-content"><span class="info-box-text">مجموعات التوليد</span><span class="info-box-number">{{ $statistics['generation_groups_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info"><i class="fas fa-exchange-alt"></i></span>
                <div class="info-box-content"><span class="info-box-text">المضخات الأفقية</span><span class="info-box-number">{{ $statistics['horizontal_pumps_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-dark"><i class="fas fa-box-open"></i></span>
                <div class="info-box-content"><span class="info-box-text">الخزانات الأرضية</span><span class="info-box-number">{{ $statistics['ground_tanks_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-light"><i class="fas fa-archway"></i></span>
                <div class="info-box-content"><span class="info-box-text">الخزانات العالية</span><span class="info-box-number">{{ $statistics['elevated_tanks_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary"><i class="fas fa-network-wired"></i></span>
                <div class="info-box-content"><span class="info-box-text">قطاعات الضخ</span><span class="info-box-number">{{ $statistics['pumping_sectors_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                <div class="info-box-content"><span class="info-box-text">ساعات الكهرباء</span><span class="info-box-number">{{ $statistics['electricity_hours_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger"><i class="fas fa-plug"></i></span>
                <div class="info-box-content"><span class="info-box-text">محولات الكهرباء</span><span class="info-box-number">{{ $statistics['electricity_transformers_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success"><i class="fas fa-vial"></i></span>
                <div class="info-box-content"><span class="info-box-text">الانفلترات</span><span class="info-box-number">{{ $statistics['infiltrators_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info"><i class="fas fa-filter"></i></span>
                <div class="info-box-content"><span class="info-box-text">المرشحات</span><span class="info-box-number">{{ $statistics['filters_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-secondary"><i class="fas fa-dungeon"></i></span>
                <div class="info-box-content"><span class="info-box-text">المناهل</span><span class="info-box-number">{{ $statistics['manholes_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning"><i class="fas fa-solar-panel"></i></span>
                <div class="info-box-content"><span class="info-box-text">الطاقة الشمسية</span><span class="info-box-number">{{ $statistics['solar_energy_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-dark"><i class="fas fa-gas-pump"></i></span>
                <div class="info-box-content"><span class="info-box-text">خزانات الديزل</span><span class="info-box-number">{{ $statistics['diesel_tanks_count'] ?? 0 }}</span></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary"><i class="fas fa-shield-virus"></i></span>
                <div class="info-box-content"><span class="info-box-text">مضخات التعقيم</span><span class="info-box-number">{{ $statistics['disinfection_pumps_count'] ?? 0 }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- تفعيل مكتبة Select2 --}}
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });
        });
    </script>
@endpush
