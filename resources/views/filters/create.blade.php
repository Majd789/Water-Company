<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة مرشح جديد</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('filters.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف المرشحات (Excel أو CSV)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">استيراد</button>
                </form>
            @endif
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

            <!-- نموذج إضافة مرشح -->
            <form action="{{ route('filters.store') }}" method="POST" class="login-form">
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
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر المحطة</label>
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

                                <!-- استطاعة المرشح -->
                                <label for="filter_capacity">استطاعة المرشح</label>
                                <input type="number" name="filter_capacity" id="filter_capacity"
                                    class="form-control @error('filter_capacity') is-invalid @enderror"
                                    value="{{ old('filter_capacity') }}" placeholder="استطاعة المرشح">
                                @error('filter_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حالة الجاهزية -->
                                <label for="readiness_status">حالة الجاهزية</label>
                                <input type="number" name="readiness_status" id="readiness_status"
                                    class="form-control @error('readiness_status') is-invalid @enderror"
                                    value="{{ old('readiness_status') }}" placeholder="حالة الجاهزية">
                                @error('readiness_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نوع المرشح -->
                                <label for="filter_type">نوع المرشح</label>
                                <input type="text" name="filter_type" id="filter_type"
                                    class="form-control @error('filter_type') is-invalid @enderror"
                                    value="{{ old('filter_type') }}" placeholder="نوع المرشح">
                                @error('filter_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-primary">حفظ</button>

        </div>
    </div>

@endsection
