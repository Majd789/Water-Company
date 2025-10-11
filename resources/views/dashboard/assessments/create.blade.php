{{-- resources/views/dashboard/assessments/create.blade.php --}}
@extends('layouts.app')
@section('title', 'إضافة تقييم جديد')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">إضافة تقييم جديد</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.assessments.index') }}">التقييمات</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('dashboard.partials.alerts') {{-- <-- إضافة مهمة --}}
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle ml-1"></i>
                            إضافة تقييم جديد لـ: <span class="text-warning">{{ __(class_basename($assessmentable_type)) }} #{{ $assessmentable_id }}</span>
                        </h3>
                    </div>
                    <form action="{{ route('dashboard.assessments.store') }}" method="POST" novalidate>
                        @csrf
                        @include('dashboard.assessments._form', ['assessment' => new \App\Models\Assessment()])
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save ml-1"></i> حفظ</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg"><i class="fas fa-times ml-1"></i> إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
