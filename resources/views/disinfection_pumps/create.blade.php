<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة مضخة تعقيم جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('disinfection_pumps.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف مضخات التعقيم (Excel أو CSV)</label>
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

            <!-- نموذج إضافة مضخة تعقيم -->
            <form action="{{ route('disinfection_pumps.store') }}" method="POST" class="login-form">
                @csrf

                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: معلومات البلدة -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                معلومات المضخة
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
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الوضع التشغيلي -->
                                <label for="disinfection_pump_status">اختر الوضع التشغيلي</label>
                                <select name="disinfection_pump_status" id="disinfection_pump_status"
                                    class="form-control @error('disinfection_pump_status') is-invalid @enderror">
                                    <option value="">اختر الوضع التشغيلي</option>
                                    <option value="يعمل"
                                        {{ old('disinfection_pump_status') == 'يعمل' ? 'selected' : '' }}>يعمل</option>
                                    <option value="متوقف"
                                        {{ old('disinfection_pump_status') == 'متوقف' ? 'selected' : '' }}>متوقف</option>
                                </select>
                                @error('disinfection_pump_status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ماركة وطراز المضخة -->
                                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                <select name="pump_brand_model" id="pump_brand_model"
                                    class="form-control @error('pump_brand_model') is-invalid @enderror">
                                    <option value="">-- اختر الماركة --</option>
                                    <option value="TEKNA EVO"
                                        {{ old('pump_brand_model') == 'TEKNA EVO' ? 'selected' : '' }}>TEKNA EVO</option>
                                    <option value="SEKO" {{ old('pump_brand_model') == 'SEKO' ? 'selected' : '' }}>SEKO
                                    </option>
                                    <option value="AQUA" {{ old('pump_brand_model') == 'AQUA' ? 'selected' : '' }}>AQUA
                                    </option>
                                    <option value="BETA" {{ old('pump_brand_model') == 'BETA' ? 'selected' : '' }}>BETA
                                    </option>
                                    <option value="Sempom" {{ old('pump_brand_model') == 'Sempom' ? 'selected' : '' }}>
                                        Sempom</option>
                                    <option value="SACO" {{ old('pump_brand_model') == 'SACO' ? 'selected' : '' }}>SACO
                                    </option>
                                    <option value="Grundfos" {{ old('pump_brand_model') == 'Grundfos' ? 'selected' : '' }}>
                                        Grundfos</option>
                                    <option value="Antech" {{ old('pump_brand_model') == 'Antech' ? 'selected' : '' }}>
                                        Antech</option>
                                    <option value="FCE" {{ old('pump_brand_model') == 'FCE' ? 'selected' : '' }}>FCE
                                    </option>
                                    <option value="SEL" {{ old('pump_brand_model') == 'SEL' ? 'selected' : '' }}>SEL
                                    </option>
                                    <option value="غير معروف"
                                        {{ old('pump_brand_model') == 'غير معروف' ? 'selected' : '' }}>غير معروف</option>
                                </select>
                                @error('pump_brand_model')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-success">
                                الارقام والحالة
                            </div>
                            <div class="card-body">
                                <!-- غزارة المضخة -->
                                <label for="pump_flow_rate">غزارة المضخة (لتر/ساعة)</label>
                                <input type="number" name="pump_flow_rate" id="pump_flow_rate"
                                    class="form-control @error('pump_flow_rate') is-invalid @enderror"
                                    value="{{ old('pump_flow_rate') }}" step="0.01"
                                    placeholder="غزارة المضخة (لتر/ساعة)">
                                @error('pump_flow_rate')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ضغط العمل -->
                                <label for="operating_pressure">ضغط العمل</label>
                                <input type="number" name="operating_pressure" id="operating_pressure"
                                    class="form-control @error('operating_pressure') is-invalid @enderror"
                                    value="{{ old('operating_pressure') }}" step="0.01" placeholder="ضغط العمل">
                                @error('operating_pressure')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الحالة الفنية -->
                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" id="technical_condition"
                                    class="form-control @error('technical_condition') is-invalid @enderror"
                                    value="{{ old('technical_condition') }}" placeholder="الحالة الفنية">
                                @error('technical_condition')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="الملاحظات">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary">إضافة المضخة</button>
            </form>
        </div>
    </div>

@endsection
