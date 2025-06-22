<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة مضخة أفقية جديدة</h2>
            @if(auth()->check() && auth()->user()->role_id == 'admin') 
            <form action="{{ route('horizontal_pumps.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
        
                <div class="form-group">
                    <label for="file">اختر ملف المضخات الأفقية (Excel أو CSV)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
        
                <button type="submit" class="btn btn-primary mt-3">استيراد</button>
            </form>
            @endif
            <!-- عرض الأخطاء -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج إضافة مضخة أفقية جديدة -->
            <form action="{{ route('horizontal-pumps.store') }}" method="POST" class="login-form">
                @csrf

                <!-- اختيار المحطة -->
                <label for="station_id">اختر محطة</label>
                <select name="station_id" class="form-control @error('station_id') is-invalid @enderror" required>
                    <option value="">اختر محطة</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                            {{ $station->station_name }} - {{ $station->town->town_name }}
                        </option>
                    @endforeach
                </select>
                @error('station_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- اسم المضخة -->
                <label for="pump_name">اسم المضخة</label>
                <input type="text" name="pump_name" class="form-control @error('pump_name') is-invalid @enderror" value="{{ old('pump_name') }}" placeholder="اسم المضخة">
                @error('pump_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- الحالة التشغيلية -->
                <label for="pump_status">الحالة التشغيلية</label>
                <select name="pump_status" class="form-control @error('pump_status') is-invalid @enderror">
                    <option value="">غير محدد</option>
                    <option value="يعمل" {{ old('pump_status') == 'يعمل' ? 'selected' : '' }}>تعمل</option>
                    <option value="متوقفة" {{ old('pump_status') == 'متوقفة' ? 'selected' : '' }}>متوقفة</option>
                </select>
                @error('pump_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- استطاعة المضخة -->
                <label for="pump_capacity_hp">الاستطاعة (حصان)</label>
                <input type="number" name="pump_capacity_hp" class="form-control @error('pump_capacity_hp') is-invalid @enderror" step="0.01" value="{{ old('pump_capacity_hp') }}" placeholder="الاستطاعة (حصان)">
                @error('pump_capacity_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- تدفق المضخة -->
                <label for="pump_flow_rate_m3h">تدفق المضخة (م³/ساعة)</label>
                <input type="number" name="pump_flow_rate_m3h" class="form-control @error('pump_flow_rate_m3h') is-invalid @enderror" step="0.01" value="{{ old('pump_flow_rate_m3h') }}" placeholder="تدفق المضخة (م³/ساعة)">
                @error('pump_flow_rate_m3h')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- ارتفاع الضخ -->
                <label for="pump_head">ارتفاع الضخ</label>
                <input type="number" name="pump_head" class="form-control @error('pump_head') is-invalid @enderror" step="0.01" value="{{ old('pump_head') }}" placeholder="ارتفاع الضخ">
                @error('pump_head')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- ماركة وطراز المضخة -->
                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                <input type="text" name="pump_brand_model" class="form-control @error('pump_brand_model') is-invalid @enderror" value="{{ old('pump_brand_model') }}" placeholder="ماركة وطراز المضخة">
                @error('pump_brand_model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- الحالة الفنية -->
                <label for="technical_condition">الحالة الفنية</label>
                <input type="text" name="technical_condition" class="form-control @error('technical_condition') is-invalid @enderror" value="{{ old('technical_condition') }}" placeholder="الحالة الفنية">
                @error('technical_condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- مصدر الطاقة -->
                <label for="energy_source">مصدر الطاقة</label>
                <input type="text" name="energy_source" class="form-control @error('energy_source') is-invalid @enderror" value="{{ old('energy_source') }}" placeholder="مصدر الطاقة">
                @error('energy_source')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- ملاحظات -->
                <label for="notes">ملاحظات</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>

@endsection
