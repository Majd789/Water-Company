@extends('layouts.app')
@section('title', 'إضافة نشاط رئيسي جديد')

@push('styles')
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }
        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة نشاط رئيسي جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.master-activities.index') }}">الأنشطة الرئيسية</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-md-9">

                @include('dashboard.partials.alerts')

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات النشاط الجديد
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.master-activities.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">اسم النشاط<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-stream"></i></span></div>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="مثال: صيانة شبكة المياه" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code">الكود<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                             <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-barcode"></i></span></div>
                                            <input type="text" class="form-control" id="code" name="code"
                                                   placeholder="مثال: SSHM" value="{{ old('code') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit">الواحدة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                             <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tag"></i></span></div>
                                            <input type="text" class="form-control" id="unit" name="unit"
                                                   placeholder="مثال: piont, ml, lumpsum" value="{{ old('unit') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ النشاط
                            </button>
                            <a href="{{ route('dashboard.master-activities.index') }}" class="btn btn-secondary btn-lg">
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
