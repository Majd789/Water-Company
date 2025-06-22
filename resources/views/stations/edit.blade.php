<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات المحطة: {{ $station->station_name }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stations.update', $station->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل بيانات المحطة
                            </div>
                            <div class="card-body">
                                <!-- كود المحطة -->
                                <label for="station_code">كود المحطة</label>
                                <input type="text" class="form-control @error('station_code') is-invalid @enderror"
                                    name="station_code" value="{{ old('station_code', $station->station_code) }}"
                                    placeholder="أدخل كود المحطة" required>
                                @error('station_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم المحطة -->
                                <label for="station_name">اسم المحطة</label>
                                <input type="text" class="form-control @error('station_name') is-invalid @enderror"
                                    name="station_name" value="{{ old('station_name', $station->station_name) }}"
                                    placeholder="أدخل اسم المحطة" required>
                                @error('station_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حالة التشغيل -->
                                <label for="operational_status">حالة التشغيل:</label>
                                <select name="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
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
                                @error('operational_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- سبب التوقف -->
                                <label for="stop_reason">سبب التوقف</label>
                                <input type="text" class="form-control @error('stop_reason') is-invalid @enderror"
                                    name="stop_reason" value="{{ old('stop_reason', $station->stop_reason) }}"
                                    placeholder="أدخل سبب التوقف (إن وجد)">
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- مصدر الطاقة -->
                                <label for="energy_source">مصدر الطاقة</label>
                                <input type="text" class="form-control @error('energy_source') is-invalid @enderror"
                                    name="energy_source" value="{{ old('energy_source', $station->energy_source) }}"
                                    placeholder="أدخل مصدر الطاقة"
                                    pattern="^(كهرباء ومولدة وطاقة شمسية|كهرباء ومولدة|متوقفة|خارج الخدمة|مولدة|طاقة شمسية ومولدة|كهرباء وطاقة شمسية|كهرباء|طاقة شمسية)$"
                                    title="القيم المسموح بها: كهرباء ومولدة وطاقة شمسية, كهرباء ومولدة, متوقفة, خارج الخدمة, مولدة, طاقة شمسية ومولدة, كهرباء وطاقة شمسية, كهرباء, طاقة شمسية">

                                @error('energy_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- الجهة المشغلة -->
                                <label for="operator_entity">جهة التشغيل:</label>
                                <select name="operator_entity"
                                    class="form-control @error('operator_entity') is-invalid @enderror">
                                    <option value="تشغيل تشاركي"
                                        {{ old('operator_entity', $station->operator_entity) == 'تشغيل تشاركي' ? 'selected' : '' }}>
                                        تشغيل تشاركي</option>
                                    <option value="المؤسسة العامة لمياه الشرب"
                                        {{ old('operator_entity', $station->operator_entity) == 'المؤسسة العامة لمياه الشرب' ? 'selected' : '' }}>
                                        المؤسسة العامة لمياه الشرب</option>
                                </select>
                                @error('operator_entity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم جهة التشغيل -->
                                <label for="operator_name">اسم جهة التشغيل</label>
                                <input type="text" class="form-control @error('operator_name') is-invalid @enderror"
                                    name="operator_name" value="{{ old('operator_name', $station->operator_name) }}"
                                    placeholder="أدخل اسم جهة التشغيل (إن وجد)">
                                @error('operator_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات عامة -->
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة (إن وجد)">{{ old('general_notes', $station->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- البلدة -->
                                <label for="town_id">البلدة:</label>
                                <select name="town_id" class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">-- اختر البلدة --</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ old('town_id', $station->town_id) == $town->id ? 'selected' : '' }}>
                                            {{ $town->town_name }}</option>
                                    @endforeach
                                </select>
                                @error('town_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- باقي الحقول -->
                                <label for="water_delivery_method">طريقة توصيل المياه</label>
                                <input type="text"
                                    class="form-control @error('water_delivery_method') is-invalid @enderror"
                                    name="water_delivery_method"
                                    value="{{ old('water_delivery_method', $station->water_delivery_method) }}"
                                    placeholder="أدخل طريقة توصيل المياه">
                                @error('water_delivery_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="network_readiness_percentage">نسبة جاهزية الشبكة</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('network_readiness_percentage') is-invalid @enderror"
                                    name="network_readiness_percentage"
                                    value="{{ old('network_readiness_percentage', $station->network_readiness_percentage) }}"
                                    placeholder="أدخل نسبة جاهزية الشبكة">
                                @error('network_readiness_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="network_type">نوع الشبكة</label>
                                <input type="text" class="form-control @error('network_type') is-invalid @enderror"
                                    name="network_type" value="{{ old('network_type', $station->network_type) }}"
                                    placeholder="أدخل نوع الشبكة">
                                @error('network_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="beneficiary_families_count">عدد الأسر المستفيدة</label>
                                <input type="number"
                                    class="form-control @error('beneficiary_families_count') is-invalid @enderror"
                                    name="beneficiary_families_count"
                                    value="{{ old('beneficiary_families_count', $station->beneficiary_families_count) }}"
                                    placeholder="أدخل عدد الأسر المستفيدة">
                                @error('beneficiary_families_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <!-- حقل "has disinfection" -->
                                <label for="has_disinfection">هل يوجد تعقيم؟ <span class="text-danger">*</span></label>
                                <select name="has_disinfection" class="form-control" required>
                                    <option value="">-- اختر --</option>
                                    <option value="1"
                                        {{ old('has_disinfection', $station->has_disinfection) == 1 ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="0"
                                        {{ old('has_disinfection', $station->has_disinfection) == 0 ? 'selected' : '' }}>لا
                                    </option>
                                </select>

                                <!-- حقل "is verified" -->
                                <label for="is_verified">هل تم التحقق؟ <span class="text-danger">*</span></label>
                                <select name="is_verified" class="form-control" required>
                                    <option value="">-- اختر --</option>
                                    <option value="1"
                                        {{ old('is_verified', $station->is_verified) == 1 ? 'selected' : '' }}>نعم</option>
                                    <option value="0"
                                        {{ old('is_verified', $station->is_verified) == 0 ? 'selected' : '' }}>لا</option>
                                </select>

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-success">تحديث البيانات</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
