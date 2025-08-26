@extends('layouts.app')

@section('title', 'إضافة شكوى جديدة')

@push('styles')
    {{-- (انسخ نفس محتوى قسم @push('styles') من صفحة إضافة مهمة صيانة) --}}
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>إضافة شكوى جديدة</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.complaints.index') }}">الشكاوى</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title mb-0"><i class="fas fa-plus-circle ml-1"></i> بيانات الشكوى</h3></div>
                    <form action="{{ route('dashboard.complaints.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="card-body">
                            {{-- 1. معلومات أساسية --}}
                            <h5 class="mt-2 mb-3 section-title"><i class="fas fa-info-circle text-primary ml-2"></i>معلومات الشكوى الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6"><div class="form-group"><label for="complainant_name">اسم المشتكي<span class="text-danger">*</span></label><input type="text" name="complainant_name" id="complainant_name" class="form-control" value="{{ old('complainant_name') }}" required></div></div>
                                <div class="col-md-6"><div class="form-group"><label for="town_id">البلدة<span class="text-danger">*</span></label><select name="town_id" id="town_id" class="form-control select2" required><option value="" disabled selected>-- اختر البلدة --</option>@foreach ($towns as $town)<option value="{{ $town->id }}" {{ old('town_id') == $town->id ? 'selected' : '' }}>{{ $town->town_name }}</option>@endforeach</select></div></div>
                                <div class="col-md-6"><div class="form-group"><label for="complaint_type_id">نوع الشكوى<span class="text-danger">*</span></label><select name="complaint_type_id" id="complaint_type_id" class="form-control select2" required><option value="" disabled selected>-- اختر النوع --</option>@foreach ($complaintTypes as $type)<option value="{{ $type->id }}" {{ old('complaint_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>@endforeach</select></div></div>
                                <div class="col-md-6"><div class="form-group"><label for="building_code">رمز البناء (إن وجد)</label><input type="text" name="building_code" id="building_code" class="form-control" value="{{ old('building_code') }}"></div></div>
                            </div>

                            {{-- 2. تفاصيل الموقع --}}
                            <h5 class="mt-4 mb-3 section-title"><i class="fas fa-map-marked-alt text-warning ml-2"></i>تفاصيل الموقع</h5>
                            <div class="row">
                                <div class="col-md-6"><div class="form-group"><label>موقع الشكوى<span class="text-danger">*</span></label><div class="d-flex pt-2">
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="location_type" id="loc_inside" value="inside" {{ old('location_type', 'inside') == 'inside' ? 'checked' : '' }} required><label class="form-check-label" for="loc_inside">داخل المنزل</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="location_type" id="loc_outside" value="outside" {{ old('location_type') == 'outside' ? 'checked' : '' }} required><label class="form-check-label" for="loc_outside">خارج المنزل</label></div>
                                </div></div></div>
                                <div class="col-md-6"><div class="form-group"><label>هل قدمت نفس الشكوى مسبقاً؟<span class="text-danger">*</span></label><div class="d-flex pt-2">
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="is_repeated" id="rep_no" value="0" {{ old('is_repeated', 0) == 0 ? 'checked' : '' }} required><label class="form-check-label" for="rep_no">لا</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="is_repeated" id="rep_yes" value="1" {{ old('is_repeated') == 1 ? 'checked' : '' }} required><label class="form-check-label" for="rep_yes">نعم</label></div>
                                </div></div></div>
                            </div>
                            
                            {{-- 3. وصف الشكوى والصورة --}}
                             <h5 class="mt-4 mb-3 section-title"><i class="fas fa-file-alt text-info ml-2"></i>وصف الشكوى</h5>
                             <div class="row">
                                 <div class="col-md-8"><div class="form-group"><label for="details">تفاصيل الشكوى<span class="text-danger">*</span></label><textarea name="details" id="details" class="form-control" rows="4" required>{{ old('details') }}</textarea></div></div>
                                 <div class="col-md-4"><div class="form-group"><label for="image">إرفاق صورة إثباتية</label><div class="custom-file"><input type="file" class="custom-file-input" id="image" name="image"><label class="custom-file-label" for="image">اختر صورة</label></div></div></div>
                                 <input type="hidden" name="status" value="new"> {{-- إرسال الحالة الافتراضية --}}
                             </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save ml-1"></i> حفظ الشكوى</button>
                            <a href="{{ route('dashboard.complaints.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times ml-1"></i> إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- (انسخ نفس محتوى قسم @push('scripts') من صفحة إضافة مهمة صيانة لتفعيل Select2) --}}
@endpush