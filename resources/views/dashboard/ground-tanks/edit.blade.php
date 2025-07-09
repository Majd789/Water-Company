@extends('layouts.app')
@section('title', 'تعديل بيانات خزان أرضي')

{{-- استيراد مكتبة Select2 و CSS مخصص --}}
@push('styles')
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        .select2-container--bootstrap4 .select2-selection {
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل بيانات خزان أرضي: <span class="text-primary">{{ $groundTank->tank_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.ground-tanks.index') }}">الخزانات الأرضية</a>
                    </li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">

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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الخزان الأرضي
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.ground-tanks.update', $groundTank->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $groundTank->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tank_name">اسم الخزان<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="tank_name" class="form-control"
                                                value="{{ old('tank_name', $groundTank->tank_name) }}"
                                                placeholder="أدخل اسم الخزان" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="building_entity">الجهة المنشئة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-building"></i></span></div>
                                            <input type="text" name="building_entity" class="form-control"
                                                value="{{ old('building_entity', $groundTank->building_entity) }}"
                                                placeholder="أدخل الجهة المنشئة" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="construction_type">نوع البناء<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-hard-hat"></i></span></div>
                                            <select name="construction_type" id="construction_type" class="form-control"
                                                required>
                                                <option value="قديم"
                                                    {{ old('construction_type', $groundTank->construction_type) == 'قديم' ? 'selected' : '' }}>
                                                    قديم</option>
                                                <option value="جديد"
                                                    {{ old('construction_type', $groundTank->construction_type) == 'جديد' ? 'selected' : '' }}>
                                                    جديد</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity">سعة الخزان (م³)<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-database"></i></span></div>
                                            <input type="number" name="capacity" class="form-control"
                                                value="{{ old('capacity', $groundTank->capacity) }}"
                                                placeholder="أدخل سعة الخزان بالمتر المكعب" required min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="readiness_percentage">نسبة الجاهزية (%)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" name="readiness_percentage" class="form-control"
                                                value="{{ old('readiness_percentage', $groundTank->readiness_percentage) }}"
                                                placeholder="أدخل نسبة الجاهزية (0-100)" required min="0"
                                                max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات التغذية والتزويد --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-exchange-alt text-success ml-2"></i>بيانات التغذية والتزويد</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="feeding_station">المحطة التي تعبئه<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sign-in-alt"></i></span></div>
                                            <input type="text" name="feeding_station" class="form-control"
                                                value="{{ old('feeding_station', $groundTank->feeding_station) }}"
                                                placeholder="أدخل اسم المحطة المعبئة" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town_supply">البلدة التي تشرب منه<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sign-out-alt"></i></span></div>
                                            <input type="text" name="town_supply" class="form-control"
                                                value="{{ old('town_supply', $groundTank->town_supply) }}"
                                                placeholder="أدخل اسم البلدة المستفيدة" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. أبعاد الأنابيب --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-ruler-combined text-info ml-2"></i>أبعاد الأنابيب</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pipe_diameter_inside">قطر أنبوب الدخول (بوصة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-circle-notch"></i></span></div>
                                            <input type="number" step="0.01" name="pipe_diameter_inside"
                                                class="form-control"
                                                value="{{ old('pipe_diameter_inside', $groundTank->pipe_diameter_inside) }}"
                                                placeholder="أدخل القطر الداخلي" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pipe_diameter_outside">قطر أنبوب الخروج (بوصة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-circle-notch"></i></span></div>
                                            <input type="number" step="0.01" name="pipe_diameter_outside"
                                                class="form-control"
                                                value="{{ old('pipe_diameter_outside', $groundTank->pipe_diameter_outside) }}"
                                                placeholder="أدخل القطر الخارجي" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. الموقع الجغرافي --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-map-marked-alt text-warning ml-2"></i>الموقع الجغرافي</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">خط العرض (Latitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="number" step="any" name="latitude" class="form-control"
                                                value="{{ old('latitude', $groundTank->latitude) }}"
                                                placeholder="مثال: 34.7335">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">خط الطول (Longitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="number" step="any" name="longitude" class="form-control"
                                                value="{{ old('longitude', $groundTank->longitude) }}"
                                                placeholder="مثال: 36.7135">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="altitude">الارتفاع (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-mountain"></i></span></div>
                                            <input type="number" step="any" name="altitude" class="form-control"
                                                value="{{ old('altitude', $groundTank->altitude) }}"
                                                placeholder="ارتفاع الموقع عن سطح البحر">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="precision">دقة الموقع (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bullseye"></i></span></div>
                                            <input type="number" step="any" name="precision" class="form-control"
                                                value="{{ old('precision', $groundTank->precision) }}"
                                                placeholder="هامش الخطأ في الإحداثيات">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('dashboard.ground-tanks.index') }}" class="btn btn-secondary btn-lg">
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

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });
        });
    </script>
@endpush
