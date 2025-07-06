@extends('layouts.app')
@section('title', 'تفاصيل البئر: ' . $well->well_name)

@push('styles')
    {{-- تم دمج الأنماط هنا لتكون قابلة لإعادة الاستخدام بسهولة --}}
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
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $well->well_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wells.index') }}">الآبار</a></li>
                    <li class="breadcrumb-item active">{{ $well->well_name }}</li>
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
                                {{ $well->well_name }}</h3>
                            <h5 class="widget-user-desc mt-2">تابع لمحطة: {{ $well->station->station_name ?? 'غير محدد' }}
                            </h5>
                        </div>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2 bg-info"><i class="fas fa-water"></i></div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الحالة التشغيلية</span>
                                    <h5 class="description-header">{{ $well->well_status }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">غزارة البئر</span>
                                    <h5 class="description-header">{{ $well->well_flow ?? '0' }} <small>م³/س</small></h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">تدفق المضخة الفعلي</span>
                                    <h5 class="description-header">{{ $well->actual_pump_flow ?? '0' }} <small>م³/س</small>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- بيانات الحفر --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-ruler-vertical text-info fa-2x mb-2"></i><span
                                        class="description-text">العمق الستاتيكي</span>
                                    <h5 class="description-header">{{ $well->static_depth ?? '0' }} <small>م</small></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-ruler-horizontal text-info fa-2x mb-2"></i><span
                                        class="description-text">العمق الديناميكي</span>
                                    <h5 class="description-header">{{ $well->dynamic_depth ?? '0' }} <small>م</small></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-arrow-down text-secondary fa-2x mb-2"></i><span
                                        class="description-text">عمق الحفر</span>
                                    <h5 class="description-header">{{ $well->drilling_depth ?? '0' }} <small>م</small></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-circle-notch text-muted fa-2x mb-2"></i><span
                                        class="description-text">قطر البئر</span>
                                    <h5 class="description-header">{{ $well->well_diameter ?? '0' }} <small>انش</small>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- بيانات المضخة --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-download text-success fa-2x mb-2"></i><span
                                        class="description-text">عمق تركيب المضخة</span>
                                    <h5 class="description-header">{{ $well->pump_installation_depth ?? '0' }}
                                        <small>م</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-horse-head text-primary fa-2x mb-2"></i><span
                                        class="description-text">استطاعة المضخة</span>
                                    <h5 class="description-header">{{ $well->pump_capacity ?? '0' }} <small>حصان</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-upload text-success fa-2x mb-2"></i><span
                                        class="description-text">رفع المضخة</span>
                                    <h5 class="description-header">{{ $well->pump_lifting ?? '0' }} <small>م</small></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-industry text-muted fa-2x mb-2"></i><span
                                        class="description-text">ماركة وموديل المضخة</span>
                                    <h5 class="description-header">{{ $well->pump_brand_model ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- بيانات الموقع ومعلومات إضافية --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-bolt text-warning fa-2x mb-2"></i><span
                                        class="description-text">مصدر الطاقة</span>
                                    <h5 class="description-header">{{ $well->energy_source ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-route text-info fa-2x mb-2"></i><span
                                        class="description-text">البعد عن المحطة</span>
                                    <h5 class="description-header">{{ $well->distance_from_station ?? '0' }}
                                        <small>م</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-map-marked-alt text-danger fa-2x mb-2"></i><span
                                        class="description-text">الموقع</span>
                                    <h5 class="description-header">{{ $well->well_location ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            @if ($well->well_status != 'يعمل' && $well->stop_reason)
                                <div class="col-md-3 col-6">
                                    <div class="description-block"><i
                                            class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><span
                                            class="description-text">سبب التوقف</span>
                                        <h5 class="description-header">{{ $well->stop_reason }}</h5>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($well->general_notes)
                            <hr class="section-divider">
                            <div class="row">
                                <div class="col-12">
                                    <div class="description-block">
                                        <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                        <span class="description-text">ملاحظات عامة</span>
                                        <h5 class="description-header"
                                            style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                            {{ $well->general_notes }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div> {{-- نهاية card-footer --}}
                </div> {{-- نهاية card --}}
            </div> {{-- نهاية col-md-12 --}}
        </div> {{-- نهاية row --}}

        {{-- قسم الأزرار (بعد إزالة زر PDF) --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('wells.edit', $well->id) }}" class="btn btn-lg btn-warning"><i
                        class="fas fa-edit ml-1"></i> تعديل</a>
                <a href="{{ route('wells.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection

{{-- تم حذف قسم push('scripts') بالكامل لعدم الحاجة إليه --}}
