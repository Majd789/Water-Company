<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات خزان ديزل</h2>

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

            <!-- نموذج تعديل بيانات الخزان -->
            <form action="{{ route('diesel_tanks.update', $dieselTank->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل الخزان
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $dieselTank->station_id) == $station->id ? 'selected' : '' }}>
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
                                    value="{{ old('tank_name', $dieselTank->tank_name) }}" placeholder="أدخل اسم الخزان"
                                    required>
                                @error('tank_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- سعة الخزان -->
                                <label for="tank_capacity">سعة الخزان (لتر)</label>
                                <input type="number" name="tank_capacity" id="tank_capacity"
                                    class="form-control @error('tank_capacity') is-invalid @enderror"
                                    value="{{ old('tank_capacity', $dieselTank->tank_capacity) }}"
                                    placeholder="أدخل سعة الخزان (لتر)" required>
                                @error('tank_capacity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- نسبة الجاهزية -->
                                <label for="readiness_percentage">نسبة الجاهزية (%)</label>
                                <input type="number" name="readiness_percentage" id="readiness_percentage" step="0.01"
                                    class="form-control @error('readiness_percentage') is-invalid @enderror"
                                    value="{{ old('readiness_percentage', $dieselTank->readiness_percentage) }}"
                                    placeholder="أدخل نسبة الجاهزية (%)" required>
                                @error('readiness_percentage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الموقع: خط العرض وخط الطول -->
                                <label for="type">أرضي ام خارجي</label>
                                <input type="text" name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror"
                                    value="{{ old('type', $dieselTank->type) }}" placeholder="أرضي ام خارجي">
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الملاحظات -->
                                <label for="general_notes">الملاحظات</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes', $dieselTank->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- زر الإرسال -->
                                <button type="submit" class="btn btn-primary">تحديث البيانات</button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
