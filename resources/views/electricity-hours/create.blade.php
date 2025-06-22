<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة ساعة كهرباء جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('electricity_hours.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف ساعات الكهرباء (Excel أو CSV)</label>
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

            <!-- نموذج إضافة ساعة كهرباء -->
            <form action="{{ route('electricity-hours.store') }}" method="POST" class="login-form">
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

                                <label for="station_id">اختر محطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="electricity_hours">عدد ساعات الكهرباء</label>
                                <input type="number" name="electricity_hours" id="electricity_hours"
                                    class="form-control @error('electricity_hours') is-invalid @enderror" required
                                    min="0" placeholder="عدد ساعات الكهرباء">
                                @error('electricity_hours')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="electricity_hour_number">رقم ساعة الكهرباء</label>
                                <input type="text" name="electricity_hour_number" id="electricity_hour_number"
                                    class="form-control @error('electricity_hour_number') is-invalid @enderror" required
                                    placeholder="رقم ساعة الكهرباء">
                                @error('electricity_hour_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="meter_type">نوع العداد</label>
                                <input type="text" name="meter_type" id="meter_type"
                                    class="form-control @error('meter_type') is-invalid @enderror" required
                                    placeholder="نوع العداد">
                                @error('meter_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="operating_entity">الجهة المشغلة</label>
                                <input type="text" name="operating_entity" id="operating_entity"
                                    class="form-control @error('operating_entity') is-invalid @enderror" required
                                    placeholder="الجهة المشغلة">
                                @error('operating_entity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات"></textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary">إضافة</button>

            </form>
        </div>
    </div>

@endsection
