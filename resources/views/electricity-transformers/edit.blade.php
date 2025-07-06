@extends('layouts.app')
@section('title', 'تعديل بيانات محولة كهربائية')

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
                <h1 class="m-0">تعديل بيانات محولة كهربائية</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('electricity-transformers.index') }}">المحولات
                            الكهربائية</a></li>
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
                            بيانات المحولة الكهربائية
                        </h3>
                    </div>

                    <form action="{{ route('electricity-transformers.update', $electricityTransformer->id) }}"
                        method="POST" novalidate>
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
                                                    {{ old('station_id', $electricityTransformer->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operational_status">الوضع التشغيلي<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="operational_status" id="operational_status" class="form-control"
                                                required>
                                                <option value="تعمل"
                                                    {{ old('operational_status', $electricityTransformer->operational_status) == 'تعمل' ? 'selected' : '' }}>
                                                    تعمل</option>
                                                <option value="متوقفة"
                                                    {{ old('operational_status', $electricityTransformer->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                                    متوقفة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transformer_capacity">استطاعة المحولة (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="number" step="0.01" name="transformer_capacity"
                                                class="form-control"
                                                value="{{ old('transformer_capacity', $electricityTransformer->transformer_capacity) }}"
                                                placeholder="أدخل استطاعة المحولة" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distance_from_station">البعد عن المحطة (متر)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-route"></i></span></div>
                                            <input type="number" step="0.01" name="distance_from_station"
                                                class="form-control"
                                                value="{{ old('distance_from_station', $electricityTransformer->distance_from_station) }}"
                                                placeholder="أدخل البعد عن المحطة" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. تفاصيل الاستخدام والاستطاعة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-tasks text-success ml-2"></i>تفاصيل الاستخدام والاستطاعة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_station_transformer">هل المحولة خاصة بالمحطة؟<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-user-check"></i></span></div>
                                            <select name="is_station_transformer" id="is_station_transformer"
                                                class="form-control" required>
                                                <option value="1"
                                                    {{ old('is_station_transformer', $electricityTransformer->is_station_transformer) == 1 ? 'selected' : '' }}>
                                                    نعم</option>
                                                <option value="0"
                                                    {{ old('is_station_transformer', $electricityTransformer->is_station_transformer) == 0 ? 'selected' : '' }}>
                                                    لا (مشتركة)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="shared_with_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="talk_about_station_transformer">الجهات المشاركة في المحولة</label>
                                        <textarea name="talk_about_station_transformer" class="form-control" rows="1"
                                            placeholder="اذكر الجهات التي تشترك بالمحولة">{{ old('talk_about_station_transformer', $electricityTransformer->talk_about_station_transformer) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_capacity_sufficient">هل الاستطاعة كافية؟<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-check-circle"></i></span></div>
                                            <select name="is_capacity_sufficient" id="is_capacity_sufficient"
                                                class="form-control" required>
                                                <option value="1"
                                                    {{ old('is_capacity_sufficient', $electricityTransformer->is_capacity_sufficient) == 1 ? 'selected' : '' }}>
                                                    نعم</option>
                                                <option value="0"
                                                    {{ old('is_capacity_sufficient', $electricityTransformer->is_capacity_sufficient) == 0 ? 'selected' : '' }}>
                                                    لا</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="capacity_needed_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="how_mush_capacity_need">كم الاستطاعة الإضافية المطلوبة (KVA)؟<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-plus-circle"></i></span></div>
                                            <input type="number" step="0.01" name="how_mush_capacity_need"
                                                class="form-control"
                                                value="{{ old('how_mush_capacity_need', $electricityTransformer->how_mush_capacity_need) }}"
                                                placeholder="أدخل الاستطاعة المطلوبة">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات عامة</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $electricityTransformer->notes) }}</textarea>
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
                            <a href="{{ route('electricity-transformers.index') }}" class="btn btn-secondary btn-lg">
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

            // إظهار وإخفاء الحقول الشرطية
            const isStationTransformerSelect = $('#is_station_transformer');
            const sharedWithContainer = $('#shared_with_container');

            const isCapacitySufficientSelect = $('#is_capacity_sufficient');
            const capacityNeededContainer = $('#capacity_needed_container');

            function toggleSharedWith() {
                if (isStationTransformerSelect.val() == '0') { // 0 for "No"
                    sharedWithContainer.slideDown();
                } else {
                    sharedWithContainer.slideUp();
                }
            }

            function toggleCapacityNeeded() {
                if (isCapacitySufficientSelect.val() == '0') { // 0 for "No"
                    capacityNeededContainer.slideDown();
                } else {
                    capacityNeededContainer.slideUp();
                }
            }

            // ربط الأحداث
            isStationTransformerSelect.on('change', toggleSharedWith);
            isCapacitySufficientSelect.on('change', toggleCapacityNeeded);

            // التشغيل عند تحميل الصفحة لضبط الحالة الأولية
            toggleSharedWith();
            toggleCapacityNeeded();
        });
    </script>
@endpush
