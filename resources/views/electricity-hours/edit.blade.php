<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل ساعة الكهرباء</h2>

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

            <!-- نموذج تعديل ساعة الكهرباء -->
            <form action="{{ route('electricity-hours.update', $electricityHour) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل ساعة الكهرباء
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $electricityHour->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- عدد ساعات الكهرباء -->
                                <label for="electricity_hours">عدد ساعات الكهرباء</label>
                                <input type="number" name="electricity_hours" id="electricity_hours"
                                    class="form-control @error('electricity_hours') is-invalid @enderror" required
                                    value="{{ old('electricity_hours', $electricityHour->electricity_hours) }}"
                                    min="0" placeholder="عدد ساعات الكهرباء">
                                @error('electricity_hours')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- رقم ساعة الكهرباء -->
                                <label for="electricity_hour_number">رقم ساعة الكهرباء</label>
                                <input type="text" name="electricity_hour_number" id="electricity_hour_number"
                                    class="form-control @error('electricity_hour_number') is-invalid @enderror" required
                                    value="{{ old('electricity_hour_number', $electricityHour->electricity_hour_number) }}"
                                    placeholder="رقم ساعة الكهرباء">
                                @error('electricity_hour_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- نوع العداد -->
                                <label for="meter_type">نوع العداد</label>
                                <input type="text" name="meter_type" id="meter_type"
                                    class="form-control @error('meter_type') is-invalid @enderror" required
                                    value="{{ old('meter_type', $electricityHour->meter_type) }}" placeholder="نوع العداد">
                                @error('meter_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الجهة المشغلة -->
                                <label for="operating_entity">الجهة المشغلة</label>
                                <input type="text" name="operating_entity" id="operating_entity"
                                    class="form-control @error('operating_entity') is-invalid @enderror" required
                                    value="{{ old('operating_entity', $electricityHour->operating_entity) }}"
                                    placeholder="الجهة المشغلة">
                                @error('operating_entity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات">{{ old('notes', $electricityHour->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
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
