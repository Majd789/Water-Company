<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة بئر جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <div class="card-body">
                    <form action="{{ route('wells.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" required>
                        <button type="submit" class="btn btn-primary">استيراد الابار</button>
                    </form>
                </div>
            @endif

            <form action="{{ route('wells.store') }}" method="POST" class="login-form">
                @csrf

                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <div class="card-body">
                                <label for="well_name">اسم البئر<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="well_name" value="{{ old('well_name') }}"
                                    placeholder="أدخل اسم البئر" required>

                                <label for="town_code">كود البلدة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="town_code" value="{{ old('town_code') }}"
                                    placeholder="أدخل كود البلدة" required>

                                <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                <select name="station_id" class="form-control" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="well_type">نوع البئر<span class="text-danger">*</span></label>
                                <select name="well_type" class="form-control" required>
                                    <option value="">اختر نوع البئر</option>
                                    <option value="جوفي" {{ old('well_type') == 'جوفي' ? 'selected' : '' }}>جوفي</option>
                                    <option value="سطحي" {{ old('well_type') == 'سطحي' ? 'selected' : '' }}>سطحي</option>
                                </select>

                                <label for="well_status">الوضع التشغيلي</label>
                                <select name="well_status" class="form-control" id="well_status"
                                    onchange="toggleStopReason()">
                                    <option value="">اختر الوضع التشغيلي</option>
                                    <option value="يعمل" {{ old('well_status') == 'يعمل' ? 'selected' : '' }}>تشغيل
                                    </option>
                                    <option value="متوقف" {{ old('well_status') == 'متوقف' ? 'selected' : '' }}>توقف
                                    </option>
                                </select>

                                <!-- حقل سبب التوقف يظهر إذا تم اختيار "متوقف" -->
                                <div id="stop_reason_container" style="display: none;">
                                    <label for="stop_reason">سبب التوقف</label>
                                    <input type="text" class="form-control" name="stop_reason"
                                        value="{{ old('stop_reason') }}" placeholder="أدخل سبب التوقف">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات الحفر
                            </div>
                            <div class="card-body">
                                <label for="static_depth">العمق الستاتيكي</label>
                                <input type="number" class="form-control" name="static_depth"
                                    value="{{ old('static_depth') }}" placeholder="أدخل العمق الستاتيكي">

                                <label for="dynamic_depth">العمق الديناميكي</label>
                                <input type="number" class="form-control" name="dynamic_depth"
                                    value="{{ old('dynamic_depth') }}" placeholder="أدخل العمق الديناميكي">

                                <label for="drilling_depth">عمق الحفر</label>
                                <input type="number" class="form-control" name="drilling_depth"
                                    value="{{ old('drilling_depth') }}" placeholder="أدخل عمق الحفر">

                                <label for="well_diameter">قطر البئر</label>
                                <input type="number" class="form-control" name="well_diameter"
                                    value="{{ old('well_diameter') }}" placeholder="أدخل قطر البئر">
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 3: بيانات المضخة -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">
                                بيانات المضخة
                            </div>
                            <div class="card-body">
                                <label for="pump_installation_depth">عمق تركيب المضخة</label>
                                <input type="number" class="form-control" name="pump_installation_depth"
                                    value="{{ old('pump_installation_depth') }}" placeholder="أدخل عمق تركيب المضخة">

                                <label for="pump_capacity">استطاعة المضخة</label>
                                <input type="number" class="form-control" name="pump_capacity"
                                    value="{{ old('pump_capacity') }}" placeholder="أدخل استطاعة المضخة">

                                <label for="actual_pump_flow">تدفق المضخة الفعلي</label>
                                <input type="number" class="form-control" name="actual_pump_flow"
                                    value="{{ old('actual_pump_flow') }}" placeholder="أدخل تدفق المضخة الفعلي">

                                <label for="pump_lifting">رفع المضخة</label>
                                <input type="number" class="form-control" name="pump_lifting"
                                    value="{{ old('pump_lifting') }}" placeholder="أدخل رفع المضخة">

                                <label for="pump_brand_model">ماركة وموديل المضخة</label>
                                <select name="pump_brand_model" class="form-control">
                                    <option value="">-- اختر الماركة --</option>
                                    <option value="ATURIA" {{ old('pump_brand_model') == 'ATURIA' ? 'selected' : '' }}>
                                        ATURIA</option>
                                    <option value="CHINESE" {{ old('pump_brand_model') == 'CHINESE' ? 'selected' : '' }}>
                                        CHINESE</option>
                                    <option value="GRUNDFOS"
                                        {{ old('pump_brand_model') == 'GRUNDFOS' ? 'selected' : '' }}>GRUNDFOS</option>
                                    <option value="RED JACKET"
                                        {{ old('pump_brand_model') == 'RED JACKET' ? 'selected' : '' }}>RED JACKET</option>
                                    <option value="JET" {{ old('pump_brand_model') == 'JET' ? 'selected' : '' }}>JET
                                    </option>
                                    <option value="LOWARA" {{ old('pump_brand_model') == 'LOWARA' ? 'selected' : '' }}>
                                        LOWARA</option>
                                    <option value="LOWARA/EU"
                                        {{ old('pump_brand_model') == 'LOWARA/EU' ? 'selected' : '' }}>LOWARA/EU</option>
                                    <option value="LOWARA/FRANKLIN"
                                        {{ old('pump_brand_model') == 'LOWARA/FRANKLIN' ? 'selected' : '' }}>
                                        LOWARA/FRANKLIN</option>
                                    <option value="LOWARA/VOGEL"
                                        {{ old('pump_brand_model') == 'LOWARA/VOGEL' ? 'selected' : '' }}>LOWARA/VOGEL
                                    </option>
                                    <option value="PLUGER" {{ old('pump_brand_model') == 'PLUGER' ? 'selected' : '' }}>
                                        PLUGER</option>
                                    <option value="RITZ" {{ old('pump_brand_model') == 'RITZ' ? 'selected' : '' }}>RITZ
                                    </option>
                                    <option value="ROVATTI" {{ old('pump_brand_model') == 'ROVATTI' ? 'selected' : '' }}>
                                        ROVATTI</option>
                                    <option value="VANSAN" {{ old('pump_brand_model') == 'VANSAN' ? 'selected' : '' }}>
                                        VANSAN</option>
                                    <option value="WILLO" {{ old('pump_brand_model') == 'WILLO' ? 'selected' : '' }}>
                                        WILLO</option>
                                    <option value="غير معروف"
                                        {{ old('pump_brand_model') == 'غير معروف' ? 'selected' : '' }}>غير معروف</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 4: تدفق البئر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-warning">
                                تدفق البئر
                            </div>
                            <div class="card-body">
                                <label for="well_flow">تدفق البئر</label>
                                <input type="number" class="form-control" name="well_flow"
                                    value="{{ old('well_flow') }}" placeholder="أدخل تدفق البئر">

                                <label for="distance_from_station">المسافة من المحطة</label>
                                <input type="number" class="form-control" name="distance_from_station"
                                    value="{{ old('distance_from_station') }}" placeholder="أدخل المسافة من المحطة">
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 5: الموقع والملاحظات -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-secondary">
                                الموقع والملاحظات
                            </div>
                            <div class="card-body">
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
                                    value="{{ old('well_address') }}" placeholder="أدخل عنوان البئر">

                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control" placeholder="أدخل ملاحظات عامة">{{ old('general_notes') }}</textarea>

                                <label for="well_location">إحداثيات البئر</label>
                                <input type="text" class="form-control" name="well_location"
                                    value="{{ old('well_location') }}" placeholder="أدخل إحداثيات البئر">
                            </div>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">حفظ البيانات</button>
            </form>
        </div>
    </div>
@endsection

<script>
    function toggleStopReason() {
        var status = document.getElementById('well_status').value;
        var stopReasonContainer = document.getElementById('stop_reason_container');
        if (status === 'متوقف') {
            stopReasonContainer.style.display = 'block';
        } else {
            stopReasonContainer.style.display = 'none';
        }
    }
</script>
