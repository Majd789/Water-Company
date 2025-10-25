{{-- resources/views/dashboard/safety_profiles/show.blade.php --}}
@extends('layouts.app')
@section('title', 'ملف السلامة: ' . $station->station_name)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">ملف السلامة للمحطة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.safety-profiles.index') }}">ملفات السلامة</a></li>
                    <li class="breadcrumb-item active">{{ $station->station_name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shield-alt mr-1"></i>
                            تفاصيل السلامة والأمان للمحطة: <span class="text-primary">{{ $station->station_name }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        {{-- قسم معدات الوقاية --}}
                        <strong><i class="fas fa-hard-hat mr-1"></i> معدات الوقاية الشخصية (PPE)</strong>
                        <ul class="list-group list-group-unbordered my-3">
                            <li class="list-group-item">
                                <b>هل تتوفر المعدات؟</b>
                                <span class="float-right badge badge-{{ $safetyProfile->has_ppe ? 'success' : 'danger' }}">
                                    {{ $safetyProfile->has_ppe ? 'نعم' : 'لا' }}
                                </span>
                            </li>
                            @if($safetyProfile->has_ppe)
                            <li class="list-group-item">
                                <b>الأنواع المتوفرة</b> <a class="float-right">{{ $safetyProfile->ppe_types ?: 'لم يحدد' }}</a>
                            </li>
                            @endif
                            <li class="list-group-item">
                                <b>هل يتم تدريب العاملين؟</b>
                                <span class="float-right badge badge-{{ $safetyProfile->ppe_training_provided ? 'success' : 'danger' }}">
                                    {{ $safetyProfile->ppe_training_provided ? 'نعم' : 'لا' }}
                                </span>
                            </li>
                        </ul>
                        <hr>

                        {{-- قسم إجراءات الطوارئ --}}
                        <strong><i class="fas fa-fire-extinguisher mr-1"></i> إجراءات الطوارئ</strong>
                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <p class="text-sm"><b>وجود طفايات حريق:</b> <i class="fas {{ $safetyProfile->has_fire_extinguishers ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-sm"><b>وجود خطة إخلاء:</b> <i class="fas {{ $safetyProfile->has_evacuation_plan ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-sm"><b>أرقام الطوارئ ظاهرة:</b> <i class="fas {{ $safetyProfile->emergency_numbers_visible ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-sm"><b>توفر حقيبة إسعافات أولية:</b> <i class="fas {{ $safetyProfile->has_first_aid_kit ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-sm"><b>تدريب على الإسعافات الأولية:</b> <i class="fas {{ $safetyProfile->first_aid_training_provided ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                        </div>
                        <hr>

                        {{-- قسم المواد الخطرة --}}
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> المواد الخطرة</strong>
                        <div class="row mt-3">
                             <div class="col-sm-6">
                                <p class="text-sm"><b>تخزين آمن للمواد الكيميائية:</b> <i class="fas {{ $safetyProfile->chemical_storage_safe ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-sm"><b>وجود علامات تحذيرية:</b> <i class="fas {{ $safetyProfile->has_warning_signs ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></p>
                            </div>
                        </div>

                        {{-- قسم الملاحظات --}}
                        @if($safetyProfile->notes)
                        <hr>
                        <strong><i class="fas fa-file-alt mr-1"></i> ملاحظات إضافية</strong>
                        <p class="text-muted mt-2">{{ $safetyProfile->notes }}</p>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('dashboard.safety-profiles.edit', $station->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> تعديل</a>
                        <a href="{{ route('dashboard.safety-profiles.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة للقائمة</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
