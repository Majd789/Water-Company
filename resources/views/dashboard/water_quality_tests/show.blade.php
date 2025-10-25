{{-- resources/views/dashboard/water_quality_tests/show.blade.php --}}
@extends('layouts.app')
@section('title', 'تفاصيل فحص: ' . $waterQualityTest->station->station_name)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل الفحص</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.water-quality-tests.index') }}">فحوصات جودة المياه</a></li>
                    <li class="breadcrumb-item active">تفاصيل</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        فحص بتاريخ {{ $waterQualityTest->test_date->format('Y-m-d') }}
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>المحطة</b> <a class="float-right">{{ $waterQualityTest->station->station_name ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>العكارة (NTU)</b> <span class="float-right">{{ $waterQualityTest->turbidity ?? 'لم تسجل' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>الرقم الهيدروجيني (pH)</b> <span class="float-right">{{ $waterQualityTest->ph_level ?? 'لم يسجل' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>التحليل الجرثومي</b> <span class="float-right">{{ $waterQualityTest->microbial_analysis ?? 'لم يسجل' }}</span>
                        </li>
                    </ul>

                    @if($waterQualityTest->complaints)
                    <strong><i class="fas fa-comment-dots mr-1"></i> شكاوى المستفيدين</strong>
                    <p class="text-muted mt-2">
                        {{ $waterQualityTest->complaints }}
                    </p>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('dashboard.water-quality-tests.edit', $waterQualityTest->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> تعديل</a>
                    <a href="{{ route('dashboard.water-quality-tests.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة للقائمة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
