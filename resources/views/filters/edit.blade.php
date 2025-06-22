<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل مرشح</h2>

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

            <!-- نموذج تعديل مرشح -->
            <form action="{{ route('filters.update', $filter->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل مرشح  
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->

                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $filter->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- استطاعة المرشح -->

                                <label for="filter_capacity">استطاعة المرشح</label>
                                <input type="number" name="filter_capacity" id="filter_capacity"
                                    class="form-control @error('filter_capacity') is-invalid @enderror"
                                    value="{{ old('filter_capacity', $filter->filter_capacity) }}"
                                    placeholder="استطاعة المرشح">
                                @error('filter_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حالة الجاهزية -->

                                <label for="readiness_status">حالة الجاهزية</label>
                                <input type="number" name="readiness_status" id="readiness_status"
                                    class="form-control @error('readiness_status') is-invalid @enderror"
                                    value="{{ old('readiness_status', $filter->readiness_status) }}"
                                    placeholder="حالة الجاهزية">
                                @error('readiness_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- نوع المرشح -->

                                <label for="filter_type">نوع المرشح</label>
                                <input type="text" name="filter_type" id="filter_type"
                                    class="form-control @error('filter_type') is-invalid @enderror"
                                    value="{{ old('filter_type', $filter->filter_type) }}" placeholder="نوع المرشح">
                                @error('filter_type')
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
