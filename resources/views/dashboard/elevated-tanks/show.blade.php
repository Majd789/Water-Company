@extends('layouts.app')
@section('title', 'تفاصيل الخزان: ' . $elevatedTank->tank_name)

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

        .description-header a {
            color: #007bff;
            text-decoration: none;
        }

        .description-header a:hover {
            text-decoration: underline;
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
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $elevatedTank->tank_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.elevated-tanks.index') }}">الخزانات العالية</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $elevatedTank->tank_name }}</li>
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
                    <div class="widget-user-header" style="background-image: url('{{ asset('dist/img/photo6.jpg') }}');">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                                {{ $elevatedTank->tank_name ?? 'خزان غير مسمى' }}</h3>
                            <h5 class="widget-user-desc mt-2">البلدة المستفيدة:
                                {{ $elevatedTank->town_supply ?? 'غير محدد' }}
                            </h5>
                        </div>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2 bg-primary"><i class="fas fa-tower"></i></div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">سعة الخزان</span>
                                    <h5 class="description-header">{{ $elevatedTank->capacity ?? '0' }} <small>م³</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">نسبة الجاهزية</span>
                                    <h5 class="description-header">{{ $elevatedTank->readiness_percentage ?? '0' }}%</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">ارتفاع الخزان</span>
                                    <h5 class="description-header">{{ $elevatedTank->height ?? '0' }} <small>م</small>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- التفاصيل الفنية --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-hard-hat text-warning fa-2x mb-2"></i><span
                                        class="description-text">الجهة المنشئة</span>
                                    <h5 class="description-header">{{ $elevatedTank->building_entity ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-calendar-alt text-info fa-2x mb-2"></i><span
                                        class="description-text">تاريخ البناء</span>
                                    <h5 class="description-header">{{ $elevatedTank->construction_date ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas-fa-shapes text-secondary fa-2x mb-2"></i><span
                                        class="description-text">شكل الخزان</span>
                                    <h5 class="description-header">{{ $elevatedTank->tank_shape ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-broadcast-tower text-primary fa-2x mb-2"></i><span
                                        class="description-text">المحطة المغذية</span>
                                    <h5 class="description-header">{{ $elevatedTank->feeding_station ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- تفاصيل الأنابيب والموقع --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-arrow-down text-info fa-2x mb-2"></i><span
                                        class="description-text">قطر بوري الدخول</span>
                                    <h5 class="description-header">{{ $elevatedTank->in_pipe_diameter ?? '0' }}
                                        <small>مم</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-arrow-up text-success fa-2x mb-2"></i><span
                                        class="description-text">قطر بوري الخروج</span>
                                    <h5 class="description-header">{{ $elevatedTank->out_pipe_diameter ?? '0' }}
                                        <small>مم</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-mountain text-success fa-2x mb-2"></i><span
                                        class="description-text">الارتفاع عن سطح البحر</span>
                                    <h5 class="description-header">{{ $elevatedTank->altitude ?? '0' }} <small>م</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-map-marked-alt text-danger fa-2x mb-2"></i><span
                                        class="description-text">الإحداثيات</span>
                                    <h5 class="description-header">
                                        @if ($elevatedTank->latitude && $elevatedTank->longitude)
                                            <a href="https://www.google.com/maps?q={{ $elevatedTank->latitude }},{{ $elevatedTank->longitude }}"
                                                target="_blank">
                                                عرض على الخريطة
                                            </a>
                                        @else
                                            غير محدد
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>


                        {{-- قسم الملاحظات (يظهر بشكل شرطي) --}}
                        @if ($elevatedTank->notes)
                            <hr class="section-divider">
                            <div class="row">
                                <div class="col-12">
                                    <div class="description-block">
                                        <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                        <span class="description-text">ملاحظات عامة</span>
                                        <h5 class="description-header"
                                            style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                            {{ $elevatedTank->notes }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div> {{-- نهاية card-footer --}}
                </div> {{-- نهاية card --}}
            </div> {{-- نهاية col-md-12 --}}
        </div> {{-- نهاية row --}}

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('dashboard.elevated-tanks.edit', $elevatedTank->id) }}" class="btn btn-lg btn-warning"><i
                        class="fas fa-edit ml-1"></i> تعديل</a>
                <a href="{{ route('dashboard.elevated-tanks.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection
