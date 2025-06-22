<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة خزان ديزل جديد</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('import.diesel_tanks') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">تحميل الملف</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">استيراد البيانات</button>
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

            <!-- نموذج إضافة خزان ديزل -->
            <form action="{{ route('diesel_tanks.store') }}" method="POST" class="login-form">
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
                                <label for="station_id">اختار المحطة: <span style="color: red;">*</span></label>
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
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- اسم الخزان -->
                                <label for="tank_name">اسم الخزان: <span style="color: red;">*</span></label>
                                <input type="text" name="tank_name" id="tank_name"
                                    class="form-control @error('tank_name') is-invalid @enderror"
                                    value="{{ old('tank_name') }}" placeholder="أدخل اسم الخزان" required>
                                @error('tank_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- سعة الخزان -->
                                <label for="tank_capacity">سعة الخزان (لتر): <span style="color: red;">*</span></label>
                                <input type="number" name="tank_capacity" id="tank_capacity"
                                    class="form-control @error('tank_capacity') is-invalid @enderror"
                                    value="{{ old('tank_capacity') }}" placeholder="أدخل سعة الخزان (لتر)" required>
                                @error('tank_capacity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- نسبة الجاهزية -->
                                <label for="readiness_percentage">نسبة الجاهزية (%): <span
                                        style="color: red;">*</span></label>
                                <input type="number" name="readiness_percentage" id="readiness_percentage" step="0.01"
                                    class="form-control @error('readiness_percentage') is-invalid @enderror"
                                    value="{{ old('readiness_percentage') }}" placeholder="أدخل نسبة الجاهزية (%)"
                                    required>
                                @error('readiness_percentage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <label for="type">أرضي ام خارجي : <span style="color: red;">*</span></label>
                                <input type="text" name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}"
                                    placeholder="أرضي أو خارجي" pattern="^(أرضي|خارجي)$" required>
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <!-- الملاحظات -->
                                <label for="general_notes">الملاحظات:</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes') }}</textarea>
                                @error('general_notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary">إضافة الخزان</button>
            </form>
        </div>
    </div>

@endsection
