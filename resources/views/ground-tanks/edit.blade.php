<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>تعديل خزان أرضي</h1>

            <!-- عرض الأخطاء -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج تعديل الخزان الأرضي -->
            <form action="{{ route('ground-tanks.update', $groundTank->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل خزان أرضي
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر محطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $groundTank->station_id == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم الخزان -->
                                <label for="tank_name">اسم الخزان</label>
                                <input type="text" name="tank_name" id="tank_name"
                                    class="form-control @error('tank_name') is-invalid @enderror"
                                    value="{{ old('tank_name', $groundTank->tank_name) }}" placeholder="اسم الخزان"
                                    required>
                                @error('tank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الجهة المنشئة -->
                                <label for="building_entity">الجهة المنشئة</label>
                                <input type="text" name="building_entity" id="building_entity"
                                    class="form-control @error('building_entity') is-invalid @enderror"
                                    value="{{ old('building_entity', $groundTank->building_entity) }}"
                                    placeholder="الجهة المنشئة" required>
                                @error('building_entity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نوع البناء -->
                                <label for="construction_type">نوع البناء</label>
                                <select name="construction_type" id="construction_type"
                                    class="form-control @error('construction_type') is-invalid @enderror" required>
                                    <option value="قديم"
                                        {{ old('construction_type', $groundTank->construction_type) == 'قديم' ? 'selected' : '' }}>
                                        قديم</option>
                                    <option value="جديد"
                                        {{ old('construction_type', $groundTank->construction_type) == 'جديد' ? 'selected' : '' }}>
                                        جديد</option>
                                </select>
                                @error('construction_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- سعة الخزان -->
                                <label for="capacity">سعة الخزان (باللتر)</label>
                                <input type="number" name="capacity" id="capacity"
                                    class="form-control @error('capacity') is-invalid @enderror"
                                    value="{{ old('capacity', $groundTank->capacity) }}" placeholder="سعة الخزان (باللتر)"
                                    required min="0">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نسبة الجاهزية -->
                                <label for="readiness_percentage">نسبة الجاهزية (من 0 إلى 100)</label>
                                <input type="number" name="readiness_percentage" id="readiness_percentage"
                                    class="form-control @error('readiness_percentage') is-invalid @enderror"
                                    value="{{ old('readiness_percentage', $groundTank->readiness_percentage) }}"
                                    placeholder="نسبة الجاهزية (من 0 إلى 100)" required min="0" max="100">
                                @error('readiness_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- المحطة التي تعبئه -->
                                <label for="feeding_station">المحطة التي تعبئه</label>
                                <input type="text" name="feeding_station" id="feeding_station"
                                    class="form-control @error('feeding_station') is-invalid @enderror"
                                    value="{{ old('feeding_station', $groundTank->feeding_station) }}"
                                    placeholder="المحطة التي تعبئه" required>
                                @error('feeding_station')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- البلدة التي تشرب منه -->
                                <label for="town_supply">البلدة التي تشرب منه</label>
                                <input type="text" name="town_supply" id="town_supply"
                                    class="form-control @error('town_supply') is-invalid @enderror"
                                    value="{{ old('town_supply', $groundTank->town_supply) }}"
                                    placeholder="البلدة التي تشرب منه" required>
                                @error('town_supply')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <h3>أبعاد الخزان:</h3>

                                <!-- قطر البوري من الداخل -->
                                <label for="pipe_diameter_inside">قطر البوري من الداخل (بالمليمتر)</label>
                                <input type="number" name="pipe_diameter_inside" id="pipe_diameter_inside"
                                    class="form-control @error('pipe_diameter_inside') is-invalid @enderror"
                                    value="{{ old('pipe_diameter_inside', $groundTank->pipe_diameter_inside) }}"
                                    placeholder="قطر البوري من الداخل (بالمليمتر)" min="0">
                                @error('pipe_diameter_inside')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- قطر البوري من الخارج -->
                                <label for="pipe_diameter_outside">قطر البوري من الخارج (بالمليمتر)</label>
                                <input type="number" name="pipe_diameter_outside" id="pipe_diameter_outside"
                                    class="form-control @error('pipe_diameter_outside') is-invalid @enderror"
                                    value="{{ old('pipe_diameter_outside', $groundTank->pipe_diameter_outside) }}"
                                    placeholder="قطر البوري من الخارج (بالمليمتر)" min="0">
                                @error('pipe_diameter_outside')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <h3>الموقع الجغرافي:</h3>

                                <!-- خط العرض -->
                                <label for="latitude">خط العرض</label>
                                <input type="number" step="any" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $groundTank->latitude) }}" placeholder="خط العرض">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- خط الطول -->
                                <label for="longitude">خط الطول</label>
                                <input type="number" step="any" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $groundTank->longitude) }}" placeholder="خط الطول">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الارتفاع -->
                                <label for="altitude">الارتفاع (بالمتر)</label>
                                <input type="number" step="any" name="altitude" id="altitude"
                                    class="form-control @error('altitude') is-invalid @enderror"
                                    value="{{ old('altitude', $groundTank->altitude) }}" placeholder="الارتفاع (بالمتر)">
                                @error('altitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- دقة الموقع -->
                                <label for="precision">دقة الموقع</label>
                                <input type="number" step="any" name="precision" id="precision"
                                    class="form-control @error('precision') is-invalid @enderror"
                                    value="{{ old('precision', $groundTank->precision) }}" placeholder="دقة الموقع">
                                @error('precision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-success">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
