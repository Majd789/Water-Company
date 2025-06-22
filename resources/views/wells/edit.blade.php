<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات البئر: {{ $well->well_name }}</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('wells.update', $well->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل البئر
                            </div>
                            <div class="card-body">
                                <!-- اسم البئر -->
                                <label for="well_name">اسم البئر</label>
                                <input type="text" class="form-control @error('well_name') is-invalid @enderror"
                                    name="well_name" value="{{ old('well_name', $well->well_name) }}"
                                    placeholder="أدخل اسم البئر" required>
                                @error('well_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- كود البلدة -->
                                <label for="town_code">كود البلدة</label>
                                <input type="text" class="form-control @error('town_code') is-invalid @enderror"
                                    name="town_code" value="{{ old('town_code', $well->town_code) }}"
                                    placeholder="أدخل كود البلدة" required>
                                @error('town_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اختر المحطة -->
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" class="form-control @error('station_id') is-invalid @enderror"
                                    required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $well->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اختر الوضع التشغيلي -->
                                <label for="well_status">الوضع التشغيلي</label>
                                <select name="well_status" class="form-control @error('well_status') is-invalid @enderror">
                                    <option value="">اختر الوضع التشغيلي</option>
                                    <option value="يعمل"
                                        {{ old('well_status', $well->well_status) == 'يعمل' ? 'selected' : '' }}>تشغيل
                                    </option>
                                    <option value="متوقف"
                                        {{ old('well_status', $well->well_status) == 'متوقف' ? 'selected' : '' }}>توقف
                                    </option>
                                </select>
                                @error('well_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- سبب التوقف -->
                                <label for="stop_reason">سبب التوقف</label>
                                <input type="text" name="stop_reason" id="stop_reason"
                                    class="form-control @error('stop_reason') is-invalid @enderror"
                                    value="{{ old('stop_reason', $well->stop_reason) }}" placeholder="أدخل سبب التوقف">
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- المسافة من المحطة -->
                                <label for="distance_from_station">المسافة من المحطة</label>
                                <input type="number"
                                    class="form-control @error('distance_from_station') is-invalid @enderror"
                                    name="distance_from_station"
                                    value="{{ old('distance_from_station', $well->distance_from_station) }}"
                                    placeholder="أدخل المسافة من المحطة">
                                @error('distance_from_station')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نوع البئر -->
                                <label for="well_type">نوع البئر</label>
                                <select name="well_type" class="form-control @error('well_type') is-invalid @enderror">
                                    <option value="">اختر نوع البئر</option>
                                    <option value="جوفي"
                                        {{ old('well_type', $well->well_type) == 'جوفي' ? 'selected' : '' }}>جوفي</option>
                                    <option value="سطحي"
                                        {{ old('well_type', $well->well_type) == 'سطحي' ? 'selected' : '' }}>سطحي</option>
                                </select>
                                @error('well_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- تدفق البئر -->
                                <label for="well_flow">تدفق البئر</label>
                                <input type="number" class="form-control @error('well_flow') is-invalid @enderror"
                                    name="well_flow" value="{{ old('well_flow', $well->well_flow) }}"
                                    placeholder="أدخل تدفق البئر">
                                @error('well_flow')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- العمق الستاتيكي -->
                                <label for="static_depth">العمق الستاتيكي</label>
                                <input type="number" class="form-control @error('static_depth') is-invalid @enderror"
                                    name="static_depth" value="{{ old('static_depth', $well->static_depth) }}"
                                    placeholder="أدخل العمق الستاتيكي">
                                @error('static_depth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- العمق الديناميكي -->
                                <label for="dynamic_depth">العمق الديناميكي</label>
                                <input type="number" class="form-control @error('dynamic_depth') is-invalid @enderror"
                                    name="dynamic_depth" value="{{ old('dynamic_depth', $well->dynamic_depth) }}"
                                    placeholder="أدخل العمق الديناميكي">
                                @error('dynamic_depth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- عمق الحفر -->
                                <label for="drilling_depth">عمق الحفر</label>
                                <input type="number" class="form-control @error('drilling_depth') is-invalid @enderror"
                                    name="drilling_depth" value="{{ old('drilling_depth', $well->drilling_depth) }}"
                                    placeholder="أدخل عمق الحفر">
                                @error('drilling_depth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- قطر البئر -->
                                <label for="well_diameter">قطر البئر</label>
                                <input type="number" class="form-control @error('well_diameter') is-invalid @enderror"
                                    name="well_diameter" value="{{ old('well_diameter', $well->well_diameter) }}"
                                    placeholder="أدخل قطر البئر">
                                @error('well_diameter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- عمق تركيب المضخة -->
                                <label for="pump_installation_depth">عمق تركيب المضخة</label>
                                <input type="number"
                                    class="form-control @error('pump_installation_depth') is-invalid @enderror"
                                    name="pump_installation_depth"
                                    value="{{ old('pump_installation_depth', $well->pump_installation_depth) }}"
                                    placeholder="أدخل عمق تركيب المضخة">
                                @error('pump_installation_depth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- استطاعة المضخة -->
                                <label for="pump_capacity">استطاعة المضخة</label>
                                <input type="number" class="form-control @error('pump_capacity') is-invalid @enderror"
                                    name="pump_capacity" value="{{ old('pump_capacity', $well->pump_capacity) }}"
                                    placeholder="أدخل استطاعة المضخة">
                                @error('pump_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- تدفق المضخة الفعلي -->
                                <label for="actual_pump_flow">تدفق المضخة الفعلي</label>
                                <input type="number"
                                    class="form-control @error('actual_pump_flow') is-invalid @enderror"
                                    name="actual_pump_flow"
                                    value="{{ old('actual_pump_flow', $well->actual_pump_flow) }}"
                                    placeholder="أدخل تدفق المضخة الفعلي">
                                @error('actual_pump_flow')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- رفع المضخة -->
                                <label for="pump_lifting">رفع المضخة</label>
                                <input type="number" class="form-control @error('pump_lifting') is-invalid @enderror"
                                    name="pump_lifting" value="{{ old('pump_lifting', $well->pump_lifting) }}"
                                    placeholder="أدخل رفع المضخة">
                                @error('pump_lifting')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ماركة وموديل المضخة -->
                                <label for="pump_brand_model">ماركة وموديل المضخة</label>
                                <input type="text"
                                    class="form-control @error('pump_brand_model') is-invalid @enderror"
                                    name="pump_brand_model"
                                    value="{{ old('pump_brand_model', $well->pump_brand_model) }}"
                                    placeholder="أدخل ماركة وموديل المضخة">
                                @error('pump_brand_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- مصدر الطاقة -->
                                <label for="energy_source">مصدر الطاقة</label>
                                <input type="text" class="form-control @error('energy_source') is-invalid @enderror"
                                    name="energy_source" value="{{ old('energy_source', $well->energy_source) }}"
                                    placeholder="أدخل مصدر الطاقة">
                                @error('energy_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- عنوان البئر -->
                                <label for="well_address">عنوان البئر</label>
                                <input type="text" class="form-control @error('well_address') is-invalid @enderror"
                                    name="well_address" value="{{ old('well_address', $well->well_address) }}"
                                    placeholder="أدخل عنوان البئر">
                                @error('well_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات عامة -->
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة">{{ old('general_notes', $well->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- إحداثيات البئر -->
                                <label for="well_location">إحداثيات البئر</label>
                                <input type="text" class="form-control @error('well_location') is-invalid @enderror"
                                    name="well_location" value="{{ old('well_location', $well->well_location) }}"
                                    placeholder="أدخل إحداثيات البئر">
                                @error('well_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
