@extends('layouts.app')
@section('title', 'تفاصيل المنهل: ' . $manhole->manhole_name)

@push('styles')
    {{-- أنماط التصميم الموحد --}}
    <style>
        .widget-user .widget-user-header {
            height: 200px;
            background-size: cover;
            background-position: center center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff !important;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        }

        .widget-user .widget-user-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .widget-user .widget-user-username,
        .widget-user .widget-user-desc {
            position: relative;
            z-index: 1;
        }

        .widget-user .widget-user-image {
            position: absolute;
            top: 150px;
            left: 50%;
            margin-left: -50px;
        }

        .widget-user .widget-user-image>img,
        .widget-user .widget-user-image>.icon-circle {
            width: 100px;
            height: 100px;
            border: 3px solid #fff;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .widget-user .widget-user-image>.icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            color: #fff;
        }

        .card-footer {
            padding-top: 60px;
        }

        .description-block {
            margin-bottom: 1.5rem;
            text-align: center;
            padding: 0 10px;
        }

        .description-text {
            display: block;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .description-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #343a40;
            display: block;
        }

        .section-divider {
            border-top: 1px solid #dee2e6;
            margin: 2rem 0;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $manhole->manhole_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manholes.index') }}">المناهل</a></li>
                    <li class="breadcrumb-item active">{{ $manhole->manhole_name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user shadow-lg rounded">
                    <div class="widget-user-header" style="background-image: url('{{ asset('dist/img/photo2.png') }}');">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                                {{ $manhole->manhole_name }}</h3>
                            <h5 class="widget-user-desc mt-2">
                                محطة: {{ $manhole->station->station_name ?? 'N/A' }} | وحدة:
                                {{ $manhole->unit->unit_name ?? 'N/A' }}
                            </h5>
                        </div>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2 bg-secondary"><i class="fas fa-dolly-flatbed"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الحالة</span>
                                    <h5 class="description-header">{{ $manhole->status ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">يوجد عداد غزارة؟</span>
                                    <h5 class="description-header">{{ $manhole->has_flow_meter ? 'نعم' : 'لا' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">يوجد خزان تجميعي؟</span>
                                    <h5 class="description-header">{{ $manhole->has_storage_tank ? 'نعم' : 'لا' }}</h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        {{-- تفاصيل العداد والخزان --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-barcode text-muted fa-2x mb-2"></i><span
                                        class="description-text">رقم الشاسيه</span>
                                    <h5 class="description-header">{{ $manhole->chassis_number ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-circle-notch text-info fa-2x mb-2"></i><span
                                        class="description-text">قطر العداد</span>
                                    <h5 class="description-header">{{ $manhole->meter_diameter ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-tachometer-alt text-success fa-2x mb-2"></i><span
                                        class="description-text">حالة العداد</span>
                                    <h5 class="description-header">{{ $manhole->meter_status ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-database text-primary fa-2x mb-2"></i><span
                                        class="description-text">سعة الخزان</span>
                                    <h5 class="description-header">{{ $manhole->tank_capacity ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>

                        {{-- قسم الملاحظات وسبب التوقف (يظهر بشكل شرطي) --}}
                        @if ($manhole->stop_reason || $manhole->general_notes)
                            <hr class="section-divider">
                            <div class="row d-flex justify-content-center">
                                @if ($manhole->stop_reason)
                                    <div class="col-md-6">
                                        <div class="description-block"><i
                                                class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><span
                                                class="description-text">سبب التوقف</span>
                                            <h5 class="description-header"
                                                style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                                {{ $manhole->stop_reason }}</h5>
                                        </div>
                                    </div>
                                @endif
                                @if ($manhole->general_notes)
                                    <div class="col-md-6">
                                        <div class="description-block">
                                            <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                            <span class="description-text">ملاحظات عامة</span>
                                            <h5 class="description-header"
                                                style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                                {{ $manhole->general_notes }}</h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div> {{-- نهاية card-footer --}}
                </div> {{-- نهاية card --}}
            </div> {{-- نهاية col-md-12 --}}
        </div> {{-- نهاية row --}}

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('manholes.edit', $manhole->id) }}" class="btn btn-lg btn-warning"><i
                        class="fas fa-edit ml-1"></i> تعديل</a>
                <a href="{{ route('manholes.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection
