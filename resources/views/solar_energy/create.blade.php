<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة بيانات الطاقة الشمسية</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('import.solar_energies') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">تحميل الملف</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">استيراد البيانات</button>
                </form>
            @endif
            <!-- نموذج إضافة بيانات الطاقة الشمسية -->
            <form action="{{ route('solar_energy.store') }}" method="POST" class="login-form">
                @csrf
                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <div class="card-body">
                                <!-- حقل المحطة -->
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id') == $station->id ? 'selected' : '' }}>
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
                                    value="{{ old('panel_size') }}" placeholder="أدخل قياس اللوح" required>
                                @error('panel_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل عدد الألواح -->
                                <label for="panel_count">عدد الألواح</label>
                                <input type="number" name="panel_count" id="panel_count"
                                    class="form-control @error('panel_count') is-invalid @enderror"
                                    value="{{ old('panel_count') }}" placeholder="أدخل عدد الألواح" required>
                                @error('panel_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الجهة المنشئة -->
                                <label for="manufacturer">الجهة المنشئة</label>
                                <input type="text" name="manufacturer" id="manufacturer"
                                    class="form-control @error('manufacturer') is-invalid @enderror"
                                    value="{{ old('manufacturer') }}" placeholder="أدخل الجهة المنشئة" required>
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل نوع القاعدة -->
                                <label for="base_type">نوع القاعدة</label>
                                <select name="base_type" id="base_type"
                                    class="form-control @error('base_type') is-invalid @enderror" required>
                                    <option value="">-- اختر نوع القاعدة --</option>
                                    <option value="ثابتة" {{ old('base_type') == 'ثابتة' ? 'selected' : '' }}>ثابتة
                                    </option>
                                    <option value="متحركة" {{ old('base_type') == 'متحركة' ? 'selected' : '' }}>متحركة
                                    </option>
                                </select>
                                @error('base_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-warning">
                                الحالة والموقع
                            </div>
                            <div class="card-body">
                                <!-- حقل الحالة الفنية -->
                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" id="technical_condition"
                                    class="form-control @error('technical_condition') is-invalid @enderror"
                                    value="{{ old('technical_condition') }}" placeholder="أدخل الحالة الفنية" required>
                                @error('technical_condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل عدد الآبار المغذاة -->
                                <label for="wells_supplied_count">عدد الآبار المغذاة</label>
                                <input type="number" name="wells_supplied_count" id="wells_supplied_count"
                                    class="form-control @error('wells_supplied_count') is-invalid @enderror"
                                    value="{{ old('wells_supplied_count') }}" placeholder="أدخل عدد الآبار المغذاة"
                                    required>
                                @error('wells_supplied_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الملاحظات -->
                                <label for="general_notes">الملاحظات</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes') }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الموقع (خط العرض وخط الطول) -->
                                <label for="latitude">خط العرض</label>
                                <input type="text" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude') }}" placeholder="أدخل خط العرض">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="longitude">خط الطول</label>
                                <input type="text" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude') }}" placeholder="أدخل خط الطول">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary">إضافة بيانات الطاقة الشمسية</button>
            </form>
        </div>
    </div>
@endsection
