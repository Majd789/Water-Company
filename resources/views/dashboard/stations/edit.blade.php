@extends('layouts.app')
@section('title', 'تعديل بيانات المحطة')

{{-- استيراد مكتبة Select2 --}}
{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <style>
        /* لا يتم تطبيق الألوان إلا بعد أن يبدأ المستخدم بالكتابة */
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        /* استهداف خاص لـ Select2 */
        .select2-container--bootstrap4 .select2-selection {
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control.is-valid~.select2-container--bootstrap4 .select2-selection {
            border-color: #28a745 !important;
        }

        .form-control.is-invalid~.select2-container--bootstrap4 .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل بيانات المحطة: <span class="text-primary">{{ $station->station_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.stations.index') }}">المحطات</a></li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10"> {{-- تم تكبير العرض قليلاً ليناسب الحقول الكثيرة --}}

                <!-- رسائل الحالة والأخطاء -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check ml-1"></i> نجاح!</h5>
                        {{ session('success') }}
                    </div>
                @endif

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


                <!-- الفورم الرئيسي لتعديل المحطة -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات المحطة
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.stations.update', $station->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT') {{-- مهم جداً لعملية التعديل --}}

                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_code">كود المحطة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-barcode"></i></span></div>
                                            <input type="text" class="form-control" id="station_code" name="station_code"
                                                placeholder="أدخل كود المحطة"
                                                value="{{ old('station_code', $station->station_code) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_name">اسم المحطة<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" class="form-control" id="station_name" name="station_name"
                                                placeholder="أدخل اسم المحطة"
                                                value="{{ old('station_name', $station->station_name) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operational_status">حالة التشغيل<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="operational_status" class="form-control" required>
                                                <option value="عاملة"
                                                    {{ old('operational_status', $station->operational_status) == 'عاملة' ? 'selected' : '' }}>
                                                    عاملة</option>
                                                <option value="متوقفة"
                                                    {{ old('operational_status', $station->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                                    متوقفة</option>
                                                <option value="خارج الخدمة"
                                                    {{ old('operational_status', $station->operational_status) == 'خارج الخدمة' ? 'selected' : '' }}>
                                                    خارج الخدمة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stop_reason">سبب التوقف (إن وجد)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span></div>
                                            <input type="text" class="form-control" name="stop_reason"
                                                placeholder="أدخل سبب التوقف"
                                                value="{{ old('stop_reason', $station->stop_reason) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_type">نوع المحطة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-industry"></i></span></div>
                                            <select name="station_type" class="form-control">
                                                <option value="محطة ابار ارتوازيه"
                                                    {{ old('station_type', $station->station_type) == 'محطة ابار ارتوازيه' ? 'selected' : '' }}>
                                                    محطة آبار ارتوازية</option>
                                                <option value="محطة رفع"
                                                    {{ old('station_type', $station->station_type) == 'محطة رفع' ? 'selected' : '' }}>
                                                    محطة رفع</option>
                                                <option value="محطة آبار سطحية"
                                                    {{ old('station_type', $station->station_type) == 'محطة آبار سطحية' ? 'selected' : '' }}>
                                                    محطة آبار سطحية</option>
                                                <option value="محطة ضخ"
                                                    {{ old('station_type', $station->station_type) == 'محطة ضخ' ? 'selected' : '' }}>
                                                    محطة ضخ</option>
                                                <option value="محطة ابار ارتوازيه ورفع"
                                                    {{ old('station_type', $station->station_type) == 'محطة ابار ارتوازيه ورفع' ? 'selected' : '' }}>
                                                    محطة آبار ارتوازية ورفع</option>
                                                <option value="محطة نبع"
                                                    {{ old('station_type', $station->station_type) == 'محطة نبع' ? 'selected' : '' }}>
                                                    محطة نبع</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. التبعية والطاقة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-sitemap text-success ml-2"></i>التبعية والطاقة والتشغيل</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town_id">البلدة<span class="text-danger">*</span></label>
                                        <select name="town_id" class="form-control select2" id="town_id" required>
                                            <option value="" disabled>-- اختر البلدة --</option>
                                            @if (auth()->user()->unit_id)
                                                @foreach (auth()->user()->unit->towns as $town)
                                                    <option value="{{ $town->id }}"
                                                        {{ old('town_id', $station->town_id) == $town->id ? 'selected' : '' }}>
                                                        {{ $town->town_name }}</option>
                                                @endforeach
                                            @else
                                                @foreach ($towns as $town)
                                                    <option value="{{ $town->id }}"
                                                        {{ old('town_id', $station->town_id) == $town->id ? 'selected' : '' }}>
                                                        {{ $town->town_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="energy_source">مصدر الطاقة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <select name="energy_source" class="form-control">
                                                <option value="">-- اختر مصدر الطاقة --</option>
                                                <option value="لا يوجد"
                                                    {{ old('energy_source', $station->energy_source) == 'لا يوجد' ? 'selected' : '' }}>
                                                    لا يوجد</option>
                                                <option value="كهرباء"
                                                    {{ old('energy_source', $station->energy_source) == 'كهرباء' ? 'selected' : '' }}>
                                                    كهرباء</option>
                                                <option value="مولدة"
                                                    {{ old('energy_source', $station->energy_source) == 'مولدة' ? 'selected' : '' }}>
                                                    مولدة</option>
                                                <option value="طاقة شمسية"
                                                    {{ old('energy_source', $station->energy_source) == 'طاقة شمسية' ? 'selected' : '' }}>
                                                    طاقة شمسية</option>
                                                <option value="كهرباء و مولدة"
                                                    {{ old('energy_source', $station->energy_source) == 'كهرباء و مولدة' ? 'selected' : '' }}>
                                                    كهرباء و مولدة</option>
                                                <option value="كهرباء و طاقة شمسية"
                                                    {{ old('energy_source', $station->energy_source) == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و طاقة شمسية</option>
                                                <option value="مولدة و طاقة شمسية"
                                                    {{ old('energy_source', $station->energy_source) == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    مولدة و طاقة شمسية</option>
                                                <option value="كهرباء و مولدة و طاقة شمسية"
                                                    {{ old('energy_source', $station->energy_source) == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                                    كهرباء و مولدة و طاقة شمسية</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operator_entity">جهة التشغيل</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-users-cog"></i></span></div>
                                            <select name="operator_entity" class="form-control" id="operator_entity">
                                                <option value="تشغيل تشاركي"
                                                    {{ old('operator_entity', $station->operator_entity) == 'تشغيل تشاركي' ? 'selected' : '' }}>
                                                    تشغيل تشاركي</option>
                                                <option value="المؤسسة العامة لمياه الشرب"
                                                    {{ old('operator_entity', $station->operator_entity) == 'المؤسسة العامة لمياه الشرب' ? 'selected' : '' }}>
                                                    المؤسسة العامة لمياه الشرب</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operator_name">اسم جهة التشغيل</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-user-tie"></i></span></div>
                                            <input type="text" class="form-control" name="operator_name"
                                                id="operator_name" placeholder="أدخل اسم جهة التشغيل"
                                                value="{{ old('operator_name', $station->operator_name) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. بيانات الشبكة والأرقام --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-network-wired text-info ml-2"></i>بيانات الشبكة والأرقام</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="water_delivery_method">طريقة توصيل المياه</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-faucet"></i></span></div>
                                            <select name="water_delivery_method" class="form-control">
                                                <option value="">-- اختر طريقة التوصيل --</option>
                                                <option value="شبكة"
                                                    {{ old('water_delivery_method', $station->water_delivery_method) == 'شبكة' ? 'selected' : '' }}>
                                                    شبكة</option>
                                                <option value="منهل"
                                                    {{ old('water_delivery_method', $station->water_delivery_method) == 'منهل' ? 'selected' : '' }}>
                                                    منهل</option>
                                                <option value="شبكة و منهل"
                                                    {{ old('water_delivery_method', $station->water_delivery_method) == 'شبكة و منهل' ? 'selected' : '' }}>
                                                    شبكة و منهل</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="network_readiness_percentage">نسبة جاهزية الشبكة (%)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" step="0.01" name="network_readiness_percentage"
                                                class="form-control" placeholder="أدخل نسبة الجاهزية"
                                                value="{{ old('network_readiness_percentage', $station->network_readiness_percentage) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="network_type">نوع الشبكة</label>
                                        <select name="network_type" id="network_type" class="form-control select2">
                                            <option value="">-- اختر نوع الشبكة --</option>
                                            <optgroup label="المواد الأساسية">
                                                <option value="بولي إيثيلين"
                                                    {{ old('network_type', $station->network_type) == 'بولي إيثيلين' ? 'selected' : '' }}>
                                                    بولي إيثيلين</option>
                                                <option value="حديد"
                                                    {{ old('network_type', $station->network_type) == 'حديد' ? 'selected' : '' }}>
                                                    حديد</option>
                                                <option value="فونط (حديد صب)"
                                                    {{ old('network_type', $station->network_type) == 'فونط (حديد صب)' ? 'selected' : '' }}>
                                                    فونط (حديد صب)</option>
                                                <option value="أترنيت"
                                                    {{ old('network_type', $station->network_type) == 'أترنيت' ? 'selected' : '' }}>
                                                    أترنيت</option>
                                                <option value="PVC"
                                                    {{ old('network_type', $station->network_type) == 'PVC' ? 'selected' : '' }}>
                                                    PVC</option>
                                            </optgroup>
                                            <optgroup label="التوليفات الشائعة">
                                                <option value="بولي إيثيلين و حديد"
                                                    {{ old('network_type', $station->network_type) == 'بولي إيثيلين و حديد' ? 'selected' : '' }}>
                                                    بولي إيثيلين و حديد</option>
                                                <option value="بولي إيثيلين و فونط"
                                                    {{ old('network_type', $station->network_type) == 'بولي إيثيلين و فونط' ? 'selected' : '' }}>
                                                    بولي إيثيلين و فونط</option>
                                                <option value="بولي إيثيلين و أترنيت"
                                                    {{ old('network_type', $station->network_type) == 'بولي إيثيلين و أترنيت' ? 'selected' : '' }}>
                                                    بولي إيثيلين و أترنيت</option>
                                                <option value="حديد و أترنيت"
                                                    {{ old('network_type', $station->network_type) == 'حديد و أترنيت' ? 'selected' : '' }}>
                                                    حديد و أترنيت</option>
                                                <option value="PVC و أترنيت"
                                                    {{ old('network_type', $station->network_type) == 'PVC و أترنيت' ? 'selected' : '' }}>
                                                    PVC و أترنيت</option>
                                                <option value="بولي إيثيلين و حديد و أترنيت"
                                                    {{ old('network_type', $station->network_type) == 'بولي إيثيلين و حديد و أترنيت' ? 'selected' : '' }}>
                                                    بولي إيثيلين و حديد و أترنيت</option>
                                            </optgroup>
                                            <optgroup label="أنواع أخرى">
                                                <option value="خط ضخ"
                                                    {{ old('network_type', $station->network_type) == 'خط ضخ' ? 'selected' : '' }}>
                                                    خط ضخ</option>
                                                <option value="غير محدد / أخرى"
                                                    {{ old('network_type', $station->network_type) == 'غير محدد / أخرى' ? 'selected' : '' }}>
                                                    غير محدد / أخرى</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="beneficiary_families_count">عدد الأسر المستفيدة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-users"></i></span></div>
                                            <input type="number" name="beneficiary_families_count" class="form-control"
                                                placeholder="أدخل عدد الأسر المستفيدة"
                                                value="{{ old('beneficiary_families_count', $station->beneficiary_families_count) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_flow_rate">معدل التدفق الفعلي (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" name="actual_flow_rate"
                                                class="form-control" placeholder="أدخل معدل التدفق الفعلي"
                                                value="{{ old('actual_flow_rate', $station->actual_flow_rate) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_disinfection">هل يوجد تعقيم؟</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-shield-virus"></i></span></div>
                                            <select name="has_disinfection" class="form-control">
                                                <option value="1"
                                                    {{ old('has_disinfection', $station->has_disinfection) == '1' ? 'selected' : '' }}>
                                                    نعم</option>
                                                <option value="0"
                                                    {{ old('has_disinfection', $station->has_disinfection) == '0' ? 'selected' : '' }}>
                                                    لا</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disinfection_reason">سبب عدم وجود تعقيم (إن وجد)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-comment-slash"></i></span></div>
                                            <input type="text" name="disinfection_reason" class="form-control"
                                                placeholder="أدخل السبب"
                                                value="{{ old('disinfection_reason', $station->disinfection_reason) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. بيانات الموقع والأرض --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-map-marked-alt text-warning ml-2"></i>بيانات الموقع والأرض</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">خط العرض (Latitude)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-pin"></i></span></div>
                                            <input type="number" step="any" name="latitude" class="form-control"
                                                placeholder="مثال: 34.7335"
                                                value="{{ old('latitude', $station->latitude) }}">
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
                                                placeholder="مثال: 36.7135"
                                                value="{{ old('longitude', $station->longitude) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="land_area">مساحة الأرض (م²)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-combined"></i></span></div>
                                            <input type="number" step="0.01" name="land_area" class="form-control"
                                                placeholder="أدخل مساحة الأرض"
                                                value="{{ old('land_area', $station->land_area) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="soil_type">نوع التربة</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span></div>
                                            <select name="soil_type" id="soil_type" class="form-control">
                                                <option value="">-- اختر نوع التربة --</option>
                                                <option value="أرض زراعية"
                                                    {{ old('soil_type', $station->soil_type) == 'أرض زراعية' ? 'selected' : '' }}>
                                                    أرض زراعية</option>
                                                <option value="أرض صخرية"
                                                    {{ old('soil_type', $station->soil_type) == 'أرض صخرية' ? 'selected' : '' }}>
                                                    أرض صخرية</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="detailed_address">العنوان التفصيلي</label>
                                        <textarea name="detailed_address" class="form-control" rows="2" placeholder="أدخل العنوان التفصيلي للمحطة">{{ old('detailed_address', $station->detailed_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="served_locations">المواقع المخدومة</label>
                                        <textarea name="served_locations" class="form-control" rows="2" placeholder="أدخل المواقع التي تخدمها المحطة">{{ old('served_locations', $station->served_locations) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="building_notes">ملاحظات حول المبنى</label>
                                        <textarea name="building_notes" class="form-control" rows="2" placeholder="أدخل ملاحظات حول حالة المبنى">{{ old('building_notes', $station->building_notes) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات العامة</label>
                                        <textarea name="general_notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية (اختياري)">{{ old('general_notes', $station->general_notes) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_verified">هل تم التحقق من الموقع؟</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-check-circle"></i></span></div>
                                            <select name="is_verified" class="form-control">
                                                <option value="1"
                                                    {{ old('is_verified', $station->is_verified) == '1' ? 'selected' : '' }}>
                                                    نعم</option>
                                                <option value="0"
                                                    {{ old('is_verified', $station->is_verified) == '0' ? 'selected' : '' }}>
                                                    لا</option>
                                            </select>
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
                            <a href="{{ route('dashboard.stations.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                    <!-- نهاية الفورم -->
                </div>
                <!-- /.card -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2 مع دعم كامل للغة العربية ومطابقة شكل bootstrap
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: $(this).data('placeholder')
            });

            // السكريبت الخاص بجهة التشغيل
            const operatorEntitySelect = document.getElementById('operator_entity');
            const operatorNameInput = document.getElementById('operator_name');

            function updateOperatorName() {
                if (!operatorEntitySelect || !operatorNameInput) return;

                if (operatorEntitySelect.value === 'المؤسسة العامة لمياه الشرب') {
                    operatorNameInput.value = 'المؤسسة العامة لمياه الشرب';
                    operatorNameInput.readOnly = true;
                } else {
                    // في صفحة التعديل، نسترجع القيمة القديمة أو القيمة الأصلية من قاعدة البيانات
                    operatorNameInput.value = '{{ old('operator_name', $station->operator_name) }}';
                    operatorNameInput.readOnly = false;
                    operatorNameInput.placeholder = 'أدخل اسم جهة التشغيل';
                }
            }

            if (operatorEntitySelect) {
                operatorEntitySelect.addEventListener('change', updateOperatorName);
                // تشغيل عند تحميل الصفحة لتطبيق الحالة الصحيحة فوراً
                updateOperatorName();
            }
        });
    </script>
@endpush
