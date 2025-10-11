{{-- resources/views/dashboard/safety_profiles/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'تعديل ملف السلامة: ' . $station->station_name)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">تعديل ملف السلامة</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.safety-profiles.index') }}">ملفات السلامة</a></li>
                    <li class="breadcrumb-item active">تعديل: {{ $station->station_name }}</li>
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
                            <i class="fas fa-shield-alt ml-1"></i>
                            ملف السلامة للمحطة: <span class="text-warning">{{ $station->station_name }}</span>
                        </h3>
                    </div>
                    <form action="{{ route('dashboard.safety-profiles.update', $station->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                                <i class="fas fa-hard-hat text-primary ml-2"></i>
                                معدات الوقاية الشخصية (PPE)
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="has_ppe" name="has_ppe" {{ old('has_ppe', $safetyProfile->has_ppe) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="has_ppe">هل تتوفر معدات الوقاية الشخصية؟</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ppe_types">ما هي أنواع معدات الوقاية المتوفرة؟</label>
                                        <input type="text" name="ppe_types" id="ppe_types" class="form-control"
                                               value="{{ old('ppe_types', $safetyProfile->ppe_types) }}" placeholder="مثال: خوذات، قفازات، نظارات واقية...">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="ppe_training_provided" name="ppe_training_provided" {{ old('ppe_training_provided', $safetyProfile->ppe_training_provided) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="ppe_training_provided">هل يتم تدريب العاملين على استخدامها؟</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                                <i class="fas fa-fire-extinguisher text-primary ml-2"></i>
                                إجراءات الطوارئ
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="has_fire_extinguishers" name="has_fire_extinguishers" {{ old('has_fire_extinguishers', $safetyProfile->has_fire_extinguishers) ? 'checked' : '' }}><label class="custom-control-label" for="has_fire_extinguishers">وجود طفايات حريق</label></div></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="has_evacuation_plan" name="has_evacuation_plan" {{ old('has_evacuation_plan', $safetyProfile->has_evacuation_plan) ? 'checked' : '' }}><label class="custom-control-label" for="has_evacuation_plan">وجود خطة إخلاء</label></div></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="emergency_numbers_visible" name="emergency_numbers_visible" {{ old('emergency_numbers_visible', $safetyProfile->emergency_numbers_visible) ? 'checked' : '' }}><label class="custom-control-label" for="emergency_numbers_visible">أرقام الطوارئ ظاهرة</label></div></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="has_first_aid_kit" name="has_first_aid_kit" {{ old('has_first_aid_kit', $safetyProfile->has_first_aid_kit) ? 'checked' : '' }}><label class="custom-control-label" for="has_first_aid_kit">توفر حقيبة إسعافات أولية</label></div></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="first_aid_training_provided" name="first_aid_training_provided" {{ old('first_aid_training_provided', $safetyProfile->first_aid_training_provided) ? 'checked' : '' }}><label class="custom-control-label" for="first_aid_training_provided">تدريب على الإسعافات الأولية</label></div></div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                                <i class="fas fa-exclamation-triangle text-primary ml-2"></i>
                                المواد الخطرة
                            </h5>
                             <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="chemical_storage_safe" name="chemical_storage_safe" {{ old('chemical_storage_safe', $safetyProfile->chemical_storage_safe) ? 'checked' : '' }}><label class="custom-control-label" for="chemical_storage_safe">تخزين آمن للمواد الكيميائية</label></div></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="has_warning_signs" name="has_warning_signs" {{ old('has_warning_signs', $safetyProfile->has_warning_signs) ? 'checked' : '' }}><label class="custom-control-label" for="has_warning_signs">وجود علامات تحذيرية</label></div></div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save ml-1"></i> حفظ التعديلات</button>
                            <a href="{{ route('dashboard.safety-profiles.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times ml-1"></i> إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
