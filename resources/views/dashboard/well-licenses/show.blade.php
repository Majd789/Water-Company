@extends('layouts.app')

@section('title', 'تفاصيل ترخيص: ' . $wellLicense->archive_code)

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تفاصيل ترخيص البئر</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.well-licenses.index') }}">تراخيص الآبار</a></li>
                        <li class="breadcrumb-item active">تفاصيل</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                تفاصيل الترخيص رقم: <span class="font-weight-bold">{{ $wellLicense->archive_code }}</span>
                            </h3>
                        </div>
                        <div class="card-body">

                            {{-- تم تقسيم التفاصيل إلى بطاقات صغيرة لتحسين العرض --}}
                            <div class="row">
                                {{-- بطاقة معلومات الترخيص --}}
                                <div class="col-md-6">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-file-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">مقدم الطلب</span>
                                            <span class="info-box-number">{{ $wellLicense->applicant_name }}</span>
                                            <span class="info-box-text text-muted mt-1">نوع الطلب</span>
                                            <span class="info-box-number"><span class="badge bg-info">{{ $wellLicense->request_type }}</span></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- بطاقة معلومات العقار --}}
                                <div class="col-md-6">
                                    <div class="info-box shadow-sm">
                                        <span class="info-box-icon bg-success"><i class="fas fa-map-marked-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">رقم العقار</span>
                                            <span class="info-box-number">{{ $wellLicense->property_number }}</span>
                                             <span class="info-box-text text-muted mt-1">المنطقة العقارية</span>
                                            <span class="info-box-number">{{ $wellLicense->property_zone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- قائمة بالتفاصيل الإضافية --}}
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>تاريخ كتاب المؤسسة</b> <a class="float-right">{{ $wellLicense->institution_letter_date?->format('Y-m-d') ?? 'N/A' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>رقم كتاب الموارد</b> <a class="float-right">{{ $wellLicense->directorate_letter_number ?? 'N/A' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>تاريخ كتاب الموارد</b> <a class="float-right">{{ $wellLicense->directorate_letter_date?->format('Y-m-d') ?? 'N/A' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>المسافة المصرح بها</b> <a class="float-right">{{ $wellLicense->declared_distance_meters ? $wellLicense->declared_distance_meters . ' متر' : 'N/A' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>أقرب محطة للمؤسسة</b> <a class="float-right">{{ $wellLicense->station->station_name ?? 'N/A' }}</a>
                                </li>
                                 <li class="list-group-item">
                                    <b>الإحداثيات (خط العرض، خط الطول)</b>
                                    <a class="float-right">{{ $wellLicense->latitude ?? 'N/A' }}, {{ $wellLicense->longitude ?? 'N/A' }}</a>
                                </li>
                            </ul>

                            {{-- قسم الأرشفة المادية --}}
                             @if($wellLicense->physical_cabinet || $wellLicense->physical_shelf || $wellLicense->physical_file_id)
                                <strong><i class="fas fa-archive mr-1 text-warning"></i> موقع الأرشفة المادي</strong>
                                <p class="text-muted mt-2">
                                    الخزانة: {{ $wellLicense->physical_cabinet ?? 'غير محدد' }} |
                                    الرف: {{ $wellLicense->physical_shelf ?? 'غير محدد' }} |
                                    الملف: {{ $wellLicense->physical_file_id ?? 'غير محدد' }}
                                </p>
                                <hr>
                            @endif

                            {{-- قسم الملاحظات --}}
                            @if($wellLicense->notes)
                                <strong><i class="fas fa-sticky-note mr-1 text-secondary"></i> ملاحظات</strong>
                                <p class="text-muted mt-2">
                                    {{ $wellLicense->notes }}
                                </p>
                            @endif
                        </div>

                        <div class="card-footer text-center">
                            @if($wellLicense->file_url)
                                <a href="{{ $wellLicense->file_url }}" target="_blank" class="btn btn-info"><i class="fas fa-link"></i> عرض الملف المرفق</a>
                            @endif
                            @can('well_licenses.edit')
                                <a href="{{ route('dashboard.well-licenses.edit', $wellLicense->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> تعديل</a>
                            @endcan
                            <a href="{{ route('dashboard.well-licenses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة للقائمة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
