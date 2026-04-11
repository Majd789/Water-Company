@extends('layouts.app')

@section('title', 'ملف الموظف: ' . $employee->full_name)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            /* خلفية تعبر عن بيئة العمل المكتبية أو الإدارية */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://img.freepik.com/free-photo/business-people-shaking-hands-together_53876-13391.jpg');
            background-size: cover;
            background-position: center center;
            background-color: #17a2b8;
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #17a2b8; /* لون الـ Info للموظفين */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .description-block { margin-bottom: 1.5rem; text-align: center; padding: 0 10px; }
        .description-text { display: block; color: #6c757d; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 5px; }
        .description-header { font-size: 1.1rem; font-weight: 600; color: #343a40; display: block; }
        .section-divider { border-top: 1px solid #dee2e6; margin: 2rem 0; }

        /* تنسيق خاص لحالة الموظف غير النشط */
        .inactive-box {
            background-color: #fff5f5;
            border-right: 5px solid #dc3545;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">ملف الموظف: <span class="text-info">{{ $employee->full_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.employees.index') }}">الموظفين</a></li>
                    <li class="breadcrumb-item active">{{ $employee->employee_code }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- البطاقة الرئيسية للموظف --}}
            <div class="col-md-8">
                <div class="card card-widget widget-user shadow-lg rounded">
                    {{-- رأس البطاقة --}}
                    <div class="widget-user-header text-white">
                        <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                            {{ $employee->full_name }}
                        </h3>
                        <h5 class="widget-user-desc mt-2">
                            {{ $employee->unit->name ?? 'بدون قسم' }} | {{ $employee->employee_code }}
                        </h5>
                    </div>

                    {{-- الأيقونة (صورة افتراضية أو أيقونة مستخدم) --}}
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{-- إحصائيات الرصيد والحالة --}}
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">الرصيد السنوي</span>
                                    <h5 class="description-header text-primary">
                                        {{ $employee->total_allowed_days }} يوم
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">الرصيد المتبقي</span>
                                    <h5 class="description-header text-success">
                                        {{ $employee->remaining_days }} يوم
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <span class="description-text">حالة العمل</span>
                                    <h5 class="description-header">
                                        @if($employee->is_active)
                                            <span class="badge badge-success">على رأس عمله</span>
                                        @else
                                            <span class="badge badge-danger">متوقف / مستقيل</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        {{-- تفاصيل إضافية --}}
                        <div class="card-body pt-0">

                            {{-- تنبيه الموظف غير النشط --}}
                            @if(!$employee->is_active)
                                <div class="inactive-box">
                                    <h5 class="text-danger"><i class="fas fa-user-slash ml-2"></i> تنبيه: الموظف غير نشط حالياً</h5>
                                    <p class="mb-0 mt-2 text-dark">
                                        هذا الموظف متوقف عن العمل، لا يمكن تقديم طلبات إجازة جديدة له.
                                    </p>
                                </div>
                            @endif

                            <h5 class="mb-3 text-info"><i class="fas fa-info-circle ml-1"></i> معلومات التوظيف</h5>
                            <div class="row bg-light rounded border p-3">
                                <div class="col-sm-6 mb-2">
                                    <strong><i class="fas fa-barcode ml-1"></i> الرقم الوظيفي:</strong>
                                    <span class="text-muted">{{ $employee->employee_code }}</span>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <strong><i class="fas fa-layer-group ml-1"></i> القسم/الوحدة:</strong>
                                    <span class="text-muted">{{ $employee->unit->name ?? '-' }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الجانبي --}}
            <div class="col-md-4">
                {{-- بطاقة الإحصائيات السريعة --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-pie ml-1"></i> ملخص الإجازات</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <small class="text-muted d-block">أيام مستهلكة</small>
                                <span class="font-weight-bold text-danger">
                                    {{ $employee->total_allowed_days - $employee->remaining_days }} يوم
                                </span>
                            </li>
                            <li class="list-group-item">
                                <small class="text-muted d-block">نسبة الاستهلاك</small>
                                <div class="progress progress-sm mt-1">
                                    @php
                                        $percent = ($employee->total_allowed_days > 0) ? (($employee->total_allowed_days - $employee->remaining_days) / $employee->total_allowed_days) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-info" style="width: {{ $percent }}%"></div>
                                </div>
                                <small class="text-muted">{{ round($percent, 1) }}%</small>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- بطاقة التواريخ --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="far fa-clock ml-1"></i> سجل الزمان</h3>
                    </div>
                    <div class="card-body">
                         <ul class="list-unstyled mb-0">
                             <li class="mb-2"><strong>تاريخ الإضافة:</strong> <span class="float-right">{{ $employee->created_at->format('Y-m-d') }}</span></li>
                             <li><strong>آخر تحديث:</strong> <span class="float-right">{{ $employee->updated_at->format('Y-m-d') }}</span></li>
                         </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-lg btn-info">
                    <i class="fas fa-edit ml-1"></i> تعديل البيانات
                </a>
                <a href="{{ route('dashboard.employees.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
@endsection
