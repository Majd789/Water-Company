{{-- resources/views/dashboard/assessments/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'تعديل تقييم')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">تعديل تقييم</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.assessments.index') }}">التقييمات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('dashboard.partials.alerts')
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            تعديل التقييم لـ: <span class="text-warning">{{ class_basename($assessment->assessmentable_type) }} #{{ $assessment->assessmentable_id }}</span>
                        </h3>
                    </div>
                    <form action="{{ route('dashboard.assessments.update', $assessment->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        @include('dashboard.assessments._form')
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save ml-1"></i> حفظ التعديلات</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg"><i class="fas fa-times ml-1"></i> إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
