@extends('layouts.app')
@section('title', 'تفاصيل المولدة: ' . $generationGroup->generator_name)

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
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $generationGroup->generator_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('generation-groups.index') }}">مجموعات التوليد</a></li>
                    <li class="breadcrumb-item active">{{ $generationGroup->generator_name }}</li>
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
                    <div class="widget-user-header" style="background-image: url('{{ asset('dist/img/photo3.png') }}');">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                                {{ $generationGroup->generator_name }}</h3>
                            <h5 class="widget-user-desc mt-2">تابع لمحطة:
                                {{ $generationGroup->station->station_name ?? 'غير محدد' }}
                            </h5>
                        </div>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2 bg-secondary"><i class="fas fa-cogs"></i></div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الحالة التشغيلية</span>
                                    <h5 class="description-header">
                                        {{ $generationGroup->operational_status == 'working' ? 'يعمل' : 'متوقف' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الاستطاعة الاسمية</span>
                                    <h5 class="description-header">{{ $generationGroup->generation_capacity ?? '0' }}
                                        <small>KVA</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">الاستطاعة الفعلية</span>
                                    <h5 class="description-header">{{ $generationGroup->actual_operating_capacity ?? '0' }}
                                        <small>KVA</small>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- بيانات الاستهلاك والجاهزية --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-gas-pump text-danger fa-2x mb-2"></i><span
                                        class="description-text">استهلاك الوقود</span>
                                    <h5 class="description-header">{{ $generationGroup->fuel_consumption ?? '0' }}
                                        <small>لتر/س</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-oil-can text-warning fa-2x mb-2"></i><span
                                        class="description-text">مدة استخدام الزيت</span>
                                    <h5 class="description-header">{{ $generationGroup->oil_usage_duration ?? '0' }}
                                        <small>ساعة</small>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-tint text-info fa-2x mb-2"></i><span
                                        class="description-text">كمية الزيت للتبديل</span>
                                    <h5 class="description-header">
                                        {{ $generationGroup->oil_quantity_for_replacement ?? '0' }} <small>لتر</small></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-percentage text-success fa-2x mb-2"></i><span
                                        class="description-text">نسبة الجاهزية</span>
                                    <h5 class="description-header">
                                        {{ $generationGroup->generation_group_readiness_percentage ?? '0' }}%
                                    </h5>
                                </div>
                            </div>
                        </div>

                        {{-- قسم الملاحظات وسبب التوقف (يظهر بشكل شرطي) --}}
                        @if (($generationGroup->operational_status != 'working' && $generationGroup->stop_reason) || $generationGroup->notes)
                            <hr class="section-divider">
                            <div class="row d-flex justify-content-center">
                                @if ($generationGroup->operational_status != 'working' && $generationGroup->stop_reason)
                                    <div class="col-md-6">
                                        <div class="description-block"><i
                                                class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><span
                                                class="description-text">سبب التوقف</span>
                                            <h5 class="description-header"
                                                style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                                {{ $generationGroup->stop_reason }}</h5>
                                        </div>
                                    </div>
                                @endif
                                @if ($generationGroup->notes)
                                    <div class="col-md-6">
                                        <div class="description-block">
                                            <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                            <span class="description-text">ملاحظات عامة</span>
                                            <h5 class="description-header"
                                                style="text-transform: none; font-size: 1rem; font-weight: normal;">
                                                {{ $generationGroup->notes }}</h5>
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
                <a href="{{ route('generation-groups.edit', $generationGroup->id) }}" class="btn btn-lg btn-warning"><i
                        class="fas fa-edit ml-1"></i> تعديل</a>
                <a href="{{ route('generation-groups.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection
