{{-- resources/views/dashboard/station_teams/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'تعديل فريق المحطة: ' . $station->station_name)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل فريق المحطة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.station-teams.index') }}">فرق المحطات</a></li>
                    <li class="breadcrumb-item active">تعديل فريق: {{ $station->station_name }}</li>
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
                            <i class="fas fa-users-cog ml-1"></i>
                            بيانات فريق المحطة: <span class="text-warning">{{ $station->station_name }}</span>
                        </h3>
                    </div>
                    <form action="{{ route('dashboard.station-teams.update', $station->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contact_number">رقم التواصل مع مسؤول المحطة</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control"
                                    value="{{ old('contact_number', $team->contact_number ?? '') }}" placeholder="أدخل رقم التواصل">
                                    </div>
                                </div>
                            </div>
                          </div>
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                                <i class="fas fa-user-friends text-primary ml-2"></i>
                                عدد أفراد الفرق
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="maintenance_team_count">عدد فريق الصيانة والتشغيل</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-hard-hat"></i></span></div>
                                            <input type="number" name="maintenance_team_count" id="maintenance_team_count" class="form-control"
                                                   value="{{ old('maintenance_team_count', $team->maintenance_team_count) }}" placeholder="أدخل العدد">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="water_quality_team_count">عدد فريق مراقبة نوعية المياه</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-tint"></i></span></div>
                                            <input type="number" name="water_quality_team_count" id="water_quality_team_count" class="form-control"
                                                   value="{{ old('water_quality_team_count', $team->water_quality_team_count) }}" placeholder="أدخل العدد">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="admin_team_count">عدد الفريق الإداري والمستودعات</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tie"></i></span></div>
                                            <input type="number" name="admin_team_count" id="admin_team_count" class="form-control"
                                                   value="{{ old('admin_team_count', $team->admin_team_count) }}" placeholder="أدخل العدد">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                                <i class="fas fa-clipboard-check text-primary ml-2"></i>
                                تقييم المهارات
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="maintenance_team_skills">مهارات فريق الصيانة والتشغيل</label>
                                        <textarea name="maintenance_team_skills" id="maintenance_team_skills" class="form-control" rows="4" placeholder="صف مهارات الفريق هنا...">{{ old('maintenance_team_skills', $team->maintenance_team_skills) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="water_quality_team_skills">مهارات فريق مراقبة نوعية المياه</label>
                                        <textarea name="water_quality_team_skills" id="water_quality_team_skills" class="form-control" rows="4" placeholder="صف مهارات الفريق هنا...">{{ old('water_quality_team_skills', $team->water_quality_team_skills) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save ml-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('dashboard.station-teams.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times ml-1"></i> إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
