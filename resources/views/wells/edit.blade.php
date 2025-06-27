<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات البئر: {{ $well->well_name }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>عفوًا! هناك بعض الأخطاء في الإدخال:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('wells.update', $well->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">المعلومات الأساسية</div>
                            <div class="card-body">
                                <label for="well_name">اسم البئر<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="well_name"
                                    value="{{ old('well_name', $well->well_name) }}" required>

                                <label for="town_code">كود البلدة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="town_code"
                                    value="{{ old('town_code', $well->town_code) }}" required>

                                <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                <select name="station_id" class="form-control" required>
                                    <option value="">-- اختر المحطة --</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $well->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="well_type">نوع البئر</label>
                                <select name="well_type" class="form-control">
                                    <option value="">-- اختر نوع البئر --</option>
                                    <option value="جوفي"
                                        {{ old('well_type', $well->well_type) == 'جوفي' ? 'selected' : '' }}>جوفي</option>
                                    <option value="سطحي"
                                        {{ old('well_type', $well->well_type) == 'سطحي' ? 'selected' : '' }}>سطحي</option>
                                </select>

                                <label for="well_status">الوضع التشغيلي</label>
                                <select name="well_status" class="form-control" id="well_status">
                                    <option value="">-- اختر الوضع --</option>
                                    <option value="يعمل"
                                        {{ old('well_status', $well->well_status) == 'يعمل' ? 'selected' : '' }}>يعمل
                                    </option>
                                    <option value="متوقف"
                                        {{ old('well_status', $well->well_status) == 'متوقف' ? 'selected' : '' }}>متوقف
                                    </option>
                                </select>

                                <div id="stop_reason_container" style="display: none;">
                                    <label for="stop_reason">سبب التوقف</label>
                                    <input type="text" class="form-control" name="stop_reason"
                                        value="{{ old('stop_reason', $well->stop_reason) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">بيانات الحفر</div>
                            <div class="card-body">
                                <label for="static_depth">العمق الستاتيكي (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="static_depth"
                                    value="{{ old('static_depth', $well->static_depth) }}">

                                <label for="dynamic_depth">العمق الديناميكي (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="dynamic_depth"
                                    value="{{ old('dynamic_depth', $well->dynamic_depth) }}">

                                <label for="drilling_depth">عمق الحفر (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="drilling_depth"
                                    value="{{ old('drilling_depth', $well->drilling_depth) }}">

                                <label for="well_diameter">قطر البئر (بوصة)</label>
                                <input type="number" step="0.01" class="form-control" name="well_diameter"
                                    value="{{ old('well_diameter', $well->well_diameter) }}">

                                <label for="well_flow">تدفق البئر (م³/ساعة)</label>
                                <input type="number" step="0.01" class="form-control" name="well_flow"
                                    value="{{ old('well_flow', $well->well_flow) }}">
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 3: بيانات المضخة -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">بيانات المضخة</div>
                            <div class="card-body">
                                <label for="pump_installation_depth">عمق تركيب المضخة (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="pump_installation_depth"
                                    value="{{ old('pump_installation_depth', $well->pump_installation_depth) }}">

                                <label for="pump_capacity">استطاعة المضخة (حصان)</label>
                                <input type="number" step="0.01" class="form-control" name="pump_capacity"
                                    value="{{ old('pump_capacity', $well->pump_capacity) }}">

                                <label for="actual_pump_flow">تدفق المضخة الفعلي (م³/ساعة)</label>
                                <input type="number" step="0.01" class="form-control" name="actual_pump_flow"
                                    value="{{ old('actual_pump_flow', $well->actual_pump_flow) }}">

                                <label for="pump_lifting">رفع المضخة (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="pump_lifting"
                                    value="{{ old('pump_lifting', $well->pump_lifting) }}">

                                <label for="pump_brand_model">ماركة المضخة</label>
                                <select name="pump_brand_model" class="form-control">
                                    <option value="">-- اختر الماركة --</option>
                                    <option value="ATURIA"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'ATURIA' ? 'selected' : '' }}>
                                        ATURIA</option>
                                    <option value="CHINESE"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'CHINESE' ? 'selected' : '' }}>
                                        CHINESE</option>
                                    <option value="GRUNDFOS"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'GRUNDFOS' ? 'selected' : '' }}>
                                        GRUNDFOS</option>
                                    <option value="RED JACKET"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'RED JACKET' ? 'selected' : '' }}>
                                        RED JACKET</option>
                                    <option value="JET"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'JET' ? 'selected' : '' }}>
                                        JET</option>
                                    <option value="LOWARA"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA' ? 'selected' : '' }}>
                                        LOWARA</option>
                                    <option value="LOWARA/EU"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/EU' ? 'selected' : '' }}>
                                        LOWARA/EU</option>
                                    <option value="LOWARA/FRANKLIN"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/FRANKLIN' ? 'selected' : '' }}>
                                        LOWARA/FRANKLIN</option>
                                    <option value="LOWARA/VOGEL"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'LOWARA/VOGEL' ? 'selected' : '' }}>
                                        LOWARA/VOGEL</option>
                                    <option value="PLUGER"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'PLUGER' ? 'selected' : '' }}>
                                        PLUGER</option>
                                    <option value="RITZ"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'RITZ' ? 'selected' : '' }}>
                                        RITZ</option>
                                    <option value="ROVATTI"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'ROVATTI' ? 'selected' : '' }}>
                                        ROVATTI</option>
                                    <option value="VANSAN"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'VANSAN' ? 'selected' : '' }}>
                                        VANSAN</option>
                                    <option value="WILLO"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'WILLO' ? 'selected' : '' }}>
                                        WILLO</option>
                                    <option value="غير معروف"
                                        {{ old('pump_brand_model', $well->pump_brand_model) == 'غير معروف' ? 'selected' : '' }}>
                                        غير معروف</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 4: الموقع والملاحظات -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-secondary">الموقع والملاحظات</div>
                            <div class="card-body">
                                <label for="distance_from_station">المسافة من المحطة (متر)</label>
                                <input type="number" step="0.01" class="form-control" name="distance_from_station"
                                    value="{{ old('distance_from_station', $well->distance_from_station) }}">

                                <label for="energy_source">مصدر الطاقة</label>
                                <select name="energy_source" class="form-control">
                                    <option value="">-- اختر مصدر الطاقة --</option>
                                    <option value="لا يوجد" {{ old('energy_source') == 'لا يوجد' ? 'selected' : '' }}>لا
                                        يوجد</option>
                                    <option value="كهرباء" {{ old('energy_source') == 'كهرباء' ? 'selected' : '' }}>كهرباء
                                    </option>
                                    <option value="مولدة" {{ old('energy_source') == 'مولدة' ? 'selected' : '' }}>مولدة
                                    </option>
                                    <option value="طاقة شمسية"
                                        {{ old('energy_source') == 'طاقة شمسية' ? 'selected' : '' }}>طاقة شمسية</option>
                                    <option value="كهرباء و مولدة"
                                        {{ old('energy_source') == 'كهرباء و مولدة' ? 'selected' : '' }}>كهرباء و مولدة
                                    </option>
                                    <option value="كهرباء و طاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>كهرباء و طاقة
                                        شمسية</option>
                                    <option value="مولدة و طاقة شمسية"
                                        {{ old('energy_source') == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>مولدة و طاقة
                                        شمسية</option>
                                    <option value="كهرباء و مولدة و طاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و مولدة و طاقة شمسية</option>
                                </select>

                                <label for="well_address">عنوان البئر</label>
                                <input type="text" class="form-control" name="well_address"
                                    value="{{ old('well_address', $well->well_address) }}">

                                <label for="well_location">إحداثيات البئر (خط عرض، خط طول)</label>
                                <input type="text" class="form-control" name="well_location"
                                    value="{{ old('well_location', $well->well_location) }}">

                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control">{{ old('general_notes', $well->general_notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div> <!-- نهاية cards-container -->

                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">تحديث
                    البيانات</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wellStatusSelect = document.getElementById('well_status');
            const stopReasonContainer = document.getElementById('stop_reason_container');

            function toggleStopReason() {
                if (wellStatusSelect.value === 'متوقف') {
                    stopReasonContainer.style.display = 'block';
                } else {
                    stopReasonContainer.style.display = 'none';
                    // اختياري: إفراغ الحقل عند إخفائه لعدم حفظ سبب توقف لبئر يعمل
                    // stopReasonContainer.querySelector('input').value = ''; 
                }
            }

            // أضف مستمع الحدث
            wellStatusSelect.addEventListener('change', toggleStopReason);

            // قم بتشغيل الوظيفة عند تحميل الصفحة لضبط الحالة الأولية
            toggleStopReason();
        });
    </script>
@endpush
