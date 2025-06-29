<!-- ملف جديد أو معدل: resources/views/disinfection_pumps/edit.blade.php -->
<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات مضخة التعقيم</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('disinfection_pumps.update', $disinfectionPump->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <!-- الكرت 1: معلومات المضخة -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">معلومات المضخة</div>
                            <div class="card-body">
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" class="form-control" required>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $disinfectionPump->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="disinfection_pump_status">الوضع التشغيلي</label>
                                <select name="disinfection_pump_status" class="form-control">
                                    <option value="يعمل"
                                        {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'يعمل' ? 'selected' : '' }}>
                                        يعمل</option>
                                    <option value="متوقف"
                                        {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'متوقف' ? 'selected' : '' }}>
                                        متوقف</option>
                                </select>

                                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                <select name="pump_brand_model" class="form-control">
                                    <option value="">-- اختر الماركة --</option>
                                    <option value="TEKNA EVO"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'TEKNA EVO' ? 'selected' : '' }}>
                                        TEKNA EVO</option>
                                    <option value="SEKO"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SEKO' ? 'selected' : '' }}>
                                        SEKO</option>
                                    <option value="AQUA"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'AQUA' ? 'selected' : '' }}>
                                        AQUA</option>
                                    <option value="BETA"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'BETA' ? 'selected' : '' }}>
                                        BETA</option>
                                    <option value="Sempom"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Sempom' ? 'selected' : '' }}>
                                        Sempom</option>
                                    <option value="SACO"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SACO' ? 'selected' : '' }}>
                                        SACO</option>
                                    <option value="Grundfos"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Grundfos' ? 'selected' : '' }}>
                                        Grundfos</option>
                                    <option value="Antech"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'Antech' ? 'selected' : '' }}>
                                        Antech</option>
                                    <option value="FCE"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'FCE' ? 'selected' : '' }}>
                                        FCE</option>
                                    <option value="SEL"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'SEL' ? 'selected' : '' }}>
                                        SEL</option>
                                    <option value="غير معروف"
                                        {{ old('pump_brand_model', $disinfectionPump->pump_brand_model) == 'غير معروف' ? 'selected' : '' }}>
                                        غير معروف</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- الكرت 2: الأرقام والحالة -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-success">الأرقام والحالة</div>
                            <div class="card-body">
                                <label for="pump_flow_rate">غزارة المضخة (لتر/ساعة)</label>
                                <input type="number" name="pump_flow_rate" class="form-control"
                                    value="{{ old('pump_flow_rate', $disinfectionPump->pump_flow_rate) }}" step="0.01">

                                <label for="operating_pressure">ضغط العمل</label>
                                <input type="number" name="operating_pressure" class="form-control"
                                    value="{{ old('operating_pressure', $disinfectionPump->operating_pressure) }}"
                                    step="0.01">

                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" class="form-control"
                                    value="{{ old('technical_condition', $disinfectionPump->technical_condition) }}">

                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" class="form-control">{{ old('notes', $disinfectionPump->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">تحديث المضخة</button>
            </form>
        </div>
    </div>
@endsection
