{{-- resources/views/dashboard/water_quality_tests/create.blade.php --}}
@extends('layouts.app')
@section('title', 'إضافة فحص جودة مياه جديد')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة فحص جودة مياه جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.water-quality-tests.index') }}">فحوصات جودة المياه</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('dashboard.partials.alerts')
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الفحص الجديد
                        </h3>
                    </div>
                    <form action="{{ route('dashboard.water-quality-tests.store') }}" method="POST" novalidate>
                        @csrf
                        {{--  ====  التصحيح هنا  ==== --}}
                        @include('dashboard.water_quality_tests._form', [
                            'waterQualityTest' => new \App\Models\WaterQualityTest(),
                            'stations' => $stations
                        ])
                        {{--  ====================== --}}

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ الفحص
                            </button>
                            <a href="{{ route('dashboard.water-quality-tests.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
