@extends('layouts.app')
@section('title', 'تفاصيل مهمة الصيانة: #' . $maintenanceTask->id)

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
                <h1 class="m-0">تفاصيل مهمة: <span class="text-primary">رقم {{ $maintenanceTask->id }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.maintenance_tasks.index') }}">مهام الصيانة</a>
                    </li>
                    <li class="breadcrumb-item active">مهمة #{{ $maintenanceTask->id }}</li>
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
                    {{-- تحديد خلفية وأيقونة بناءً على حالة الإصلاح --}}
                    @php
                        $isFixed = $maintenanceTask->is_fixed;
                        $headerBg = $isFixed ? asset('dist/img/photo3.jpg') : asset('dist/img/photo3.png');
                        $iconClass = $isFixed ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
                        $iconBgColor = $isFixed ? 'bg-success' : 'bg-danger';
                    @endphp
                    <div class="widget-user-header" style="background-image: url('{{ $headerBg }}');">
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                                {{ $maintenanceTask->location ?? 'مكان غير محدد' }}
                            </h3>
                            <h5 class="widget-user-desc mt-2">وحدة: {{ $maintenanceTask->unit->unit_name ?? 'غير محددة' }}
                            </h5>
                        </div>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2 {{ $iconBgColor }}">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">حالة الإصلاح</span>
                                    <h5 class="description-header">
                                        @if ($isFixed)
                                            <span class="badge badge-success p-2">تم الإصلاح بنجاح</span>
                                        @else
                                            <span class="badge badge-danger p-2">لم يتم الإصلاح</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الفني المسؤول</span>
                                    <h5 class="description-header">{{ $maintenanceTask->technician_name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">تاريخ الصيانة</span>
                                    <h5 class="description-header">
                                        {{ \Carbon\Carbon::parse($maintenanceTask->maintenance_date)->format('Y-m-d') ?? 'N/A' }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- تفاصيل العطل --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="description-block">
                                    <i class="fas fa-search-location text-danger fa-2x mb-2"></i>
                                    <span class="description-text">وصف العطل</span>
                                    <p class="description-header-note">
                                        {{ $maintenanceTask->fault_description ?? 'لا يوجد وصف.' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="description-block">
                                    <i class="fas fa-question-circle text-warning fa-2x mb-2"></i>
                                    <span class="description-text">سبب العطل</span>
                                    <p class="description-header-note">
                                        {{ $maintenanceTask->fault_cause ?? 'لم يتم التحديد.' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">
                        {{-- تفاصيل الإصلاح --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="description-block">
                                    <i class="fas fa-tools text-primary fa-2x mb-2"></i>
                                    <span class="description-text">إجراءات الصيانة التي تمت</span>
                                    <p class="description-header-note">
                                        {{ $maintenanceTask->maintenance_actions ?? 'لا توجد إجراءات مسجلة.' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- يظهر هذا القسم فقط إذا لم يتم الإصلاح --}}
                        @if (!$isFixed && !empty($maintenanceTask->reason_not_fixed))
                            <hr class="section-divider">
                            <div class="row">
                                <div class="col-12">
                                    <div class="description-block">
                                        <i class="fas fa-comment-dots text-danger fa-2x mb-2"></i>
                                        <span class="description-text">سبب عدم اكتمال الإصلاح</span>
                                        <p class="description-header-note">{{ $maintenanceTask->reason_not_fixed }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- يظهر هذا القسم فقط إذا كانت هناك ملاحظات --}}
                        @if (!empty($maintenanceTask->notes))
                            <hr class="section-divider">
                            <div class="row">
                                <div class="col-12">
                                    <div class="description-block">
                                        <i class="fas fa-file-alt text-info fa-2x mb-2"></i>
                                        <span class="description-text">ملاحظات إضافية</span>
                                        <p class="description-header-note">{{ $maintenanceTask->notes }}</p>
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
                @can('maintenance_tasks.edit')
                    <a href="{{ route('dashboard.maintenance_tasks.edit', $maintenanceTask->id) }}"
                        class="btn btn-lg btn-warning"><i class="fas fa-edit ml-1"></i> تعديل</a>
                @endcan
                <a href="{{ route('dashboard.maintenance_tasks.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection
