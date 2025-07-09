<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>إضافة عقار جديد</h1>

            <!-- عرض الأخطاء في المدخلات -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج إضافة عقار جديد -->
            <form action="{{ route('dashboard.institution_properties.store') }}" method="POST" class="login-form">
                @csrf

                <!-- حقل المحطة -->
                <label for="station_id">المحطة</label>
                <select name="station_id" id="station_id" class="form-control @error('station_id') is-invalid @enderror"
                    required>
                    <option value="">اختر المحطة</option>
                    @foreach ($stations as $station)
                        <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                            {{ $station->station_name }}
                        </option>
                    @endforeach
                </select>
                @error('station_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل اسم القسم -->
                <label for="department_name">اسم القسم</label>
                <input type="text" name="department_name" id="department_name"
                    class="form-control @error('department_name') is-invalid @enderror" value="{{ old('department_name') }}"
                    placeholder="أدخل اسم القسم" required>
                @error('department_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل نوع العقار -->
                <label for="property_type">نوع العقار</label>
                <input type="text" name="property_type" id="property_type"
                    class="form-control @error('property_type') is-invalid @enderror" value="{{ old('property_type') }}"
                    placeholder="أدخل نوع العقار" required>
                @error('property_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل عمل العقار -->
                <label for="property_use">عمل العقار</label>
                <input type="text" name="property_use" id="property_use"
                    class="form-control @error('property_use') is-invalid @enderror" value="{{ old('property_use') }}"
                    placeholder="أدخل عمل العقار" required>
                @error('property_use')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل طبيعة العقار -->
                <label for="property_nature">طبيعة العقار</label>
                <input type="text" name="property_nature" id="property_nature"
                    class="form-control @error('property_nature') is-invalid @enderror"
                    value="{{ old('property_nature') }}" placeholder="أدخل طبيعة العقار" required>
                @error('property_nature')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل قيمة الإيجار -->
                <label for="rental_value">قيمة الإيجار</label>
                <input type="number" name="rental_value" id="rental_value" step="0.01"
                    class="form-control @error('rental_value') is-invalid @enderror" value="{{ old('rental_value') }}"
                    placeholder="أدخل قيمة الإيجار" required>
                @error('rental_value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- حقل الملاحظات -->
                <label for="general_notes">الملاحظات</label>
                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                    placeholder="أدخل الملاحظات">{{ old('general_notes') }}</textarea>
                @error('general_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-success">إضافة العقار</button>
            </form>
        </div>
    </div>

@endsection
