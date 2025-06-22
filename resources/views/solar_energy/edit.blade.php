<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات الطاقة الشمسية</h2>

            <!-- نموذج تعديل بيانات الطاقة الشمسية -->
            <form action="{{ route('solar_energy.update', $solarEnergy->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل بيانات الطاقة الشمسية
                            </div>
                            <div class="card-body">
                                <!-- حقل المحطة -->
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $solarEnergy->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل قياس اللوح -->
                                <label for="panel_size">قياس اللوح</label>
                                <input type="number" name="panel_size" id="panel_size"
                                    class="form-control @error('panel_size') is-invalid @enderror"
                                    value="{{ old('panel_size', $solarEnergy->panel_size) }}" placeholder="أدخل قياس اللوح"
                                    required>
                                @error('panel_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل عدد الألواح -->
                                <label for="panel_count">عدد الألواح</label>
                                <input type="number" name="panel_count" id="panel_count"
                                    class="form-control @error('panel_count') is-invalid @enderror"
                                    value="{{ old('panel_count', $solarEnergy->panel_count) }}"
                                    placeholder="أدخل عدد الألواح" required>
                                @error('panel_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الجهة المنشئة -->
                                <label for="manufacturer">الجهة المنشئة</label>
                                <input type="text" name="manufacturer" id="manufacturer"
                                    class="form-control @error('manufacturer') is-invalid @enderror"
                                    value="{{ old('manufacturer', $solarEnergy->manufacturer) }}"
                                    placeholder="أدخل الجهة المنشئة" required>
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل نوع القاعدة -->
                                <label for="base_type">نوع القاعدة</label>
                                <input type="text" name="base_type" id="base_type"
                                    class="form-control @error('base_type') is-invalid @enderror"
                                    value="{{ old('base_type', $solarEnergy->base_type) }}" placeholder="أدخل نوع القاعدة"
                                    required>
                                @error('base_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الحالة الفنية -->
                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" id="technical_condition"
                                    class="form-control @error('technical_condition') is-invalid @enderror"
                                    value="{{ old('technical_condition', $solarEnergy->technical_condition) }}"
                                    placeholder="أدخل الحالة الفنية" required>
                                @error('technical_condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل عدد الآبار المغذاة -->
                                <label for="wells_supplied_count">عدد الآبار المغذاة</label>
                                <input type="number" name="wells_supplied_count" id="wells_supplied_count"
                                    class="form-control @error('wells_supplied_count') is-invalid @enderror"
                                    value="{{ old('wells_supplied_count', $solarEnergy->wells_supplied_count) }}"
                                    placeholder="أدخل عدد الآبار المغذاة" required>
                                @error('wells_supplied_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الملاحظات -->
                                <label for="general_notes">الملاحظات</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes', $solarEnergy->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الموقع (خط العرض وخط الطول) -->
                                <label for="latitude">خط العرض</label>
                                <input type="text" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $solarEnergy->latitude) }}" placeholder="أدخل خط العرض">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="longitude">خط الطول</label>
                                <input type="text" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $solarEnergy->longitude) }}" placeholder="أدخل خط الطول">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر الإرسال -->
                                <button type="submit" class="btn btn-primary">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
