<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل الخزان المرتفع</h2>

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

            <!-- نموذج تعديل الخزان المرتفع -->
            <form action="{{ route('elevated-tanks.update', $elevatedTank) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل الخزان المرتفع
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->

                                <label for="station_id">اختر محطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $elevatedTank->station_id == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- اسم الخزان -->

                                <label for="tank_name">اسم الخزان</label>
                                <input type="text" name="tank_name" id="tank_name"
                                    class="form-control @error('tank_name') is-invalid @enderror"
                                    value="{{ old('tank_name', $elevatedTank->tank_name) }}" placeholder="اسم الخزان"
                                    required>
                                @error('tank_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- الجهة المنشئة -->

                                <label for="building_entity">الجهة المنشئة</label>
                                <input type="text" name="building_entity" id="building_entity"
                                    class="form-control @error('building_entity') is-invalid @enderror"
                                    value="{{ old('building_entity', $elevatedTank->building_entity) }}"
                                    placeholder="الجهة المنشئة" required>
                                @error('building_entity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- تاريخ البناء -->

                                <label for="construction_date">تاريخ البناء</label>
                                <select name="construction_date" id="construction_date"
                                    class="form-control @error('construction_date') is-invalid @enderror" required>
                                    <option value="جديد"
                                        {{ old('construction_date', $elevatedTank->construction_date) == 'جديد' ? 'selected' : '' }}>
                                        جديد</option>
                                    <option value="قديم"
                                        {{ old('construction_date', $elevatedTank->construction_date) == 'قديم' ? 'selected' : '' }}>
                                        قديم</option>
                                </select>
                                @error('construction_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- سعة الخزان -->

                                <label for="capacity">سعة الخزان</label>
                                <input type="number" name="capacity" id="capacity"
                                    class="form-control @error('capacity') is-invalid @enderror"
                                    value="{{ old('capacity', $elevatedTank->capacity) }}" placeholder="سعة الخزان"
                                    required min="0">
                                @error('capacity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- نسبة الجاهزية -->

                                <label for="readiness_percentage">نسبة الجاهزية</label>
                                <input type="number" name="readiness_percentage" id="readiness_percentage"
                                    class="form-control @error('readiness_percentage') is-invalid @enderror"
                                    value="{{ old('readiness_percentage', $elevatedTank->readiness_percentage) }}"
                                    placeholder="نسبة الجاهزية" required min="0" max="100">
                                @error('readiness_percentage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- ارتفاع الخزان -->

                                <label for="height">ارتفاع الخزان</label>
                                <input type="number" name="height" id="height"
                                    class="form-control @error('height') is-invalid @enderror"
                                    value="{{ old('height', $elevatedTank->height) }}" placeholder="ارتفاع الخزان" required
                                    min="0">
                                @error('height')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- شكل الخزان -->

                                <label for="tank_shape">شكل الخزان</label>
                                <input type="text" name="tank_shape" id="tank_shape"
                                    class="form-control @error('tank_shape') is-invalid @enderror"
                                    value="{{ old('tank_shape', $elevatedTank->tank_shape) }}" placeholder="شكل الخزان"
                                    required>
                                @error('tank_shape')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- المحطة التي تعبئه -->

                                <label for="feeding_station">المحطة التي تعبئه</label>
                                <input type="text" name="feeding_station" id="feeding_station"
                                    class="form-control @error('feeding_station') is-invalid @enderror"
                                    value="{{ old('feeding_station', $elevatedTank->feeding_station) }}"
                                    placeholder="المحطة التي تعبئه" required>
                                @error('feeding_station')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- البلدة التي تشرب منه -->

                                <label for="town_supply">البلدة التي تشرب منه</label>
                                <input type="text" name="town_supply" id="town_supply"
                                    class="form-control @error('town_supply') is-invalid @enderror"
                                    value="{{ old('town_supply', $elevatedTank->town_supply) }}"
                                    placeholder="البلدة التي تشرب منه" required>
                                @error('town_supply')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- قطر البوري الداخل -->

                                <label for="in_pipe_diameter">قطر البوري الداخل</label>
                                <input type="number" name="in_pipe_diameter" id="in_pipe_diameter"
                                    class="form-control @error('in_pipe_diameter') is-invalid @enderror"
                                    value="{{ old('in_pipe_diameter', $elevatedTank->in_pipe_diameter) }}"
                                    placeholder="قطر البوري الداخل" required min="0">
                                @error('in_pipe_diameter')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- قطر البوري الخارج -->

                                <label for="out_pipe_diameter">قطر البوري الخارج</label>
                                <input type="number" name="out_pipe_diameter" id="out_pipe_diameter"
                                    class="form-control @error('out_pipe_diameter') is-invalid @enderror"
                                    value="{{ old('out_pipe_diameter', $elevatedTank->out_pipe_diameter) }}"
                                    placeholder="قطر البوري الخارج" required min="0">
                                @error('out_pipe_diameter')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- خط العرض -->

                                <label for="latitude">خط العرض</label>
                                <input type="number" step="any" name="latitude" id="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $elevatedTank->latitude) }}" placeholder="خط العرض">
                                @error('latitude')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- خط الطول -->

                                <label for="longitude">خط الطول</label>
                                <input type="number" step="any" name="longitude" id="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $elevatedTank->longitude) }}" placeholder="خط الطول">
                                @error('longitude')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- الارتفاع -->

                                <label for="altitude">الارتفاع</label>
                                <input type="number" step="any" name="altitude" id="altitude"
                                    class="form-control @error('altitude') is-invalid @enderror"
                                    value="{{ old('altitude', $elevatedTank->altitude) }}" placeholder="الارتفاع">
                                @error('altitude')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- دقة الموقع -->

                                <label for="precision">دقة الموقع</label>
                                <input type="number" step="any" name="precision" id="precision"
                                    class="form-control @error('precision') is-invalid @enderror"
                                    value="{{ old('precision', $elevatedTank->precision) }}" placeholder="دقة الموقع">
                                @error('precision')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- الملاحظات -->

                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="ملاحظات">{{ old('notes', $elevatedTank->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- زر الإرسال -->
                                <button type="submit" class="btn btn-primary">تحديث الخزان</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
