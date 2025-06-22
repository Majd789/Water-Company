<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>تعديل الانفلتر</h1>

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

            <form action="{{ route('infiltrators.update', $infiltrator) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل الانفلتر
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $infiltrator->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- استطاعة الانفلتر -->
                                <label for="infiltrator_capacity">استطاعة الانفلتر</label>
                                <input type="number" name="infiltrator_capacity" id="infiltrator_capacity"
                                    class="form-control @error('infiltrator_capacity') is-invalid @enderror"
                                    value="{{ old('infiltrator_capacity', $infiltrator->infiltrator_capacity) }}"
                                    placeholder="استطاعة الانفلتر" required>
                                @error('infiltrator_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حالة الجاهزية -->
                                <label for="readiness_status">حالة الجاهزية</label>
                                <input type="number" name="readiness_status" id="readiness_status"
                                    class="form-control @error('readiness_status') is-invalid @enderror"
                                    value="{{ old('readiness_status', $infiltrator->readiness_status) }}"
                                    placeholder="حالة الجاهزية" required>
                                @error('readiness_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نوع الانفلتر -->
                                <label for="infiltrator_type">نوع الانفلتر</label>
                                <input type="text" name="infiltrator_type" id="infiltrator_type"
                                    class="form-control @error('infiltrator_type') is-invalid @enderror"
                                    value="{{ old('infiltrator_type', $infiltrator->infiltrator_type) }}"
                                    placeholder="نوع الانفلتر" required>
                                @error('infiltrator_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <input name="notes" id="notes"
                                    class="form-control @error('notes') is-invalid @enderror" placeholder="الملاحظات"
                                    value="{{ old('notes', $infiltrator->notes) }}">
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-primary">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
